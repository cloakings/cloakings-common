<?php

namespace Cloakings\CloakingsCommon;

use Symfony\Component\HttpFoundation\Request;

class CloakerIpExtractor
{
    private const DEFAULT_IP = '127.0.0.1';

    public function getIp(Request $request, CloakerIpExtractorModeEnum $mode = CloakerIpExtractorModeEnum::Aggressive): string
    {
        if ($mode === CloakerIpExtractorModeEnum::Simple) {
            return $request->getClientIp() ?? self::DEFAULT_IP;
        }

        if ($mode === CloakerIpExtractorModeEnum::Aggressive) {
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

        return $request->getClientIp() ?? self::DEFAULT_IP;
    }
}
