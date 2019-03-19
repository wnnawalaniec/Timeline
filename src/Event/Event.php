<?php
declare(strict_types=1);

namespace Timeline\Event;

use Timeline\Date\DateTime;
use Timeline\Exceptions\DatesWrongOrder;

class Event implements EventInterface
{
    /**
     * @var DateTime
     */
    protected $start;

    /**
     * @var DateTime
     */
    protected $end;

    /**
     * @var string
     */
    protected $name;

    /**
     * Event constructor.
     *
     * @param  DateTime $start
     * @param  DateTime $end
     * @param  string   $name
     * @throws DatesWrongOrder
     */
    public function __construct(DateTime $start, DateTime $end, string $name = '')
    {
        if ($start > $end) {
            throw DatesWrongOrder::createFromDates($start, $end);
        }

        $this->start = $start;
        $this->end = $end;
        $this->name = $name;
    }

    /**
     * @return DateTime
     */
    public function getStart(): DateTime
    {
        return $this->start;
    }

    /**
     * @return DateTime
     */
    public function getEnd(): DateTime
    {
        return $this->end;
    }

    public function __toString() : string
    {
        return $this->name ? $this->name : get_class($this);
    }
}
