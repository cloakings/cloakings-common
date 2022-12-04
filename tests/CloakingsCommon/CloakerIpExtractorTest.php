<?php

namespace Cloakings\Tests\CloakingsCommon;

use Cloakings\CloakingsCommon\CloakerIpExtractor;
use Cloakings\CloakingsCommon\CloakerIpExtractorModeEnum;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class CloakerIpExtractorTest extends TestCase
{
    private CloakerIpExtractor $ipExtractor;

    protected function setUp(): void
    {
        $this->ipExtractor = new CloakerIpExtractor();
    }

    public function testGetIp(): void
    {
        $request = Request::create('https://example.com', server: [
            'REMOTE_ADDR' => '1.2.3.4',
        ]);

        self::assertSame('1.2.3.4', $this->ipExtractor->getIp($request));
    }

    public function testGetIpCloudflare(): void
    {
        $request = Request::create('https://example.com', server: [
            'REMOTE_ADDR' => '127.0.0.1',
            'HTTP_CF_CONNECTING_IP' => '1.2.3.4',
        ]);

        self::assertSame('1.2.3.4', $this->ipExtractor->getIp($request));
    }

    public function testGetIpCloudflareSimple(): void
    {
        $request = Request::create('https://example.com', server: [
            'REMOTE_ADDR' => '1.1.1.1', // will use this IP because simple mode doesn't use header "cf-connecting-ip"
            'HTTP_CF_CONNECTING_IP' => '1.2.3.4',
        ]);

        self::assertSame('1.1.1.1', $this->ipExtractor->getIp($request, CloakerIpExtractorModeEnum::Simple));
    }
}
