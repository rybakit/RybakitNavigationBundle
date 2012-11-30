<?php

namespace Rybakit\Bundle\NavigationBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Rybakit\Bundle\NavigationBundle\Navigation\Item;
use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;
use Rybakit\Bundle\NavigationBundle\Navigation\Iterator\BreadcrumbIterator;
use Rybakit\Bundle\NavigationBundle\Navigation\Iterator\RecursiveItemIterator;
use Rybakit\Bundle\NavigationBundle\Navigation\Iterator\RecursiveCallbackFilterIterator;

class NavigationExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     * @var \Twig_Template
     */
    protected $template;

    /**
     * @var ItemInterface[]
     */
    protected static $current = array();

    /**
     * @param ContainerInterface $container
     * @param \Twig_Template|string $template
     */
    public function __construct(ContainerInterface $container, $template)
    {
        $this->container = $container;
        $this->template = $template;
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'nav'             => new \Twig_Function_Method($this, 'render', array('is_safe' => array('html'))),
            'nav_breadcrumbs' => new \Twig_Function_Method($this, 'renderBreadcrumbs', array('is_safe' => array('html'))),
            'nav_current'     => new \Twig_Function_Method($this, 'getCurrent', array('is_safe' => array('html'))),
        );
    }

    /**
     * @param string $navName
     *
     * @return ItemInterface|null
     */
    public function getCurrent($navName)
    {
        if (!array_key_exists($navName, static::$current)) {
            static::$current[$navName] = $this->container->get($navName)->getCurrent();
        }

        return static::$current[$navName];
    }

    /**
     * Renders a navigation block.
     *
     * @param string $navName
     * @param array  $options
     *
     * @return string
     */
    public function render($navName, array $options = array())
    {
        if (!$root = $this->container->get($navName)) {
            return '';
        }

        $options += array('block' => 'nav');
        $items = $this->createNavigationIterator($root, $options);

        return $this->getTemplate()->renderBlock($options['block'], array(
            'items'   => $items,
            'options' => $options,
        ));
    }

    /**
     * Renders a breadcrumbs block.
     *
     * @param string $navName
     * @param mixed  $options
     *
     * @return string
     */
    public function renderBreadcrumbs($navName, $options = array())
    {
        if (!$current = $this->getCurrent($navName)) {
            return '';
        }

        if (!is_array($options)) {
            $options = array('last' => (string) $options);
        }

        $options += array('block' => 'breadcrumbs');
        $items = $this->createBreadcrumbIterator($current, $options);

        return $this->getTemplate()->renderBlock($options['block'], array(
            'items'   => $items,
            'options' => $options,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'rybakit_navigation';
    }

    protected function createNavigationIterator(ItemInterface $root, array $options = array())
    {
        $filter = null;

        if (isset($options['depth'])) {
            $depth  = (int) $options['depth'] + 1;
            $filter = function(Item $current) use ($depth) {
                return $current->getLevel() <= $depth;
            };
        }

        if (!empty($options['visible_only']) && $options['visible_only']) {
            $filter = function(Item $current) use ($filter) {
                return $filter($current) && $current->isVisible();
            };
        }

        $iterator = new RecursiveItemIterator($root);

        if ($filter) {
            $iterator = new RecursiveCallbackFilterIterator($iterator, function($current) use ($filter) {
                return $current instanceof Item && $filter($current);
            });
        }

        return $iterator;
    }

    protected function createBreadcrumbIterator(ItemInterface $current, array $options = array())
    {
        return new BreadcrumbIterator($current);
    }

    protected function getTemplate()
    {
        if (!$this->template instanceof \Twig_Template) {
            $this->template = $this->environment->loadTemplate($this->template);
        }

        return $this->template;
    }
}
