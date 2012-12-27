<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation;

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
     * @param FilterInterface    $filter
     * @param ItemInterface|null $itemPrototype
     */
    public function __construct(FilterInterface $filter, ItemInterface $itemPrototype = null)
    {
        $this->filter = $filter;
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
                $item->add($this->create($childOptions));
            }
            unset($options['children']);
        }

        $this->filter->apply($options, $item);

        return $item;
    }

    /**
     * @return FilterInterface
     */
    public function getFilter()
    {
        return $this->filter;
    }
}
