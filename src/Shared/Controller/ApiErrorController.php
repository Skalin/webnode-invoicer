<?php

declare(strict_types=1);

namespace App\Shared\Controller;

use App\Shared\Symfony\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class ApiErrorController extends BaseController
{
    public function show(\Throwable $exception, ?DebugLoggerInterface $logger): Response
    {
        return $this->sendResponse(
            [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]
        );
    }
}
