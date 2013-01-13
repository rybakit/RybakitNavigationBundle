<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation;

class Item extends AbstractItem
{
    /**
     * @param string|null $label
     * @param string|null $uri
     */
    public function __construct($label = null, $uri = null)
    {
        parent::__construct();

        $this->set('label', $label);
        $this->set('uri', $uri);
    }
}
