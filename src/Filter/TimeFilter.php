<?php
declare(strict_types=1);

namespace Timeline\Filter;

use Timeline\Date\DateTime;
use Timeline\Timeline;

class TimeFilter implements Filter
{
    /**
     * @var DateTime
     */
    private $from;

    /**
     * @var DateTime
     */
    private $to;

    /**
     * @param DateTime $from
     * @param DateTime $to
     */
    public function __construct(DateTime $from, DateTime $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function filter(Timeline $timeline): Timeline
    {
        $t = clone $timeline;
        $t->reset();

        foreach ($timeline->getEvents() as $event) {
            if ($event->getStart() >= $this->from && $event->getEnd() <= $this->to) {
                $t->add($event);
            }
        }

        return $t;
    }
}
