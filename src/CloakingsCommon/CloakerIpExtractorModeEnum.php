<?php

namespace Cloakings\CloakingsCommon;

enum CloakerIpExtractorModeEnum: string
{
    case Simple = 'simple';
    case Aggressive = 'aggressive';
}
