<?php
declare(strict_types=1);

namespace Timeline\Filter;

use Timeline\Timeline;

class EventTypeFilter implements Filter
{
    /**
     * @var string
     */
    private $type;

    /**
     * EventTypeFilter constructor.
     *
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function filter(Timeline $timeline): Timeline
    {
        $t = clone $timeline;
        $t->reset();
        foreach ($timeline->getEvents() as $event) {
            if (get_class($event) === $this->type) {
                $t->add($event);
            }
        }

        return $t;
    }
}
