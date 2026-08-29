<?php

namespace OneToMany\AI\Tests\Resource\SearchStore;

use OneToMany\AI\Resource\SearchStore\Usage;
use PHPUnit\Framework\TestCase;

use function random_int;

final class UsageTest extends TestCase
{
    public function testConstructorCalculatesTotalWhenNull(): void
    {
        list($active, $pending, $failed) = [
            random_int(1, 1_000_000),
            random_int(1, 1_000_000),
            random_int(1, 1_000_000),
        ];

        $this->assertGreaterThan(0, $active);
        $this->assertGreaterThan(0, $pending);
        $this->assertGreaterThan(0, $failed);

        $total = $active + $pending + $failed;
        $this->assertGreaterThan(0, $total);

        $usage = new Usage($active, $pending, $failed);
        $this->assertSame($total, $usage->getTotal());
    }
}
