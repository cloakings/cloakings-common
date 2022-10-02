<?php /** @noinspection PhpUselessTrailingCommaInspection */

namespace Cloakings\CloakingsCommon;

use Symfony\Component\HttpFoundation\Response;

class CloakerResult
{
    public function __construct(
        public readonly CloakModeEnum $mode,
        public readonly ?Response $response = null,
        public readonly float $probability = 1.0, // 0.0 - 1.0
    ) {
    }
}
