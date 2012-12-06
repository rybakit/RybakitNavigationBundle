<?php

namespace Rybakit\Bundle\NavigationBundle\Tests\Navigation\Filter;

use Rybakit\Bundle\NavigationBundle\Navigation\Filter\BindFilter;
use Rybakit\Bundle\NavigationBundle\Tests\Fixtures\Item;

class BindFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testApply()
    {
        $item = new Item();

        $originalOptions = $options = array(
            'public_property'                             => 'value1',
            'non_public_property_with_setter'             => 'value2',
            'non_public_property_with_setter_default_arg' => 'value3',
            'non_public_property_with_setter_many_args'   => '',
        );

        $filter = new BindFilter();
        $filter->apply($options, $item);

        $this->assertEquals('value1', $item->publicProperty);
        $this->assertEquals('value2', $item->nonPublicPropertyWithSetterValue);
        $this->assertEquals('value3', $item->nonPublicPropertyWithSetterDefaultArgValue);
        $this->assertEquals(false, $item->isNonPublicPropertyWithSetterManyArgsCalled);
        $this->assertEquals($originalOptions, $options);
    }
}
