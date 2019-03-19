<?php
declare(strict_types=1);

namespace Timeline\Filter;

use Timeline\Timeline;

interface Filter
{
    public function filter(Timeline $timeline): Timeline;
}
