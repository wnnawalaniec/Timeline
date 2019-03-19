<?php
declare(strict_types=1);

namespace Timeline\Exceptions;

use Timeline\Event\EventInterface;
use Timeline\Timeline;

class TimelineBoundaryExceeded extends TimelineException
{
    public static function createFromEvent(EventInterface $event, Timeline $timeline) : TimelineBoundaryExceeded
    {
        return new TimelineBoundaryExceeded(
            "Timeline boundary are {$timeline->getStart()} - {$timeline->getEnd()}, and event is not fitting in"
            . "({$event->getStart()} - {$event->getEnd()})"
        );
    }
}
