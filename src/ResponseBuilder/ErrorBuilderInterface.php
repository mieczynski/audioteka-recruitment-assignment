<?php

namespace App\ResponseBuilder;

interface ErrorBuilderInterface
{
    public function build(string $message): array;
}