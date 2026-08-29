<?php

namespace OneToMany\AI\Tests\Resource\Prompt;

use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Exception\RuntimeException;
use OneToMany\AI\Resource\Prompt\Response;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function json_validate;
use function uniqid;

use const PHP_INT_MAX;

#[Group('UnitTests')]
#[Group('ResourceTests')]
#[Group('PromptTests')]
final class ResponseTest extends TestCase
{
    public function testToArrayRequiresNonEmptyText(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessageIs('The response text is empty.');

        new Response(uniqid(), true, null, null, 'Failed to generate a response.')->toArray();
    }

    public function testToArrayRequiresJsonArray(): void
    {
        $text = (string) PHP_INT_MAX;
        $this->assertTrue(json_validate($text));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageIs('The decoded response text was expected to be an array.');

        new Response(uniqid(), true, $text)->toArray();
    }
}
