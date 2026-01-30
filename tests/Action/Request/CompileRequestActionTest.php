<?php

namespace OneToMany\AI\Tests\Action\Request;

use OneToMany\AI\Action\Request\CompileRequestAction;
use OneToMany\AI\Exception\InvalidArgumentException;
use OneToMany\AI\Factory\PromptClientFactory;
use OneToMany\AI\Request\Prompt\CompilePromptRequest;
use OneToMany\AI\Tests\Factory\ClientContainer;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ActionTests')]
#[Group('PromptTests')]
final class CompileRequestActionTest extends TestCase
{
    public function testCompilingRequestRequiresNonEmptyContents(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Compiling the prompt for the model "mock" failed because the contents are empty.');

        new CompileRequestAction(new PromptClientFactory(new ClientContainer()))->act(new CompilePromptRequest('mock', 'mock', []));
    }
}
