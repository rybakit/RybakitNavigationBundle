<?php

namespace Rybakit\Bundle\NavigationBundle\Tests\Navigation\Iterator;

use Rybakit\Bundle\NavigationBundle\Navigation\Iterator\CustomFilterIterator;

class CustomFilterIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testAccept()
    {
        $iterator = new \RecursiveArrayIterator(array(1, array(21, 22), 3));

        $result = null;
        $iterator = new CustomFilterIterator($iterator, function($item) use (&$result) {
            $result = $item;
            return true;
        });

        foreach (new \RecursiveIteratorIterator($iterator) as $item) {
            $this->assertEquals($item, $result);
        }
    }
}
