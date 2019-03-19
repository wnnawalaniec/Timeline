<?php
declare(strict_types=1);

namespace Tests\Filter;

use Timeline\Date\DateTime;
use Timeline\Event\Event;
use Timeline\Filter\EventTypeFilter;
use PHPUnit\Framework\TestCase;
use Timeline\Filter\FilterComposite;
use Timeline\Filter\TimeFilter;
use Timeline\Implementations\NoOverlappingTimeline;

class FilterCompositeTest extends TestCase
{
    public function testChainingFilters(): void
    {
        // arrange
        $firstFilter = new EventTypeFilter(Event::class);
        $filterBegin = new DateTime('2019-02-01');
        $filterEnd = new DateTime('2019-03-01');
        $secondFilter = new TimeFilter($filterBegin, $filterEnd);
        $compositeFilter = new FilterComposite();
        $compositeFilter->addFilter($firstFilter)->addFilter($secondFilter);

        $timeline = new NoOverlappingTimeline();

        $firstEventBegin = new DateTime('2019-02-01');
        $firstEventEnd = new DateTime('2019-02-02');
        $eventNotFittingFirstFilterButFittingSecond = new StubEvent($firstEventBegin, $firstEventEnd, 'first');

        $secondEventBegin = new DateTime('2020-01-01');
        $secondEventEnd = new DateTime('2020-01-01');
        $eventNotFittingSecondFilterButFittingFirst = new Event($secondEventBegin, $secondEventEnd, 'second');

        $thirdEventBegin = new DateTime('2019-02-03');
        $thirdEventEnd = new DateTime('2019-02-04');
        $eventFittingBoth = new Event($thirdEventBegin, $thirdEventEnd, 'third');

        $timeline->add($eventFittingBoth);
        $timeline->add($eventNotFittingSecondFilterButFittingFirst);
        $timeline->add($eventNotFittingFirstFilterButFittingSecond);

        $expectedTimeline = new NoOverlappingTimeline();
        $expectedTimeline->add($eventFittingBoth);

        // act
        $filteredTimeline = $compositeFilter->filter($timeline);

        // assert
        $this->assertEquals($expectedTimeline, $filteredTimeline);
    }
}
