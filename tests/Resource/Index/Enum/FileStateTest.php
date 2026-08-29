<?php

namespace OneToMany\AI\Tests\Resource\Index\Enum;

use OneToMany\AI\Resource\Index\Enum\FileState;
use PHPUnit\Framework\TestCase;

final class FileStateTest extends TestCase
{
    public function testIsPending(): void
    {
        $this->assertTrue(FileState::Pending->isPending()); // @phpstan-ignore method.alreadyNarrowedType
    }

    public function testIsActive(): void
    {
        $this->assertTrue(FileState::Active->isActive()); // @phpstan-ignore method.alreadyNarrowedType
    }

    public function testIsFailed(): void
    {
        $this->assertTrue(FileState::Failed->isFailed()); // @phpstan-ignore method.alreadyNarrowedType
    }
}
