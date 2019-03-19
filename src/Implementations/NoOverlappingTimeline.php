<?php
declare(strict_types=1);

namespace Timeline\Implementations;

use Timeline\Event\EventInterface;
use Timeline\Exceptions\EventsCannotOverlapAnotherEvents;
use Timeline\Exceptions\TimelineBoundaryExceeded;
use Timeline\Timeline;

class NoOverlappingTimeline extends Timeline
{
    /**
     * @param  EventInterface $event
     * @throws TimelineBoundaryExceeded
     * @throws EventsCannotOverlapAnotherEvents
     */
    public function add(EventInterface $event) : void
    {
        if ($this->isOverlapping($event)) {
            throw new EventsCannotOverlapAnotherEvents();
        }

        parent::add($event);
    }

    private function isOverlapping(EventInterface $event) : bool
    {
        foreach ($this->events as $e) {
            if ((                $event->getStart() >= $e->getStart()
                && $event->getStart() <= $e->getEnd())
                || (                $event->getEnd() <= $e->getEnd()
                && $event->getEnd() >= $e->getStart())
            ) {
                return true;
            }
        }

        return false;
    }
}
