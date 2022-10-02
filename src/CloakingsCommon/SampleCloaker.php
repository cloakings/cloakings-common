<?php

namespace Cloakings\CloakingsCommon;

use Symfony\Component\HttpFoundation\Request;

class SampleCloaker implements CloakerInterface
{
    public function handle(Request $request): CloakerResult
    {
        $userAgent = $request->headers->get('user-agent') ?? '';
        if ($this->isBotUserAgent($userAgent)) {
            $mode = CloakModeEnum::Fake;
        } else {
            $mode = CloakModeEnum::Real;
        }

        return new CloakerResult($mode);
    }

    private function isBotUserAgent(string $userAgent): bool
    {
        return str_contains(mb_strtolower($userAgent), 'bot');
    }
}
