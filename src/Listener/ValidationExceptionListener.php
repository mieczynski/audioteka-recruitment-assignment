<?php

namespace App\Listener;

use App\ResponseBuilder\ErrorBuilderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ValidationExceptionListener
{

    public function __construct(
        private readonly ErrorBuilderInterface $errorBuilder
    )
    {
    }

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

        if ($exception instanceof NotNormalizableValueException) {
            $event->setResponse(new JsonResponse([
                'error' => 'Invalid input type',
                'details' => $exception->getMessage(),
            ], JsonResponse::HTTP_BAD_REQUEST));
            return;
        }

        if ($exception instanceof HttpException) {
            $event->setResponse(new JsonResponse(
                $this->errorBuilder->build($exception->getMessage()),
                $exception->getStatusCode()
            ));
        }

    }
}
