<?php

namespace Cloakings\Tests\CloakingsCommon;

use Cloakings\CloakingsCommon\CloakModeEnum;
use Cloakings\CloakingsCommon\SampleCloaker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class SampleCloakerTest extends TestCase
{
    public function testHandle(): void
    {
        $cloaker = new SampleCloaker();

        $result = $cloaker->handle(new Request(server: ['HTTP_USER_AGENT' => 'Chrome 100']));
        self::assertSame(CloakModeEnum::Real, $result->mode);
        self::assertNull($result->response);

        $result = $cloaker->handle(new Request(server: ['HTTP_USER_AGENT' => 'GoogleBot', 'REMOTE_ADDR' => '8.8.8.8']));
        self::assertSame(CloakModeEnum::Fake, $result->mode);
        self::assertNull($result->response);

        $result = $cloaker->handle(new Request(server: ['HTTP_USER_AGENT' => 'GoogleBot', 'REMOTE_ADDR' => '11.22.33.44']));
        self::assertSame(CloakModeEnum::Response, $result->mode);
        self::assertSame(200, $result->response->getStatusCode());
        self::assertSame('Hi, fake googlebot!', $result->response->getContent());

        $result = $cloaker->handle(new Request());
        self::assertSame(CloakModeEnum::Error, $result->mode);
        self::assertSame(500, $result->response->getStatusCode());
        self::assertSame('no user agent', $result->response->getContent());
    }
}
