<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Factory;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;
use Rybakit\Bundle\NavigationBundle\Navigation\NavigationItem;

class Factory implements FactoryInterface
{
    /**
     * @var ItemInterface
     */
    protected $itemPrototype;

    /**
     * @var FactoryInterface
     */
    protected $parent;

    /**
     * @var array
     */
    protected $meta = array();

    /**
     * @param ItemInterface|null $itemPrototype
     */
    public function __construct(ItemInterface $itemPrototype = null)
    {
        $this->itemPrototype = $itemPrototype ?: new NavigationItem();
    }

    /**
     * {@inheritdoc}
     */
    public function setParent(FactoryInterface $parent)
    {
        if ($parent === $this) {
            throw new \InvalidArgumentException('A factory cannot have itself as a parent.');
        }

        $this->parent = $parent;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = array(), ItemInterface $parent = null)
    {
        $item = clone $this->itemPrototype;

        if ($parent) {
            $parent->add($item);
        }

        $this->bindOptions($item, $options);

        return $item;
    }

    /**
     * @param ItemInterface $item
     * @param array         $options
     */
    protected function bindOptions(ItemInterface $item, array $options)
    {
        $children = empty($options['children']) ? array() : $options['children'];
        unset($options['children']);

        foreach ($options as $key => $value) {
            $this->bindOption($item, $key, $value);
        }

        if ($children) {
            $factory = $this->parent ?: $this;
            foreach ($children as $childOptions) {
                $factory->create($childOptions, $item);
            }
        }
    }

    /**
     * @param ItemInterface $item
     * @param string        $option
     * @param mixed         $value
     */
    protected function bindOption(ItemInterface $item, $option, $value)
    {
        if (!isset($this->meta[$option])) {
            $normalizedName = static::normalizeOptionName($option);
            $method = 'set'.$normalizedName;

            if (method_exists($item, $method)) {
                $this->meta[$option]['method'] = $method;
            } else {
                $this->meta[$option]['property'] = lcfirst($normalizedName);
            }
        }

        if (isset($this->meta[$option]['method'])) {
            $item->{$this->meta[$option]['method']}($value);
        } else {
            $item->{$this->meta[$option]['property']} = $value;
        }
    }

    /**
     * Normalizes an option name.
     *
     * @param string $option
     *
     * @return string
     */
    protected static function normalizeOptionName($option)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $option)));
    }
}
