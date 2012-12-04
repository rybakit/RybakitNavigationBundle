<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Filter;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;

interface FilterInterface
{
    /**
     * @param array         $options
     * @param ItemInterface $item
     */
    public function apply(array $options, ItemInterface $item);
}
