<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Filter;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;

class FilterChain implements FilterInterface
{
    /**
     * @var FilterInterface[]
     */
    protected $filters;

    /**
     * @param array $filters
     */
    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(array $options, ItemInterface $item)
    {
        foreach ($this->filters as $filter) {
            $filter->apply($options, $item);
        }
    }
}
