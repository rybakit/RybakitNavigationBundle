<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation;

use Rybakit\Bundle\NavigationBundle\Navigation\Filter\FilterInterface;

class Item implements ItemInterface
{
    protected $parent;
    protected $children = array();
    protected $attributes = array();

    public function __construct(array $options, FilterInterface $filter = null, self $parent = null)
    {
        if ($parent) {
            $this->parent = $parent;
        }

        $this->setOptions($options, $filter);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * {@inheritdoc}
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute($name, $default = null)
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        return $default;
    }

    protected function setOptions(array $options, FilterInterface $filter = null)
    {
        if (isset($options['children'])) {
            foreach ($options['children'] as $childOptions) {
                $this->children[] = new static($childOptions, $filter, $this);
            }
            unset($options['children']);
        }

        if ($filter) {
            $filter->apply($options, $this);
        }

        $this->attributes = $options + $this->attributes;
    }
}
