<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Filter;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;

class BindFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(array &$options, ItemInterface $item)
    {
        foreach ($options as $option => $value) {
            $item->set($option, $value);
        }
    }
}
