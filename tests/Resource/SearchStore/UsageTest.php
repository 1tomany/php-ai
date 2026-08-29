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

        $usage = new Usage(
            active: $active,
            pending: $pending,
            failed: $failed,
        );

        $this->assertSame($total, $usage->getTotal());
    }

    public function testGettingBytesIsNotNegative(): void
    {
        $bytes = -random_int(1, 100_000);
        $this->assertLessThan(0, $bytes);

        $this->assertSame(0, new Usage($bytes)->getBytes()); // @phpstan-ignore argument.type
    }
}
