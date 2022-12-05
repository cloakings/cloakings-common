<?php

namespace Cloakings\CloakingsCommon;

use Symfony\Component\HttpFoundation\Request;

class AlwaysRealCloaker implements CloakerInterface
{
    public function handle(Request $request): CloakerResult
    {
        return $this->handleParams($this->collectParams($request));
    }

    public function collectParams(Request $request): array
    {
        return [];
    }

    public function handleParams(array $params): CloakerResult
    {
        return new CloakerResult(CloakModeEnum::Real);
    }
}
