<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Filter;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;

class BindFilter implements FilterInterface
{
    /**
     * @var \ReflectionClass[]
     */
    protected $classes = [];

    /**
     * @var (\Closure|bool)[]
     */
    protected $closures = [];

    /**
     * {@inheritdoc}
     */
    public function apply(array &$options, ItemInterface $item)
    {
        $class = get_class($item);

        foreach ($options as $option => $value) {
            if (!isset($this->closures[$class][$option])) {
                $this->closures[$class][$option] = $this->resolveOption($class, $option);
            }

            if (false !== $this->closures[$class][$option]) {
                $this->closures[$class][$option]($item, $value);
            }
        }
    }

    /**
     * @return \Closure|bool
     */
    protected function resolveOption(string $class, string $option)
    {
        $option = static::normalize($option);
        $reflClass = $this->getReflectionClass($class);

        $property = lcfirst($option);
        if ($this->isValidProperty($reflClass, $property)) {
            return function (ItemInterface $item, $value) use ($property) {
                $item->{$property} = $value;
            };
        }

        $method = 'set' . $option;
        if ($this->isValidMethod($reflClass, $method)) {
            return function (ItemInterface $item, $value) use ($method) {
                $item->{$method}($value);
            };
        }

        return false;
    }

    /**
     * @param \ReflectionClass $class
     * @param string $name
     *
     * @return bool
     */
    protected function isValidProperty(\ReflectionClass $class, $name)
    {
        return $class->hasProperty($name) && $class->getProperty($name)->isPublic();
    }

    /**
     * @param \ReflectionClass $class
     * @param string $name
     *
     * @return bool
     */
    protected function isValidMethod(\ReflectionClass $class, $name)
    {
        if (!$class->hasMethod($name)) {
            return false;
        }

        $method = $class->getMethod($name);

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
     * Converts 'option_name' to 'OptionName'.
     *
     * @param string $string
     *
     * @return string
     */
    protected static function normalize($string)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    }
}
