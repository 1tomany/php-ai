<?php

namespace OneToMany\AI\Tests\Request\Query;

use OneToMany\AI\Request\Query\CompileRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('RequestTests')]
#[Group('QueryTests')]
final class CompileRequestTest extends TestCase
{
    public function testHasComponentsIsFalseWhenTheContentsAreEmpty(): void
    {
        $request = new CompileRequest();

        $this->assertFalse($request->hasComponents());
        $this->assertCount(0, $request->getComponents());
    }

    public function testHasComponentsIsTrueWhenTheContentsAreNotEmpty(): void
    {
        $request = new CompileRequest()->withText(...[
            'text' => 'When was PHP first released?',
        ]);

        $this->assertTrue($request->hasComponents());
        $this->assertCount(1, $request->getComponents());
    }
}
