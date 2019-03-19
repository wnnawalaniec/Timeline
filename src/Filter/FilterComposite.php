<?php
declare(strict_types=1);

namespace Timeline\Filter;

use Timeline\Timeline;

class FilterComposite implements Filter
{
    /**
     * @var Filter[]
     */
    protected $filters;

    public function addFilter(Filter $filter) : FilterComposite
    {
        $this->filters[] = $filter;
        return $this;
    }

    public function filter(Timeline $timeline): Timeline
    {
        $t = clone $timeline;
        foreach ($this->filters as $filter) {
            $t = $filter->filter($t);
        }
        return $t;
    }
}
