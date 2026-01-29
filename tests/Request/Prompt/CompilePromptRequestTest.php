<?php

namespace OneToMany\AI\Tests\Request\Prompt;

use OneToMany\AI\Request\Prompt\CompilePromptRequest;
use OneToMany\AI\Request\Prompt\Content\InputText;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('RequestTests')]
#[Group('PromptTests')]
final class CompilePromptRequestTest extends TestCase
{
    public function testConstructingPromptWithNoContentsDoesNotThrowException(): void
    {
        $this->assertCount(0, new CompilePromptRequest('mock', 'mock', [])->getContents());
    }

    public function testHasContentsIsFalseWhenTheContentsAreEmpty(): void
    {
        $request = new CompilePromptRequest('mock', 'mock', []);

        $this->assertFalse($request->hasContents());
        $this->assertCount(0, $request->getContents());
    }

    public function testHasContentsIsTrueWhenTheContentsAreNotEmpty(): void
    {
        $request = new CompilePromptRequest('mock', 'mock', [
            new InputText('When was PHP first released?'),
        ]);

        $this->assertTrue($request->hasContents());
        $this->assertCount(1, $request->getContents());
    }
}
