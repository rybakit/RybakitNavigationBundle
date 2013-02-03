<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Attribute;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;

class UpAttribute extends AbstractAttributeSetter
{
    /**
     * {@inheritdoc}
     */
    public function set(ItemInterface $item, $attrName)
    {
        $item->setAttribute($attrName, $this->value);

        if ($parent = $item->getParent()) {
            $this->set($parent, $attrName);
        }
    }
}
