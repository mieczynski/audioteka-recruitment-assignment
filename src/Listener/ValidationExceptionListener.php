<?php

namespace App\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ValidationExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HandlerFailedException) {
            $exception = $exception->getPrevious();
        }

        if ($exception instanceof ValidationFailedException) {
            $violations = $exception->getViolations();

            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = [
                    'field' => $violation->getPropertyPath(),
                    'message' => $violation->getMessage(),
                ];
            }

            $response = new JsonResponse([
                'error' => 'Validation failed',
                'violations' => $errors,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

            $event->setResponse($response);
        }

        if ($exception instanceof \InvalidArgumentException) {
            $response = new JsonResponse([
                'error' => $exception->getMessage(),
            ], JsonResponse::HTTP_BAD_REQUEST);

            $event->setResponse($response);
        }

        if ($exception instanceof HttpExceptionInterface) {
            $event->setResponse(new JsonResponse([
                'error' => $exception->getMessage(),
            ], $exception->getStatusCode()));
        }
    }
}
