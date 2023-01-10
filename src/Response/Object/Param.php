<?php

namespace App\Response\Object;

use ReturnTypeWillChange;

class Param implements \JsonSerializable
{
    private ?int $id;

    private ?string $name;

    private ?float $value;

    public function __construct(\App\Entity\Param $param)
    {
        $this->id = $param->getId();
        $this->name = $param->getName();
        $this->value = $param->getValue();
    }

    #[ReturnTypeWillChange] public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'value' => $this->value,
        ];
    }

}