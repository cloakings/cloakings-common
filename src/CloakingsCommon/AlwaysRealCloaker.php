<?php

namespace Cloakings\CloakingsCommon;

use Symfony\Component\HttpFoundation\Request;

class AlwaysRealCloaker implements CloakerInterface
{
    public function handle(Request $request): CloakerResult
    {
        return new CloakerResult(CloakModeEnum::Real);
    }
}
