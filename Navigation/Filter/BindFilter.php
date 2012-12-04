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
    public function apply(array &$options, ItemInterface $item)
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
            $normalizedOption = static::normalizeOptionName($option);
            $methodName = 'set'.$normalizedOption;

            $refl = new \ReflectionClass($class);
            if ($refl->hasMethod($methodName)) {
                $method = $refl->getMethod($methodName);
                if ($method->isPublic() && 1 === $method->getNumberOfRequiredParameters()) {
                    $this->cache[$class][$option]['method'] = $methodName;
                }
            } else {
                $propertyName = lcfirst($normalizedOption);
                if ($refl->hasProperty($propertyName) && $refl->getProperty($propertyName)->isPublic()) {
                    $this->cache[$class][$option]['property'] = $propertyName;
                }
            }
        }

        if (isset($this->cache[$class][$option]['method'])) {
            $item->{$this->cache[$class][$option]['method']}($value);
        } elseif (isset($this->cache[$class][$option]['property'])) {
            $item->{$this->cache[$class][$option]['property']} = $value;
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
