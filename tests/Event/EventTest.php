<?php
declare(strict_types=1);

namespace Tests\Event;

use Tests\BaseTestCase;
use Timeline\Date\DateTime;
use Timeline\Event\Event;
use Timeline\Exceptions\DatesWrongOrder;

class EventTest extends BaseTestCase
{
    public function testCreatingEvent_DatesAreInWrongOrder_ThrowsException(): void
    {
        $startDate = new DateTime('2019-01-02');
        $endDate = new DateTime('2019-01-01');

        $acts = function () use ($startDate, $endDate) {
            new Event($startDate, $endDate);
        };

        $expectedException = DatesWrongOrder::createFromDates($startDate, $endDate);
        $this->assertException($expectedException, $acts);
    }

    public function testCreatingEvent_SingleDayEvent_EventIsCreated(): void
    {
        $startDate = new DateTime('2019-01-01');
        $endDate = new DateTime('2019-01-01');

        $event = new Event($startDate, $endDate);

        $this->assertNotNull($event);
    }

    public function testToString_EventHasName_ReturnsEventsName(): void
    {
        $startDate = new DateTime('2019-01-01');
        $endDate = new DateTime('2019-01-01');
        $expectedName = "event#1";

        $event = new Event($startDate, $endDate, $expectedName);

        $this->assertEquals($expectedName, $event);
    }

    public function testToString_EventHasNoName_ReturnsEventsClassName(): void
    {
        $startDate = new DateTime('2019-01-01');
        $endDate = new DateTime('2019-01-01');
        $expectedName = "Timeline\\Event\Event";

        $event = new Event($startDate, $endDate);

        $this->assertEquals($expectedName, $event);
    }
}
