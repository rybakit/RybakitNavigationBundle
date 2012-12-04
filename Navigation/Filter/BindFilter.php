<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Filter;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;

class BindFilter implements FilterInterface
{
    /**
     * @var array
     */
    protected $cache = array();

    /**
     * {@inheritdoc}
     */
    public function apply(array $options, ItemInterface $item)
    {
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
