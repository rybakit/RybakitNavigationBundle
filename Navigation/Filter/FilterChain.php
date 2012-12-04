<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Filter;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;

class FilterChain implements FilterInterface
{
    /**
     * @var FilterInterface[]
     */
    protected $filters = array();

    /**
     * @param array $filters
     */
    public function __construct(array $filters)
    {
        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function apply(array &$options, ItemInterface $item)
    {
        foreach ($this->filters as $filter) {
            $filter->apply($options, $item);
        }
    }

    public function addFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;
    }
}
