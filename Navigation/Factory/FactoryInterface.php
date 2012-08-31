<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Factory;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;

interface FactoryInterface
{
    /**
     * @param array              $options
     * @param ItemInterface|null $parent
     *
     * @return ItemInterface
     */
    public function create(array $options = array(), ItemInterface $parent = null);
}
