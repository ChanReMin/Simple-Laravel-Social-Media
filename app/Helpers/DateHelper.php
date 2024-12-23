<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function diffForHumansBefore($date)
    {
        $now = Carbon::now();
        if ($date->lessThan($now)) {
            return $date->diffForHumans($now, true) . ' before';
        }
        return $date->diffForHumans();
    }
}
