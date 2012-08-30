<?php

namespace Rybakit\Bundle\NavigationBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Rybakit\Bundle\NavigationBundle\Navigation\ItemInterface;
use Rybakit\Bundle\NavigationBundle\Navigation\NavigationItem;
use Rybakit\Bundle\NavigationBundle\Navigation\Iterator\RecursiveItemIterator;
use Rybakit\Bundle\NavigationBundle\Navigation\Iterator\RecursiveCallbackFilterIterator;
use Rybakit\Bundle\NavigationBundle\Navigation\Iterator\BreadcrumbIterator;

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
            'nav_current'     => new \Twig_Function_Method($this, 'getCurrent', array('is_safe' => array('html'))),
            'nav_menu'        => new \Twig_Function_Method($this, 'renderMenu', array('is_safe' => array('html'))),
            'nav_breadcrumbs' => new \Twig_Function_Method($this, 'renderBreadcrumbs', array('is_safe' => array('html'))),
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
     * Renders a menu.
     *
     * @param string $navName
     * @param array  $options
     *
     * @return string
     */
    public function renderMenu($navName, array $options = array())
    {
        if (!$item = $this->container->get($navName)) {
            return '';
        }

        $iterator = new RecursiveItemIterator($item);

        if (!empty($options['visible_only']) && $options['visible_only']) {
            $iterator = new RecursiveCallbackFilterIterator($iterator, function($current) {
                return !$current instanceof NavigationItem || $current->isVisible();
            });
        }

        $renderer = $this->createMenuRenderer($iterator, $this->template, $options);

        return $this->getTemplate()->renderBlock('menu', array(
            'items'   => $renderer->render(),
            'options' => $options,
        ));
    }

    /**
     * Renders a breadcrumbs.
     *
     * @param string $navName
     * @param mixed  $options
     *
     * @return string
     */
    public function renderBreadcrumbs($navName, $options = array())
    {
        if (!is_array($options)) {
            $options = array('last' => $options);
        }

        if (!$current = $this->getCurrent($navName)) {
            return '';
        }

        $items = $this->createBreadcrumbIterator($current);

        return $this->getTemplate()->renderBlock('breadcrumbs', array(
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

    protected function getTemplate()
    {
        if (!$this->template instanceof \Twig_Template) {
            $this->template = $this->environment->loadTemplate($this->template);
        }

        return $this->template;
    }

    protected function createMenuRenderer(\Traversable $iterator, \Twig_Template $template, array $options = array())
    {
        return new MenuRenderer($iterator, $template, $options);
    }

    protected function createBreadcrumbIterator(ItemInterface $current)
    {
        return new BreadcrumbIterator($current);
    }
}
