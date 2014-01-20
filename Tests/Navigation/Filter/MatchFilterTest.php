<?php

namespace Rybakit\Bundle\NavigationBundle\Tests\Navigation\Filter;

use Rybakit\Bundle\NavigationBundle\Navigation\Filter\MatchFilter;

class MatchFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideApplyData
     */
    public function testApply($isMatched)
    {
        $matcher = $this->getMock('\\Rybakit\\Bundle\\NavigationBundle\\Navigation\\Filter\\Matcher\\MatcherInterface');
        $matcher->expects($this->once())->method('match')->will($this->returnValue($isMatched));

        $options = array();
        $item = $this->getMock('\\Rybakit\\Bundle\\NavigationBundle\\Navigation\\ItemInterface');

        $filter = new MatchFilter($matcher);
        $filter->apply($options, $item);

        $isMatched ? $this->assertEquals($item, $filter->getMatched()) : $this->assertNull($filter->getMatched());
    }

    public function provideApplyData()
    {
        return array(array(true), array(false));
    }
}
