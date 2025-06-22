<?php

namespace App\Tests\Unit\ResponseBuilder;

use App\ResponseBuilder\ErrorBuilder;
use App\ResponseBuilder\ErrorBuilderInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\ResponseBuilder\ErrorBuilder
 */
class ErrorBuilderTest extends TestCase
{
    private readonly ErrorBuilderInterface $builder;

    public function setUp(): void
    {
        parent::setUp();

        $this->builder = new ErrorBuilder();
    }

    public function test_builds_error(): void
    {
        $this->assertEquals(['error_message' => 'Error message.'], $this->builder->build('Error message.'));
    }
}