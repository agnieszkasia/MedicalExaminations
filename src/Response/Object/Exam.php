<?php

namespace App\Response\Object;

use ReturnTypeWillChange;

class Exam implements \JsonSerializable
{
    private ?int $id;

    private ?string $name;

    private ?\DateTimeInterface $createDt;

    private ?string $description;

    public function __construct(\App\Entity\Exam $exam)
    {
        $this->id = $exam->getId();
        $this->name = $exam->getName();
        $this->createDt = $exam->getCreateDt();
        $this->description = $exam->getDescription();
    }

    #[ReturnTypeWillChange] public function jsonSerialize(): array
    {
        $date = date_format($this->createDt, "d.m.Y H:i");

        return [
            'id' => $this->id,
            'name' => $this->name,
            'createDt' => $date,
            'description' => $this->description
        ];
    }

}