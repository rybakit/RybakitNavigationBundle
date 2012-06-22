<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation;

interface ContainerInterface extends \IteratorAggregate
{
    /**
     * @param ContainerInterface|null $parent
     *
     * @return ContainerInterface
     *
     * @throws \InvalidArgumentException
     */
    function setParent(ContainerInterface $parent = null);

    /**
     * @return ContainerInterface|null
     */
    function getParent();

    /**
     * @param ContainerInterface $item
     *
     * @return ContainerInterface
     *
     * @throws \InvalidArgumentException
     */
    function add(ContainerInterface $item);

    /**
     * @param ContainerInterface $item
     *
     * @return ContainerInterface
     */
    function remove(ContainerInterface $item);

    /**
     * @param ContainerInterface $item
     *
     * @return bool
     */
    function has(ContainerInterface $item);
}