<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation;

interface ItemInterface
{
    /**
     * @return self|null
     */
    public function getParent();

    /**
     * @return self[]
     */
    public function getChildren();

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function setAttribute($name, $value);

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getAttribute($name, $default = null);
}
