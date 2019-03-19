<?php
declare(strict_types=1);

namespace Timeline\Exceptions;

use Timeline\Date\DateTime;

class DatesWrongOrder extends TimelineException
{
    public static function createFromDates(DateTime $start, DateTime $end) : DatesWrongOrder
    {
        return new DatesWrongOrder(
            "Start date ({$start})"
            . "is after end date ({$end})"
        );
    }
}
