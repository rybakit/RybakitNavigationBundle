<?php

namespace Rybakit\Bundle\NavigationBundle\Tests\Navigation\Factory;

use Rybakit\Bundle\NavigationBundle\Navigation\Filter\ActiveFilter;

class UrlFilterTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateUsingRouteName()
    {
        /*
        $generator = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $generator->expects($this->once())->method('generate')
            ->with($this->equalTo('route_name'))
            ->will($this->returnValue('generated_uri'));

        $factory = $this->getMock('Rybakit\Bundle\NavigationBundle\Navigation\Factory\FactoryInterface');
        $factory->expects($this->once())->method('create')
            ->with($this->equalTo(array(
                'route' => 'route_name',
                'uri'   => 'generated_uri',
            )));

        $routerAwareFactory = new RouterAwareFactory($generator, $factory);
        $routerAwareFactory->create(array('route' => 'route_name'));
        */
    }

    /*
    public function testCreateUsingRouteNameAndParameters()
    {
        $generator = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $generator->expects($this->once())->method('generate')
            ->with($this->equalTo('route_name'), $this->equalTo(array('param' => 'value')))
            ->will($this->returnValue('generated_uri_based_on_parameters'));

        $factory = $this->getMock('Rybakit\Bundle\NavigationBundle\Navigation\Factory\FactoryInterface');
        $factory->expects($this->once())->method('create')
            ->with($this->equalTo(array(
                'route' => array('route_name', array('param' => 'value')),
                'uri'   => 'generated_uri_based_on_parameters',
            )));

        $routerAwareFactory = new RouterAwareFactory($generator, $factory);
        $routerAwareFactory->create(array('route' => array('route_name', array('param' => 'value'))));
    }

    public function testCreateUsingRouteNameAndAbsoluteFlag()
    {
        $generator = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $generator->expects($this->once())->method('generate')
            ->with($this->equalTo('route_name'), $this->equalTo(array()), $this->equalTo(true))
            ->will($this->returnValue('generated_absolute_uri'));

        $factory = $this->getMock('Rybakit\Bundle\NavigationBundle\Navigation\Factory\FactoryInterface');
        $factory->expects($this->once())->method('create')
            ->with($this->equalTo(array(
                'route' => array('route_name', array(), true),
                'uri'   => 'generated_absolute_uri',
            )));

        $routerAwareFactory = new RouterAwareFactory($generator, $factory);
        $routerAwareFactory->create(array('route' => array('route_name', array(), true)));
    }
    */
}
