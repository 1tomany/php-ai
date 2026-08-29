<?php

namespace OneToMany\AI\Tests\Resource\File;

use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Resource\File\LocalFile;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function mime_content_type;

#[Group('UnitTests')]
#[Group('ResourceTests')]
#[Group('FileTests')]
final class LocalFileTest extends TestCase
{
    public function testConstructorRequiresNonEmptyPath(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessageIs('The file path cannot be empty.');

        new LocalFile('', 'text/plain');
    }

    public function testConstructorRequiresReadableFile(): void
    {
        $path = '/invalid/instructions.txt';
        $this->assertFileDoesNotExist($path);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessageIs('The file "'.$path.'" is not readable.');

        new LocalFile($path, 'text/plain');
    }

    public function testConstructorAttemptsToResolveTypeIfNotProvided(): void
    {
        $path = __FILE__;
        $this->assertFileExists($path);

        $type = @mime_content_type($path);
        $this->assertIsString($type);

        $this->assertSame($type, new LocalFile($path)->getType());
    }
}
