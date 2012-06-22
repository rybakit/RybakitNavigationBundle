<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Factory;

use Rybakit\Bundle\NavigationBundle\Navigation\ContainerInterface;

interface FactoryInterface
{
    /**
     * @param FactoryInterface $parent
     *
     * @throws \InvalidArgumentException
     */
    function setParent(FactoryInterface $parent);

    /**
     * @param array                   $options
     * @param ContainerInterface|null $parent
     *
     * @return ContainerInterface
     */
    function create(array $options = array(), ContainerInterface $parent = null);
}
