<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation;

interface ItemInterface
{
    const ATTR_BUBBLE  = -1;
    const ATTR_CASCADE = 1;

    /**
     * @return self|null
     */
    public function getParent();

    /**
     * @return self[]
     */
    public function getChildren();

    /**
     * @param string   $name
     * @param mixed    $value
     * @param int|null $mode
     */
    public function setAttribute($name, $value, $mode = null);

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getAttribute($name, $default = null);
}
