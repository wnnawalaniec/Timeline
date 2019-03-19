<?php
declare(strict_types=1);

namespace Timeline;

use Timeline\Date\DateTime;
use Timeline\Event\EventInterface;
use Timeline\Exceptions\TimelineBoundaryExceeded;

abstract class Timeline
{
    /**
     * @var DateTime|null
     */
    protected $start;

    /**
     * @var DateTime|null
     */
    protected $end;

    /**
     * @var EventInterface[]
     */
    protected $events;

    /**
     * Timeline constructor.
     *
     * @param DateTime|null $start
     * @param DateTime|null $end
     */
    public function __construct(?DateTime $start = null, ?DateTime $end = null)
    {
        $this->events = [];
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @param  EventInterface $event
     * @throws TimelineBoundaryExceeded
     */
    public function add(EventInterface $event) : void
    {
        if ($this->isBeginExceeded($event)
            || $this->isEndExceeded($event)
        ) {
            throw TimelineBoundaryExceeded::createFromEvent($event, $this);
        }

        $this->events[] = $event;
    }

    /**
     * @param  EventInterface[] $events
     * @throws TimelineBoundaryExceeded
     */
    public function addMany(array $events) : void
    {
        foreach ($events as $event) {
            $this->add($event);
        }
    }

    /**
     * @return EventInterface[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * It removes all events from timeline
     */
    public function reset(): void
    {
        $this->events = [];
    }

    /**
     * @return DateTime|null
     */
    public function getStart(): ?DateTime
    {
        return $this->start;
    }

    /**
     * @return DateTime|null
     */
    public function getEnd(): ?DateTime
    {
        return $this->end;
    }

    /**
     * @param  EventInterface $event
     * @return bool
     */
    private function isBeginExceeded(EventInterface $event): bool
    {
        return $this->start && $event->getStart() < $this->start;
    }

    /**
     * @param  EventInterface $event
     * @return bool
     */
    private function isEndExceeded(EventInterface $event): bool
    {
        return $this->end && $event->getEnd() > $this->end;
    }
}
