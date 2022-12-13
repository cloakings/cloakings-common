<?php

namespace Cloakings\CloakingsCommon;

use JsonSerializable;

interface CloakerApiResponseInterface extends JsonSerializable
{
    public function isReal(): bool;
    public function isFake(): bool;

    public function getResponseStatus(): int;
    public function getResponseHeaders(): array;
    public function getResponseBody(): string;
    public function getResponseTime(): float;
}
