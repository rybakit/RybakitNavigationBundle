<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation;

use Rybakit\Bundle\NavigationBundle\Navigation\Filter\BindFilter;
use Rybakit\Bundle\NavigationBundle\Navigation\Filter\FilterChain;
use Rybakit\Bundle\NavigationBundle\Navigation\Filter\FilterInterface;

class ItemFactory
{
    /**
     * @var FilterInterface
     */
    protected $filter;

    /**
     * @var ItemInterface
     */
    protected $itemPrototype;

    /**
     * @param FilterInterface|null $filter
     * @param ItemInterface|null   $itemPrototype
     */
    public function __construct(FilterInterface $filter = null, ItemInterface $itemPrototype = null)
    {
        $this->filter = $filter ?: new BindFilter();
        $this->itemPrototype = $itemPrototype ?: new Item();
    }

    /**
     * Creates a tree using post-order traversal.
     *
     * @param array $options
     *
     * @return ItemInterface
     */
    public function create(array $options = array())
    {
        $item = clone $this->itemPrototype;

        if (!empty($options['children'])) {
            foreach ($options['children'] as $childOptions) {
                $this->create($childOptions)->setParent($item);
            }
            unset($options['children']);
        }

        $this->filter->apply($options, $item);

        return $item;
    }

    /**
     * @param FilterInterface $filter
     */
    public function setFilter(FilterInterface $filter)
    {
        $this->filter = $filter;
    }

    /**
     * @param FilterInterface $filter
     */
    public function addFilter(FilterInterface $filter)
    {
        if ($this->filter instanceof FilterChain) {
            $this->filter->addFilter($filter);
        } else {
            $this->filter = new FilterChain(array($this->filter, $filter));
        }
    }
}
