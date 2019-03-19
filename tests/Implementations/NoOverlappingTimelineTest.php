<?php
declare(strict_types=1);

namespace Tests\Implementations;

use Timeline\Date\DateTime;
use Timeline\Event\Event;
use Timeline\Exceptions\TimelineBoundaryExceeded;
use Timeline\Exceptions\EventsCannotOverlapAnotherEvents;
use Timeline\Implementations\NoOverlappingTimeline;

class NoOverlappingTimelineTest extends \Tests\BaseTestCase
{
    public function testAddingEvent_EventIsNotCollidingWithOtherEvents_EventIsAddedToTimeline(): void
    {
        $timelineBegin = new DateTime('2019-01-01');
        $timelineEnd = new DateTime('2019-01-10');
        $timeline = new NoOverlappingTimeline($timelineBegin, $timelineEnd);
        $startDate = new DateTime('2019-01-01');
        $endDate = new DateTime('2019-01-10');
        $event = new Event(
            $startDate,
            $endDate
        );

        $timeline->add($event);

        $eventsFromTimeLine = $timeline->getEvents();
        $this->assertContains($event, $eventsFromTimeLine);
    }

    public function testAddingManyEvents_EventsAreNotCollidingWithEachOthers_EventsAreAdded(): void
    {
        $timelineBegin = new DateTime('2019-01-01');
        $timelineEnd = new DateTime('2019-01-10');
        $timeline = new NoOverlappingTimeline($timelineBegin, $timelineEnd);
        $events = [
            new Event(
                new DateTime('2019-01-01'),
                new DateTime('2019-01-02')
            ),
            new Event(
                new DateTime('2019-01-03'),
                new DateTime('2019-01-04')
            ),
        ];

        $timeline->addMany($events);

        $eventsFromTimeLine = $timeline->getEvents();
        $this->assertEquals($events, $eventsFromTimeLine);
    }

    /**
     * @dataProvider datesExceedingTimelineProvider
     */
    public function testAddingEvent_EventIsExceedingTimeLine_ThrowsException(DateTime $begin, DateTime $end): void
    {
        $timelineBegin = new DateTime('2019-01-02');
        $timelineEnd = new DateTime('2019-01-03');
        $timeline = new NoOverlappingTimeline(
            $timelineBegin,
            $timelineEnd
        );
        $event = new Event($begin, $end);

        $acts = function () use ($timeline, $event) {
            $timeline->add($event);
        };

        $expectedException = TimelineBoundaryExceeded::createFromEvent($event, $timeline);
        $this->assertException($expectedException, $acts);
    }

    public function datesExceedingTimelineProvider() : array
    {
        return [
            [new DateTime('2019-01-01'), new DateTime('2019-01-02')],
            [new DateTime('2019-01-03'), new DateTime('2019-01-04')],
            [new DateTime('2019-01-01'), new DateTime('2019-01-05')]
        ];
    }

    /**
     * @dataProvider datesExceedingTimelineProvider
     */
    public function testAddingManyEvent_SomeEventsAreExceedingTimeLine_ThrowsException(DateTime $begin, DateTime $end): void
    {
        $timelineBegin = new DateTime('2019-01-01');
        $timelineEnd = new DateTime('2019-01-02');
        $timeline = new NoOverlappingTimeline(
            $timelineBegin,
            $timelineEnd
        );
        $eventExceedingTimeline = new Event(
            new DateTime('2019-01-03'),
            new DateTime('2019-01-04')
        );
        $eventWithinTimeline = new Event(
            new DateTime('2019-01-01'),
            new DateTime('2019-01-02')
        );
        $events = [
            $eventWithinTimeline,
            $eventExceedingTimeline,
        ];

        $acts = function () use ($timeline, $events) {
            $timeline->addMany($events);
        };

        $expectedException = TimelineBoundaryExceeded::createFromEvent($eventExceedingTimeline, $timeline);
        $this->assertException($expectedException, $acts);
    }

    /**
     * @dataProvider overlappingEventsProvider
     */
    public function testAddingEvent_EventOverlapsOther_ThrowsException(Event $e1, Event $e2): void
    {
        $timeline = new NoOverlappingTimeline();
        $eventAlreadyAdded = $e1;
        $timeline->add($eventAlreadyAdded);
        $overlappingEvent = $e2;

        $acts = function () use ($timeline, $overlappingEvent) {
            $timeline->add($overlappingEvent);
        };

        $expectedException = new EventsCannotOverlapAnotherEvents();
        $this->assertException($expectedException, $acts);
    }

    public function overlappingEventsProvider() : array
    {
        return [
            [
                new Event(
                    new DateTime('2019-01-01'),
                    new DateTime('2019-01-10')
                ),
                new Event(
                    new DateTime('2018-12-01'),
                    new DateTime('2019-01-01')
                )
            ],
            [
                new Event(
                    new DateTime('2019-01-01'),
                    new DateTime('2019-01-10')
                ),
                new Event(
                    new DateTime('2019-01-10'),
                    new DateTime('2019-02-01')
                )
            ],
            [
                new Event(
                    new DateTime('2019-01-01'),
                    new DateTime('2019-01-01')
                ),
                new Event(
                    new DateTime('2019-01-01'),
                    new DateTime('2019-02-01')
                )
            ],
            [
                new Event(
                    new DateTime('2019-01-01'),
                    new DateTime('2019-01-01')
                ),
                new Event(
                    new DateTime('2018-12-01'),
                    new DateTime('2019-01-01')
                )
            ]
        ];
    }

    public function testReset_TimelineHasSomeEvents_ClearsAllEventsFromTimeline(): void
    {
        $event = new Event(
            new DateTime('2019-01-01'),
            new DateTime('2019-01-10')
        );
        $timeline = new NoOverlappingTimeline();
        $timeline->add($event);

        $timeline->reset();

        $this->assertEmpty($timeline->getEvents());
    }
}
