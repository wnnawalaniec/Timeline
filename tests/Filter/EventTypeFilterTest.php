<?php
declare(strict_types=1);

namespace Tests\Filter;

use Tests\BaseTestCase;
use Timeline\Date\DateTime;
use Timeline\Event\Event;
use Timeline\Filter\EventTypeFilter;
use Timeline\Implementations\NoOverlappingTimeline;

class EventTypeFilterTest extends BaseTestCase
{
    public function testFiltering_NoEventsFitsFilter_ReturnEmptyTimeline(): void
    {
        $filter = new EventTypeFilter(Event::class);
        $timelineBegin = new DateTime('2019-01-01');
        $timelineEnd = new DateTime('2020-01-01');
        $expectedTimeline = new NoOverlappingTimeline($timelineBegin, $timelineEnd);
        $filteredTimeLine = new NoOverlappingTimeline($timelineBegin, $timelineEnd);
        $eventBegin = new DateTime('2019-02-01');
        $eventEnd = new DateTime('2019-02-04');
        $someEvent = new StubEvent($eventBegin, $eventEnd);
        $filteredTimeLine->add($someEvent);

        $filteredTimeLine = $filter->filter($filteredTimeLine);

        $this->assertEquals($expectedTimeline, $filteredTimeLine);
    }

    public function testFiltering_SomeEventsFitsFilter_ReturnTimelineWithEventsFittingFilter(): void
    {
        $filter = new EventTypeFilter(Event::class);
        $timelineBegin = new DateTime('2019-01-01');
        $timelineEnd = new DateTime('2020-01-01');
        $expectedTimeline = new NoOverlappingTimeline($timelineBegin, $timelineEnd);
        $filteredTimeLine = new NoOverlappingTimeline($timelineBegin, $timelineEnd);
        $notFittingEventBegin = new DateTime('2019-02-01');
        $notFittingEventEnd = new DateTime('2019-02-04');
        $notFittingEvent = new StubEvent($notFittingEventBegin, $notFittingEventEnd);
        $fittingEventBegin = new DateTime('2019-03-01');
        $fittingEventEnd = new DateTime('2019-03-04');
        $fittingEvent = new Event($fittingEventBegin, $fittingEventEnd);
        $expectedTimeline->add($fittingEvent);
        $filteredTimeLine->add($fittingEvent);
        $filteredTimeLine->add($notFittingEvent);

        $filteredTimeLine = $filter->filter($filteredTimeLine);

        $this->assertEquals($expectedTimeline, $filteredTimeLine);
    }
}
