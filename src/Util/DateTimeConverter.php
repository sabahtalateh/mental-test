<?php

namespace App\Util;

use Carbon\CarbonImmutable;

class DateTimeConverter
{
    /**
     * @param $dateTime
     * @return CarbonImmutable|\Carbon\CarbonInterface
     */
    public static function dateTimeToCarbonImmutable($dateTime)
    {
        if ($dateTime instanceof \DateTime) {
            return CarbonImmutable::createFromTimestamp($dateTime->getTimestamp());
        }
        return $dateTime;
    }
}