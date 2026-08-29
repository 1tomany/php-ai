<?php

namespace OneToMany\AI\Tests\Resource\Index;

use OneToMany\AI\Resource\Index\Index;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ResourceTests')]
#[Group('IndexTests')]
final class IndexTest extends TestCase
{
    public function testUsageIsEmptyByDefault(): void
    {
        $usage = new Index('store_123')->getUsage();

        $this->assertSame(0, $usage->getBytes());
        $this->assertSame(0, $usage->getActive());
        $this->assertSame(0, $usage->getPending());
        $this->assertSame(0, $usage->getFailed());
        $this->assertSame(0, $usage->getTotal());
    }
}
