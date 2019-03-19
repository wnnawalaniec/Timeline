<?php
declare(strict_types=1);

namespace Tests\Date;

use Tests\BaseTestCase;
use Timeline\Date\DateTime;
use Timeline\Date\DateTimeFormatter;

class DateTimeTest extends BaseTestCase
{
    public function testToString_ReturnsFormattedDate(): void
    {
        $formatter = new DateTimeFormatter('Y-m-d H:i:s');
        $expectedDate = "2019-01-01 12:00:00";
        $date = new DateTime($expectedDate, null, $formatter);

        $formattedDate = (string) $date;

        $this->assertEquals($expectedDate, $formattedDate);
    }
}
