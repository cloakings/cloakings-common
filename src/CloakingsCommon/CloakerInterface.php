<?php

namespace Cloakings\CloakingsCommon;

use Symfony\Component\HttpFoundation\Request;

interface CloakerInterface
{
    public function handle(Request $request): CloakerResult;

    public function collectParams(Request $request): array;

    public function handleParams(array $params): CloakerResult;
}
