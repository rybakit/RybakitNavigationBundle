<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Factory;

use Rybakit\Bundle\NavigationBundle\Navigation\Filter\FilterInterface;
use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;
use Rybakit\Bundle\NavigationBundle\Navigation\NavigationItem;

class ItemFactory implements FactoryInterface
{
    /**
     * @var FilterInterface
     */
    protected $filter;

    /**
     * @var ItemInterface
     */
    protected $itemPrototype;

    /**
     * @var array
     */
    protected $meta = array();

    /**
     * @param FilterInterface|null $filter
     * @param ItemInterface|null   $itemPrototype
     */
    public function __construct(FilterInterface $filter = null, ItemInterface $itemPrototype = null)
    {
        $this->filter = $filter;
        $this->itemPrototype = $itemPrototype ?: new NavigationItem();
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

        if ($children) {
            foreach ($children as $childOptions) {
                $this->create($childOptions, $item);
            }
        }

        if ($this->filter) {
            $options = $this->filter->filter($options);
        }

        foreach ($options as $key => $value) {
            $this->bindOption($item, $key, $value);
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
