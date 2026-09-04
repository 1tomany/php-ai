<?php

namespace OneToMany\AI\Tests\Resource\Shared;

use OneToMany\AI\Resource\Shared\Metadata;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ResourceTests')]
#[Group('SharedTests')]
final class MetadataTest extends TestCase
{
    public function testConstructorRequiresStringKey(): void
    {
        $this->assertTrue(new Metadata([0 => 'Vic', 1 => 42])->isEmpty());
    }

    public function testConstructorRequiresNonEmptyStringKey(): void
    {
        $this->assertTrue(new Metadata(['  ' => 'Vic'])->isEmpty());
    }

    public function testConstructorConvertsDateTimeInterfaceObjectsToIntegerTimestamps(): void
    {
        $datetime = new \DateTimeImmutable();

        $timestamp = $datetime->getTimestamp();
        $this->assertGreaterThan(0, $timestamp);

        $metadata = new Metadata([
            'datetime' => $datetime,
        ]);

        $this->assertSame(['datetime' => $timestamp], $metadata->toArray());
    }

    public function testConstructorConvertsStringableValuesToStrings(): void
    {
        $error = new \Exception('Server has crashed!');
        $this->assertInstanceOf(\Stringable::class, $error);

        $metadata = new Metadata([
            'error' => $error,
        ]);

        $this->assertSame(['error' => $error->__toString()], $metadata->toArray());
    }
}
