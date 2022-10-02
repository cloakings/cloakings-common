<?php

namespace Cloakings\CloakingsCommon;

use Symfony\Component\HttpFoundation\Response;

class CloakerResult
{
    public function __construct(
        public readonly CloakModeEnum $mode,
        public readonly ?Response $response = null,
    ) {
    }
}
