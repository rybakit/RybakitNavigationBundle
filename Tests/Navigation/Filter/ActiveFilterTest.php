<?php

namespace Rybakit\Bundle\NavigationBundle\Tests\Navigation\Filter;

use Rybakit\Bundle\NavigationBundle\Navigation\Filter\ActiveFilter;

class ActiveFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testApplyWhenMatcherReturnsTrue()
    {
        $matcher = $this->getMock('Rybakit\Bundle\NavigationBundle\Navigation\Filter\Matcher\MatcherInterface');
        $matcher->expects($this->once())->method('match')
            ->will($this->returnValue(true));

        $filter = new ActiveFilter($matcher);

        $this->assertEquals(array('active' => true), $filter->apply(array()));
    }

    public function testApplyWhenMatcherReturnsFalse()
    {
        $matcher = $this->getMock('Rybakit\Bundle\NavigationBundle\Navigation\Filter\Matcher\MatcherInterface');
        $matcher->expects($this->once())->method('match')
            ->will($this->returnValue(false));

        $filter = new ActiveFilter($matcher);

        $this->assertEquals(array(), $filter->apply(array()));
    }
}
