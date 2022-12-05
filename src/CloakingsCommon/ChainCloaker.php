<?php

namespace Cloakings\CloakingsCommon;

use Symfony\Component\HttpFoundation\Request;

class ChainCloaker implements CloakerInterface
{
    /** @var CloakerInterface[] */
    private array $cloakers;

    private float $minFakeProbability = 0.5;

    public function __construct(
        array $cloakers,
    ) {
        $this->cloakers = [];
        foreach ($cloakers as $cloaker) {
            if (!($cloaker instanceof CloakerInterface)) {
                throw new \InvalidArgumentException('invalid_cloaker');
            }
            $this->cloakers[] = $cloaker;
        }
    }

    public function setMinFakeProbability(float $minFakeProbability): self
    {
        $this->minFakeProbability = $minFakeProbability;

        return $this;
    }

    public function handle(Request $request): CloakerResult
    {
        return $this->handleParams($this->collectParams($request));
    }

    public function collectParams(Request $request): array
    {
        return ['request' => $request];
    }

    public function handleParams(array $params): CloakerResult
    {
        $successResult = null;
        $errorResult = new CloakerResult(CloakModeEnum::Error);

        foreach ($this->cloakers as $cloaker) {
            $r = $cloaker->handle($params['request']);
            if ($r->mode === CloakModeEnum::Fake || $r->mode === CloakModeEnum::Response) {
                if ($r->probability >= $this->minFakeProbability) {
                    return $r;
                }
                $successResult = $r;
            }
            if ($r->mode === CloakModeEnum::Real) {
                $successResult = $r;
            }
            if ($r->mode === CloakModeEnum::Error) {
                $errorResult = $r;
            }
        }

        return $successResult ?? $errorResult;
    }
}
