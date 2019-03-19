<?php
declare(strict_types=1);

namespace Tests\Filter;

use Tests\BaseTestCase;
use Timeline\Date\DateTime;
use Timeline\Event\Event;
use Timeline\Filter\EventTypeFilter;
use Timeline\Filter\TimeFilter;
use Timeline\Implementations\NoOverlappingTimeline;

class TimeFilterTest extends BaseTestCase
{
    public function testFiltering_NoEventsFitsFilter_ReturnEmptyTimeline(): void
    {
        $filterBegin = new DateTime('2019-01-01');
        $filterEnd = new DateTime('2019-01-01');
        $filter = new TimeFilter($filterBegin, $filterEnd);
        $timelineBegin = new DateTime('2019-01-01');
        $timelineEnd = new DateTime('2020-01-01');
        $expectedTimeline = new NoOverlappingTimeline($timelineBegin, $timelineEnd);
        $filteredTimeLine = new NoOverlappingTimeline($timelineBegin, $timelineEnd);
        $eventBegin = new DateTime('2019-02-01');
        $eventEnd = new DateTime('2019-02-04');
        $someEvent = new Event($eventBegin, $eventEnd);
        $filteredTimeLine->add($someEvent);

        $filteredTimeLine = $filter->filter($filteredTimeLine);

        $this->assertEquals($expectedTimeline, $filteredTimeLine);
    }

    public function testFiltering_SomeEventsFitsFilter_ReturnTimelineWithEventsFittingFilter(): void
    {
        $filterBegin = new DateTime('2019-03-01');
        $filterEnd = new DateTime('2019-03-01');
        $filter = new TimeFilter($filterBegin, $filterEnd);
        $timelineBegin = new DateTime('2019-01-01');
        $timelineEnd = new DateTime('2020-01-01');
        $expectedTimeline = new NoOverlappingTimeline($timelineBegin, $timelineEnd);
        $filteredTimeLine = new NoOverlappingTimeline($timelineBegin, $timelineEnd);
        $notFittingEventBegin = new DateTime('2019-02-01');
        $notFittingEventEnd = new DateTime('2019-02-04');
        $notFittingEvent = new Event($notFittingEventBegin, $notFittingEventEnd);
        $fittingEventBegin = new DateTime('2019-03-01');
        $fittingEventEnd = new DateTime('2019-03-01');
        $fittingEvent = new Event($fittingEventBegin, $fittingEventEnd);
        $expectedTimeline->add($fittingEvent);
        $filteredTimeLine->add($fittingEvent);
        $filteredTimeLine->add($notFittingEvent);

        $filteredTimeLine = $filter->filter($filteredTimeLine);

        $this->assertEquals($expectedTimeline, $filteredTimeLine);
    }
}
