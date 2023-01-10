<?php

namespace App\Entity;

use App\Repository\ExamRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExamRepository::class)]
class Exam
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createDt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'exam', targetEntity: Param::class)]
    private Collection $params;

    public function __construct()
    {
        $this->createDt = new DateTime();
        $this->params = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreateDt(): ?\DateTimeInterface
    {
        return $this->createDt;
    }

    public function setCreateDt(\DateTimeInterface $createDt): self
    {
        $this->createDt = $createDt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Param>
     */
    public function getParams(): Collection
    {
        return $this->params;
    }

    public function addParam(Param $param): self
    {
        if (!$this->params->contains($param)) {
            $this->params->add($param);
            $param->setExam($this);
        }

        return $this;
    }

    public function removeParam(Param $param): self
    {
        if ($this->params->removeElement($param)) {
            if ($param->getExam() === $this) {
                $param->setExam(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}
