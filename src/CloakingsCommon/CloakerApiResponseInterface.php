<?php

namespace Cloakings\CloakingsCommon;

interface CloakerApiResponseInterface
{
    public function isReal(): bool;
    public function isFake(): bool;

    public function getResponseStatus(): int;
    public function getResponseHeaders(): array;
    public function getResponseBody(): string;
    public function getResponseTime(): float;
}
