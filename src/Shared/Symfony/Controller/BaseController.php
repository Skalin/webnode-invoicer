<?php

declare(strict_types=1);

namespace App\Shared\Symfony\Controller;

use App\Shared\Symfony\Formatter\ResponseFormatter;
use App\Shared\Symfony\Request\Validation\BaseRequest;
use App\Shared\Symfony\Request\Validation\RequestValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class BaseController extends AbstractController
{
    public function __construct(private readonly SerializerInterface $serializer, private readonly RequestValidator $requestValidator, private readonly ResponseFormatter $responseFormatter)
    {
    }

    public function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }

    protected function createRequest(Request $request, string $type, bool $validate = true): BaseRequest
    {
        $request = $this->getSerializer()->deserialize($request->getContent(), $type, $request->getContentTypeFormat() ?? 'json');
        /** @var BaseRequest $request */
        if ($validate) {
            $this->requestValidator->validate($request);
        }
        return $request;
    }

    /**
     * @param array<mixed, mixed> $headers
     */
    public function sendResponse(mixed $data, int $status = Response::HTTP_OK, array $headers = []): Response
    {
        return new Response(
            content: $this->responseFormatter->format($data),
            status: $status,
            headers: \array_merge($headers, ['Content-Type' => 'application/json']),
        );
    }
}
