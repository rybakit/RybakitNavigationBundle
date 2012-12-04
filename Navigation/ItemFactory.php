<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation;

use Rybakit\Bundle\NavigationBundle\Navigation\Filter\BindFilter;
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
     * {@inheritdoc}
     */
    public function create(array $options = array(), ItemInterface $parent = null)
    {
        $item = clone $this->itemPrototype;

        if ($parent) {
            $parent->add($item);
        }

        $children = empty($options['children']) ? array() : $options['children'];
        unset($options['children']);

        $this->filter->apply($options, $item);

        if ($children) {
            foreach ($children as $childOptions) {
                $this->create($childOptions, $item);
            }
        }

        return $item;
    }
}
