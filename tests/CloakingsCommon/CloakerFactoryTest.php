<?php

namespace Cloakings\Tests\CloakingsCommon;

use Cloakings\CloakingsCommon\CloakerFactory;
use Cloakings\CloakingsCommon\CloakModeEnum;
use Cloakings\CloakingsCommon\SampleCloaker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class CloakerFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $cloaker = (new CloakerFactory())->create(SampleCloaker::class);

        $result = $cloaker->handle(new Request(server: ['HTTP_USER_AGENT' => 'Chrome 100']));
        self::assertSame(CloakModeEnum::Real, $result->mode);
        self::assertNull($result->response);

        $result = $cloaker->handle(new Request(server: ['HTTP_USER_AGENT' => 'GoogleBot']));
        self::assertSame(CloakModeEnum::Fake, $result->mode);
        self::assertNull($result->response);
    }

    public function testCreateChain(): void
    {
        $cloaker = (new CloakerFactory())->createChain([
            SampleCloaker::class,
        ]);

        $result = $cloaker->handle(new Request(server: ['HTTP_USER_AGENT' => 'Chrome 100']));
        self::assertSame(CloakModeEnum::Real, $result->mode);
        self::assertNull($result->response);

        $result = $cloaker->handle(new Request(server: ['HTTP_USER_AGENT' => 'GoogleBot']));
        self::assertSame(CloakModeEnum::Fake, $result->mode);
        self::assertNull($result->response);
    }
}
