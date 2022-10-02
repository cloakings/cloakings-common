<?php

namespace Cloakings\Tests\CloakingsCommon;

use Cloakings\CloakingsCommon\AlwaysRealCloaker;
use Cloakings\CloakingsCommon\ChainCloaker;
use Cloakings\CloakingsCommon\CloakModeEnum;
use Cloakings\CloakingsCommon\SampleCloaker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class ChainCloakerTest extends TestCase
{
    public function testHandle(): void
    {
        $cloaker = new ChainCloaker([
            new AlwaysRealCloaker(),
            new SampleCloaker(),
        ]);

        $result = $cloaker->handle(new Request(server: ['HTTP_USER_AGENT' => 'Chrome 100']));
        self::assertSame(CloakModeEnum::Real, $result->mode);
        self::assertNull($result->response);

        $result = $cloaker->handle(new Request(server: ['HTTP_USER_AGENT' => 'GoogleBot', 'REMOTE_ADDR' => '8.8.8.8']));
        self::assertSame(CloakModeEnum::Fake, $result->mode);
        self::assertNull($result->response);
    }
}
