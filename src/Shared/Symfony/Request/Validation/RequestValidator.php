<?php

declare(strict_types=1);

namespace App\Shared\Symfony\Request\Validation;

use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestValidator
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    public function validate(object $request): void
    {
        $errors = $this->validator->validate($request);
        if (\count($errors) > 0) {
            throw new PreconditionFailedHttpException((string)$errors->get(0)->getMessage());
        }
    }
}
