<?php

namespace App\Entity;

use App\Repository\NotesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotesRepository::class)]
class Notes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;


    #[ORM\Column(nullable: true)]
    private ?int $Percent = null;

    #[ORM\OneToOne(inversedBy: 'notes', cascade: ['persist', 'remove'])]
    private ?Invoices $invoice = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getInvoice(): ?Invoices
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoices $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function getPercent(): ?int
    {
        return $this->Percent;
    }

    public function setPercent(?int $Percent): self
    {
        $this->Percent = $Percent;

        return $this;
    }
}
