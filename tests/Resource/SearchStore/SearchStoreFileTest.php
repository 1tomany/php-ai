<?php

namespace OneToMany\AI\Tests\Resource\SearchStore;

use OneToMany\AI\Resource\Index\IndexFile;
use PHPUnit\Framework\TestCase;

use function random_int;

final class SearchStoreFileTest extends TestCase
{
    public function testGettingBytesIsNotNegative(): void
    {
        $bytes = -random_int(1, 100_000);
        $this->assertLessThan(0, $bytes);

        $this->assertSame(0, new IndexFile('store_123', bytes: $bytes)->getBytes()); // @phpstan-ignore argument.type
    }
}
