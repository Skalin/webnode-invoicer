<?php

declare(strict_types=1);

namespace App\Shared\Symfony\Request\Query;

use Symfony\Component\HttpFoundation\Request;

readonly class QueryParamsProvider
{
    public function __construct(private Request $request)
    {
    }

    public function getCurrentPage(?string $entityName = null): int
    {
        return (int) $this->request->query->get($this->getQueryParamName($entityName, 'page'), 1);
    }

    public function getMaxPerPage(?string $entityName = null): int
    {
        return (int) $this->request->query->get($this->getQueryParamName($entityName, 'pageSize'), 10);
    }

    private function getQueryParamName(null|int|string ...$params): string
    {
        $params = array_filter($params, fn($param) => $param !== null);
        return implode('_', $params);
    }
}
