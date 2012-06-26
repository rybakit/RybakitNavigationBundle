<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation;

interface ItemInterface extends \IteratorAggregate
{
    /**
     * @param ItemInterface|null $parent
     *
     * @return ItemInterface
     *
     * @throws \InvalidArgumentException
     */
    function setParent(ItemInterface $parent = null);

    /**
     * @return ItemInterface|null
     */
    function getParent();

    /**
     * @param ItemInterface $item
     *
     * @return ItemInterface
     *
     * @throws \InvalidArgumentException
     */
    function add(ItemInterface $item);

    /**
     * @param ItemInterface $item
     *
     * @return ItemInterface
     */
    function remove(ItemInterface $item);

    /**
     * @param ItemInterface $item
     *
     * @return bool
     */
    function has(ItemInterface $item);
}