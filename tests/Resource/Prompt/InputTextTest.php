<?php

namespace OneToMany\AI\Tests\Resource\Prompt;

use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Exception\RuntimeException;
use OneToMany\AI\Resource\Prompt\InputText;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ResourceTests')]
#[Group('PromptTests')]
final class InputTextTest extends TestCase
{
    public function testConstructorRequiresNonEmptyText(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessageIs('The input text cannot be empty.');

        new InputText('');
    }

    public function testToStringReturnsText(): void
    {
        $faker = \Faker\Factory::create();

        $text = $faker->sentence();
        $this->assertNotEmpty($text);

        $inputText = new InputText($text);
        $this->assertSame($text, $inputText->__toString());
    }

    public function testFromFileRequiresReadingFile(): void
    {
        $path = '/invalid/instructions.txt';
        $this->assertFileDoesNotExist($path);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageIs('Reading the input text file "'.$path.'" failed.');

        InputText::fromFile($path);
    }
}
