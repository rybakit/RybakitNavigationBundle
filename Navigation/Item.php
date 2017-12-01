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
    public $transParams = [];

    protected $isActive = false;
    protected $isVisible = true;

    public function __construct(string $label = null, string $uri = null)
    {
        parent::__construct();

        if (null !== $label) {
            $this->label = $label;
        }
        if (null !== $uri) {
            $this->uri = $uri;
        }
    }

    public function setActive(bool $active = true): self
    {
        $this->isActive = $active;

        if ($this->isActive && ($parent = $this->getParent()) instanceof self) {
            $parent->setActive();
        }

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setVisible(bool $visible = true): self
    {
        $this->isVisible = $visible;

        foreach ($this->getIterator() as $child) {
            if ($child instanceof self) {
                $child->setVisible($visible);
            }
        }

        return $this;
    }

    public function isVisible(): bool
    {
        return $this->isVisible;
    }

    public function __toString(): string
    {
        return $this->label ?: spl_object_hash($this);
    }
}
