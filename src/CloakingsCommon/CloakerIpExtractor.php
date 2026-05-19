<?php

namespace Cloakings\CloakingsCommon;

use Symfony\Component\HttpFoundation\Request;

class CloakerIpExtractor
{
    private const string DEFAULT_IP = '127.0.0.1';

    public function getIp(Request $request, CloakerIpExtractorModeEnum $mode = CloakerIpExtractorModeEnum::Aggressive): string
    {
        return match ($mode) {
            CloakerIpExtractorModeEnum::Simple => $request->getClientIp() ?? self::DEFAULT_IP,
            CloakerIpExtractorModeEnum::Aggressive => $this->getAggressive($request),
        };
    }

    private function getAggressive(Request $request): string
    {
        $ip = $request->headers->get('cf-connecting-ip', '');
        if ($ip !== '') {
            return explode(',', $ip, 2)[0];
        }

        $trustedProxies = Request::getTrustedProxies();
        $trustedHeaderSet = Request::getTrustedHeaderSet();
        Request::setTrustedProxies(['0.0.0.0/0'], -1);
        $ip = $request->getClientIp() ?? self::DEFAULT_IP;
        Request::setTrustedProxies($trustedProxies, $trustedHeaderSet);

        return $ip;
    }
}
