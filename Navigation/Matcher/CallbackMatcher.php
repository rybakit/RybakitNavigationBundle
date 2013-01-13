<?php

namespace Rybakit\Bundle\NavigationBundle\Navigation\Matcher;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;

class CallbackMatcher implements MatcherInterface
{
    /**
     * @var \Closure|string|array A PHP callback
     */
    protected $callback;

    /**
     * @param \Closure|string|array $callback
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('The given callback is not a valid callable.');
        }

        $this->callback = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function match(ItemInterface $item)
    {
        return call_user_func($this->callback, $item);
    }
}
