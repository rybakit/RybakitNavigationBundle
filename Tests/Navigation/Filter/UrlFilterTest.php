<?php

namespace Rybakit\Bundle\NavigationBundle\Tests\Navigation\Filter;

use Rybakit\Bundle\NavigationBundle\Navigation\Filter\UrlFilter;

class UrlFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testApplyForRouteName()
    {
        $urlGenerator = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $urlGenerator->expects($this->once())->method('generate')
            ->with($this->equalTo('my_route'))
            ->will($this->returnValue('generated_url'));

        $filter = new UrlFilter($urlGenerator);
        $result = $filter->apply(array('route' => 'my_route'));

        $this->assertEquals(array('route' => 'my_route', 'uri' => 'generated_url'), $result);
    }

    public function testApplyForRouteNameAndParameters()
    {
        $urlGenerator = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $urlGenerator->expects($this->once())->method('generate')
            ->with($this->equalTo('my_route'), $this->equalTo(array('param' => 'value')))
            ->will($this->returnValue('generated_url'));

        $filter = new UrlFilter($urlGenerator);
        $result = $filter->apply(array('route' => array('my_route', array('param' => 'value'))));

        $this->assertEquals(array(
            'route' => array('my_route', array('param' => 'value')),
            'uri'   => 'generated_url',
        ), $result);
    }

    public function testApplyForRouteNameAndAbsoluteFlag()
    {
        $urlGenerator = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $urlGenerator->expects($this->once())->method('generate')
            ->with($this->equalTo('my_route'), $this->equalTo(array()), $this->equalTo(true))
            ->will($this->returnValue('generated_url'));

        $filter = new UrlFilter($urlGenerator);
        $result = $filter->apply(array('route' => array('my_route', array(), true)));

        $this->assertEquals(array(
            'route' => array('my_route', array(), true),
            'uri'   => 'generated_url',
        ), $result);
    }
}
