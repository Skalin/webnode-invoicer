<?php

declare(strict_types=1);

namespace App\Shared\Symfony\Formatter;

use Symfony\Component\Serializer\SerializerInterface;

class JsonResponseFormatter implements ResponseFormatter
{
    private const FORMAT = 'json';

    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function format(mixed $data, array $context = []): mixed
    {
        return $this->serializer->serialize($data, self::FORMAT, $context);
    }

    public function getResponseHeaders(): array
    {
        return [
            'Content-Type' => 'application/json'
        ];
    }
}
