<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Factory;

use Rybakit\Bundle\NavigationBundle\Navigation\ContainerInterface;
use Rybakit\Bundle\NavigationBundle\Navigation\NavigationItem;

class Factory implements FactoryInterface
{
    /**
     * @var FactoryInterface
     */
    protected $parent;

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
    public function create(array $options = array(), ContainerInterface $parent = null)
    {
        $item = new NavigationItem();

        if ($parent) {
            $parent->add($item);
        }

        $this->bindOptions($item, $options);

        return $item;
    }

    /**
     * @param ContainerInterface $item
     * @param array              $options
     */
    protected function bindOptions(ContainerInterface $item, array $options)
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
     * @param ContainerInterface $item
     * @param string             $option
     * @param mixed              $value
     *
     * @throws \InvalidArgumentException
     */
    protected function bindOption(ContainerInterface $item, $option, $value)
    {
        if (!is_string($option) || empty($option)) {
            throw new \InvalidArgumentException('Option name must be a non-empty string.');
        }

        $normalizedName = static::normalizeOptionName($option);
        $method = 'set'.$normalizedName;

        if (method_exists($item, $method)) {
            $item->$method($value);
        } else {
            $item->{lcfirst($normalizedName)} = $value;
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
