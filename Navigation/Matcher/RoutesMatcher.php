<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Matcher;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;

class RoutesMatcher implements MatcherInterface
{
    /**
     * @var string The route name
     */
    private $name;

    /**
     * @var array The route parameters
     */
    private $parameters;

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
    public function match(ItemInterface $item)
    {
        if ($routes = $item->getAttribute('routes')) {
            foreach ($routes as $route) {
                if ($this->matchRoute($route)) {
                    return true;
                }
            }

            return false;
        }

        if ($route = $item->getAttribute('route')) {
            return $this->matchRoute($route);
        }

        return false;
    }

    /**
     * @param string|array $route
     *
     * @return bool
     */
    private function matchRoute($route)
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
