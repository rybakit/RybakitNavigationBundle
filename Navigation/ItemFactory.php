<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation;

use Rybakit\Bundle\NavigationBundle\Navigation\Filter\FilterInterface;

class ItemFactory
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
    protected $cache = array();

    /**
     * @param FilterInterface|null $filter
     * @param ItemInterface|null   $itemPrototype
     */
    public function __construct(FilterInterface $filter = null, ItemInterface $itemPrototype = null)
    {
        $this->filter = $filter;
        $this->itemPrototype = $itemPrototype ?: new Item();
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
            $options = $this->filter->apply($options);
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
        $class = get_class($item);

        if (!isset($this->cache[$class][$option])) {
            $method = 'set'.static::normalizeOptionName($option);
            $this->cache[$class][$option] = is_callable(array($item, $method)) ? $method : false;
        }

        if (false !== $this->cache[$class][$option]) {
            $item->{$this->cache[$class][$option]}($value);
        } else {
            $item->setAttribute($option, $value);
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
