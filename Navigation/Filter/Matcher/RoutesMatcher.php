<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Filter\Matcher;

class RoutesMatcher implements MatcherInterface
{
    /**
     * @var string The route name
     */
    protected $name;

    /**
     * @var array The route parameters
     */
    protected $parameters;

    /**
     * @param string $name
     * @param array  $parameters
     */
    public function __construct($name, array $parameters)
    {
        $this->name = $name;
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function match($value)
    {
        if (isset($value['routes'])) {
            foreach ($value['routes'] as $route) {
                if ($this->matchRoute($route)) {
                    return true;
                }
            }

            return false;
        }

        if (isset($value['route'])) {
            return $this->matchRoute($value['route']);
        }

        return false;
    }

    /**
     * @param string|array $route
     *
     * @return bool
     */
    protected function matchRoute($route)
    {
        $route = (array) $route + array('', array());

        if ($route[0] !== $this->name) {
            return false;
        }

        foreach ($route[1] as $key => $val) {
            if (!isset($this->parameters[$key]) || $this->parameters[$key] != $val) {
                return false;
            }
        }

        return true;
    }
}
