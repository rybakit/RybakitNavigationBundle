<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Factory;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;

interface FactoryInterface
{
    /**
     * @param FactoryInterface $parent
     *
     * @throws \InvalidArgumentException
     */
    function setParent(FactoryInterface $parent);

    /**
     * @param array              $options
     * @param ItemInterface|null $parent
     *
     * @return ItemInterface
     */
    function create(array $options = array(), ItemInterface $parent = null);
}
