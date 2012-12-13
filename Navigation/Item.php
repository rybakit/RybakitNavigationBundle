<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation;

class Item extends AbstractItem
{
    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $uri;

    /**
     * @var string
     */
    public $transDomain;

    /**
     * @var array
     */
    public $transParams = array();

    /**
     * @var bool
     */
    protected $isVisible = true;

    /**
     * @param string|null $label
     * @param string|null $uri
     */
    public function __construct($label = null, $uri = null)
    {
        parent::__construct();

        if (null !== $label) {
            $this->label = $label;
        }
        if (null !== $uri) {
            $this->uri = $uri;
        }
    }

    /**
     * @param bool $visible
     *
     * @return Item
     */
    public function setVisible($visible = true)
    {
        $this->isVisible = (bool) $visible;

        foreach ($this->getIterator() as $child) {
            if ($child instanceof self) {
                $child->setVisible($visible);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isVisible()
    {
        return $this->isVisible;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->label ?: spl_object_hash($this);
    }
}
