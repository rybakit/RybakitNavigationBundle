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
     * @var bool
     */
    protected $isActive = false;

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

    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @param bool $active
     *
     * @return Item
     */
    public function setActive($active = true)
    {
        $this->isActive = (bool) $active;

        if ($this->isActive && $parent = $this->getParent() instanceof self) {
            $parent->setActive();
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $visible
     *
     * @return Item
     */
    public function setVisible($visible = true)
    {
        $this->isVisible = (bool) $visible;

        foreach ($this->getIterator() as $item) {
            if ($item instanceof self) {
                $item->setVisible($visible);
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
