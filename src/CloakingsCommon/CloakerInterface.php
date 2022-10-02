<?php

namespace Cloakings\CloakingsCommon;

use Symfony\Component\HttpFoundation\Request;

interface CloakerInterface
{
    public function handle(Request $request): CloakerResult;
}
