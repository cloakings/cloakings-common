<?php

namespace Cloakings\CloakingsCommon;

use Symfony\Component\HttpFoundation\Request;

class ChainCloaker implements CloakerInterface
{
    /** @var CloakerInterface[] */
    private array $cloakers;

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

    public function handle(Request $request): CloakerResult
    {
        $successResult = null;
        $errorResult = new CloakerResult(CloakModeEnum::Error);

        foreach ($this->cloakers as $cloaker) {
            $r = $cloaker->handle($request);
            if ($r->mode === CloakModeEnum::Fake || $r->mode === CloakModeEnum::Response) {
                return $r;
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
