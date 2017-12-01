<?php

namespace Rybakit\Bundle\NavigationBundle\Tests\Navigation\Iterator;

use PHPUnit\Framework\TestCase;
use Rybakit\Bundle\NavigationBundle\Navigation\Iterator\BreadcrumbIterator;
use Rybakit\Bundle\NavigationBundle\Tests\Fixtures\Item;

class BreadcrumbIteratorTest extends TestCase
{
    public function testIterator()
    {
        $root = new Item();
        $level1 = new Item();
        $level1->setParent($root);

        $iterator = new BreadcrumbIterator($level1);

        $this->assertSame(0, $iterator->key());
        $this->assertNull($iterator->rewind());

        $this->assertTrue($iterator->valid());
        $this->assertSame($level1, $iterator->current());
        $this->assertSame(0, $iterator->key());

        $this->assertNull($iterator->next());

        $this->assertTrue($iterator->valid());
        $this->assertSame($root, $iterator->current());
        $this->assertSame(1, $iterator->key());

        $iterator->next();

        $this->assertFalse($iterator->valid());
        $this->assertNull($iterator->current());
        $this->assertNull($iterator->key());
    }
}
