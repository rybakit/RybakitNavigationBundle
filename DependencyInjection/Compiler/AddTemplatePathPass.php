<?php

namespace Rybakit\Bundle\NavigationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class AddTemplatePathPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('twig.loader')) {
            return;
        }

        $class = new \ReflectionClass('\\Rybakit\\Bundle\\NavigationBundle\\RybakitNavigationBundle');
        $path = dirname($class->getFileName()).'/Resources/views';
        $container->getDefinition('twig.loader')->addMethodCall('addPath', array($path));
    }
}
