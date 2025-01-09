<?php

declare(strict_types=1);

namespace App\Order\Enum;

enum OrderState: string
{
    case DRAFT = 'draft';

    case PENDING = 'pending';

    case PAID = 'paid';

    case CANCELLED = 'cancelled';
}
