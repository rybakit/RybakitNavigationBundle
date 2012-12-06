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
     * @var array
     */
    protected $classes = array();

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
            $normalizedOptionName = static::normalizeOptionName($option);

            $propertyName = lcfirst($normalizedOptionName);
            if ($this->isValidProperty($class, $propertyName)) {
                $this->cache[$class][$option]['property'] = $propertyName;
            } else {
                $methodName = 'set'.$normalizedOptionName;
                if ($this->isValidMethod($class, $methodName)) {
                    $this->cache[$class][$option]['method'] = $methodName;
                }
            }
        }

        if (isset($this->cache[$class][$option]['property'])) {
            $item->{$this->cache[$class][$option]['property']} = $value;
        } elseif (isset($this->cache[$class][$option]['method'])) {
            $item->{$this->cache[$class][$option]['method']}($value);
        }
    }

    /**
     * @param string $class
     * @param string $propertyName
     *
     * @return bool
     */
    protected function isValidProperty($class, $propertyName)
    {
        $reflClass = $this->getReflectionClass($class);

        return $reflClass->hasProperty($propertyName) && $reflClass->getProperty($propertyName)->isPublic();
    }

    /**
     * @param string $class
     * @param string $methodName
     *
     * @return bool
     */
    protected function isValidMethod($class, $methodName)
    {
        $reflClass = $this->getReflectionClass($class);

        if (!$reflClass->hasMethod($methodName)) {
            return false;
        }

        $method = $reflClass->getMethod($methodName);

        return $method->isPublic() &&
            $method->getNumberOfParameters() > 0 &&
            $method->getNumberOfRequiredParameters() <= 1;
    }

    /**
     * @param string $class
     *
     * @return \ReflectionClass
     */
    protected function getReflectionClass($class)
    {
        if (!isset($this->classes[$class])) {
            $this->classes[$class] = new \ReflectionClass($class);
        }

        return $this->classes[$class];
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
