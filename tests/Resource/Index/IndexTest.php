<?php

namespace OneToMany\AI\Tests\Resource\Index;

use OneToMany\AI\Resource\Index\Index;
use PHPUnit\Framework\TestCase;

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
