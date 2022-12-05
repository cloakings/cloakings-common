<?php

namespace Cloakings\CloakingsCommon;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SampleCloaker implements CloakerInterface
{
    /**
     * Let's imagine that we have site with paid content.
     * If visitor is Googlebot we want to show full content ("fake site").
     * We detect Googlebot by user agent.
     * If visitor is human user we show partial content ("real site").
     *
     * Errors:
     * - visitor without user agent - error "no user agent"
     * - visitor with googlebot user agent but incorrect IP - error "fake googlebot"
     */
    public function handle(Request $request): CloakerResult
    {
        return $this->handleParams($this->collectParams($request));
    }

    public function collectParams(Request $request): array
    {
        return [
            'user_agent' => $request->headers->get('user-agent') ?? '',
            'ip' => $request->getClientIp() ?? '',
        ];
    }

    public function handleParams(array $params): CloakerResult
    {
        $userAgent = $params['user_agent'] ?? '';
        $ip = $params['ip'] ?? '';

        if ($userAgent === '') {
            return new CloakerResult(
                mode: CloakModeEnum::Error,
                response: new Response('no user agent', Response::HTTP_INTERNAL_SERVER_ERROR),
            );
        }

        if ($this->isGooglebotUserAgent($userAgent)) {
            if ($this->isGoogleIp($ip)) {
                $mode = CloakModeEnum::Fake;
            } else {
                return new CloakerResult(
                    mode: CloakModeEnum::Response,
                    response: new Response('Hi, fake googlebot!'),
                );
            }
        } else {
            $mode = CloakModeEnum::Real;
        }

        return new CloakerResult($mode);
    }

    private function isGooglebotUserAgent(string $userAgent): bool
    {
        return str_contains(mb_strtolower($userAgent), 'googlebot');
    }

    private function isGoogleIp(string $ip): bool
    {
        return $ip === '8.8.8.8'; // not real
    }
}
