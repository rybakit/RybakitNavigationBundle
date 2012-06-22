<?php

namespace Rybakit\Bundle\NavigationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Rybakit\Bundle\NavigationBundle\DependencyInjection\Compiler\AddTemplatePathPass;

class RybakitNavigationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AddTemplatePathPass());
    }
}
