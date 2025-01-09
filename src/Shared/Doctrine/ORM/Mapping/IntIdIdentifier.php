<?php

namespace App\Shared\Doctrine\ORM\Mapping;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

trait IntIdIdentifier
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: 'integer')]
    private int $id;

    public function getId(): int
    {
        return $this->id;
    }
}
