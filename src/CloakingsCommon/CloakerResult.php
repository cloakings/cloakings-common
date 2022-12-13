<?php
/** @noinspection PhpUselessTrailingCommaInspection */

namespace Cloakings\CloakingsCommon;

use JsonSerializable;
use Symfony\Component\HttpFoundation\Response;

class CloakerResult implements JsonSerializable
{
    public function __construct(
        public readonly CloakModeEnum $mode,
        public readonly ?Response $response = null,
        public readonly ?CloakerApiResponseInterface $apiResponse = null,
        public readonly array $params = [],
        public readonly float $probability = 1.0, // 0.0 - 1.0
    ) {
    }

    public function isReal(): bool
    {
        return $this->mode === CloakModeEnum::Real;
    }

    public function isFake(): bool
    {
        return $this->mode === CloakModeEnum::Fake;
    }

    public function getResponseStatus(): int
    {
        return $this->apiResponse->getResponseStatus();
    }

    public function getResponseHeaders(): array
    {
        return $this->apiResponse->getResponseHeaders();
    }

    public function getResponseBody(): string
    {
        return $this->apiResponse->getResponseBody();
    }

    public function getResponseTime(): float
    {
        return $this->apiResponse->getResponseTime();
    }

    public function jsonSerialize(): array
    {
        return [
            'mode' => $this->mode->value,
            'response' => [
                'status_code' => $this->response->getStatusCode(),
                'headers' => $this->response->headers->all(),
                'content' => $this->response->getContent(),
            ],
            'api_response' => $this->apiResponse->jsonSerialize(),
            'params' => $this->params,
            'probability' => $this->probability,
        ];
    }
}
