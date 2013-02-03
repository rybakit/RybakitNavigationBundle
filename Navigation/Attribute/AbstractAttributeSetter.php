<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Attribute;

abstract class AbstractAttributeSetter implements AttributeSetterInterface
{
    public $value;

    public function __construct($value)
    {
        $this->value = $value;
    }
}
