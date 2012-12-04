<?php

namespace Rybakit\Bundle\NavigationBundle\Tests\Navigation\Filter;

use Rybakit\Bundle\NavigationBundle\Navigation\Filter\ActiveFilter;

class ActiveFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider testApplyProvider
     */
    public function testApply($isMatched)
    {
        $item = $this->getMock('\\Rybakit\\Bundle\\NavigationBundle\\Navigation\\ItemInterface');

        $matcher = $this->getMock('\\Rybakit\\Bundle\\NavigationBundle\\Navigation\\Filter\\Matcher\\MatcherInterface');
        $matcher->expects($this->once())->method('match')
            ->will($this->returnValue($isMatched));

        $options = array();
        $filter = new ActiveFilter($matcher);
        $filter->apply($options, $item);

        $isMatched
            ? $this->assertEquals(array('active' => true), $options)
            : $this->assertEquals(array(), $options);
    }

    public function testApplyProvider()
    {
        return array(
            array(true),
            array(false),
        );
    }
}
