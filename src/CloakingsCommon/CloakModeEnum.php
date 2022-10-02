<?php

namespace Cloakings\CloakingsCommon;

enum CloakModeEnum: string
{
    case Fake = 'fake';
    case Real = 'real';
    case Response = 'response';
    case Error = 'error';
}
