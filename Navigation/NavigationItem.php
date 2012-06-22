<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation;

class NavigationItem extends AbstractContainer
{
    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $uri;

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
     */
    public function __construct($label = null)
    {
        parent::__construct();

        if ($label) {
            $this->setLabel($label);
        }
    }

    /**
     * @param string $label
     *
     * @return NavigationItem
     *
     * @throws \InvalidArgumentException
     */
    public function setLabel($label)
    {
        if (null !== $label && !is_string($label)) {
            throw new \InvalidArgumentException('Label must be a string or null.');
        }

        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $uri
     *
     * @return NavigationItem
     *
     * @throws \InvalidArgumentException
     */
    public function setUri($uri)
    {
        if (null !== $uri && !is_string($uri)) {
            throw new \InvalidArgumentException('Uri must be a string or null.');
        }

        $this->uri = $uri;

        return $this;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param bool $active
     *
     * @return NavigationItem
     */
    public function setActive($active = true)
    {
        $this->isActive = (bool) $active;

        if ($this->isActive) {
            if ($this->getParent() instanceof self) {
                $this->getParent()->setActive();
            }
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
     * @return NavigationItem|null
     */
    public function getCurrent()
    {
        if ($this->isActive()) {
            foreach ($this->getIterator() as $item) {
                if ($item instanceof self && $item->isActive()) {
                    return $item->getCurrent();
                }
            }

            return $this;
        }

        return null;
    }

    /**
     * @param bool $visible
     *
     * @return NavigationItem
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
        return $this->label;
    }
}
