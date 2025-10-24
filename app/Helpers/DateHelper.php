<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function formatWithWeekday($date): string
    {
        if (!$date) return '';
        $carbon = Carbon::parse($date);
        $weekdays = ['日', '月', '火', '水', '木', '金', '土'];
        return $carbon->format('Y-m-d') . ' (' . $weekdays[$carbon->dayOfWeek] . ')';
    }
}