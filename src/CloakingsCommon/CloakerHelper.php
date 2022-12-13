<?php

namespace Cloakings\CloakingsCommon;

class CloakerHelper
{
    public static function flattenHeaders(array $a): array
    {
        $result = [];
        foreach ($a as $key => $value) {
            if (is_array($value) && count($value) === 1) {
                $value = $value[array_keys($value)[0]];
            }
            $result[$key] = $value;
        }

        return $result;
    }
}
