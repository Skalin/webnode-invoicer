<?php

declare(strict_types=1);

namespace App\Shared\Symfony\Formatter;

interface ResponseFormatter
{
    /**
     * @param array<string, mixed> $context Options normalizers/encoders have access to
     */
    public function format(mixed $data, array $context = []): mixed;
}
