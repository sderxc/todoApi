<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $response = $this->createApiResponse($exception);
        $event->setResponse($response);
    }

    /**
     * Creates the ApiResponse from any Exception
     *
     * @param \Exception $exception
     *
     * @return JsonResponse
     */
    private function createApiResponse(\Throwable $exception)
    {
        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        return new JsonResponse([
            'error' => $exception->getMessage(),
            'errorCode' => $statusCode
        ], $statusCode);
    }
}
