<?php

namespace Cloakings\Tests\CloakingsCommon;

use Cloakings\CloakingsCommon\AlwaysRealCloaker;
use Cloakings\CloakingsCommon\CloakModeEnum;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class AlwaysRealCloakerTest extends TestCase
{
    public function testHandle(): void
    {
        $cloaker = new AlwaysRealCloaker();

        self::assertSame($cloaker->handle(new Request())->mode, CloakModeEnum::Real);
    }
}
