<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation;

use Rybakit\Bundle\NavigationBundle\Navigation\Filter\FilterInterface;

class ItemFactory
{
    private $filter;
    private $itemPrototype;

    public function __construct(FilterInterface $filter, ItemInterface $itemPrototype = null)
    {
        $this->filter = $filter;
        $this->itemPrototype = $itemPrototype ?: new Item();
    }

    /**
     * Creates a tree using post-order traversal.
     */
    public function create(array $options = []): ItemInterface
    {
        $item = clone $this->itemPrototype;

        if (!empty($options['children'])) {
            foreach ($options['children'] as $childOptions) {
                $item->add($this->create($childOptions));
            }
            unset($options['children']);
        }

        $this->filter->apply($options, $item);

        return $item;
    }

    public function getFilter(): FilterInterface
    {
        return $this->filter;
    }
}
