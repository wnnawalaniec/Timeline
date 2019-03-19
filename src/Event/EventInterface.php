<?php
declare(strict_types = 1);

namespace Timeline\Event;

use Timeline\Date\DateTime;

interface EventInterface
{
    public function getStart(): DateTime;
    public function getEnd(): DateTime;
}
