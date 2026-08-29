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
            random_int(0, 1_000_000),
            random_int(0, 1_000_000),
            random_int(0, 1_000_000),
        ];

        $this->assertGreaterThanOrEqual(0, $active);
        $this->assertGreaterThanOrEqual(0, $pending);
        $this->assertGreaterThanOrEqual(0, $failed);

        $total = $active + $pending + $failed;
        $this->assertGreaterThanOrEqual(0, $total);

        $usage = new Usage($active, $pending, $failed);
        $this->assertSame($total, $usage->getTotal());
    }
}
