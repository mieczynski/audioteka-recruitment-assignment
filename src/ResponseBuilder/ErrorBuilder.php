<?php

namespace App\ResponseBuilder;

class ErrorBuilder implements ErrorBuilderInterface
{
    public function build(string $message): array
    {
        return [
            'error_message' => $message,
        ];
    }
}
