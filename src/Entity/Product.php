<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $JIRA = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $JiraTask = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ClientJiraTask = null;

    #[ORM\Column(length: 255)]
    private ?string $Description = null;

    #[ORM\Column]
    private ?float $Price = null;

    #[ORM\Column]
    private ?int $Unit = null;

    #[ORM\Column]
    private ?float $Amount = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Invoices $Invoice = null;

    #[ORM\Column(nullable: true)]
    private ?float $TotalSum = null;

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

    public function getJIRA(): ?int
    {
        return $this->JIRA;
    }

    public function setJIRA(?int $JIRA): self
    {
        $this->JIRA = $JIRA;

        return $this;
    }

    public function getJiraTask(): ?string
    {
        return $this->JiraTask;
    }

    public function setJiraTask(?string $JiraTask): self
    {
        $this->JiraTask = $JiraTask;

        return $this;
    }

    public function getClientJiraTask(): ?string
    {
        return $this->ClientJiraTask;
    }

    public function setClientJiraTask(?string $ClientJiraTask): self
    {
        $this->ClientJiraTask = $ClientJiraTask;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->Price;
    }

    public function setPrice(float $Price): self
    {
        $this->Price = $Price;

        return $this;
    }

    public function getUnit(): ?int
    {
        return $this->Unit;
    }

    public function setUnit(int $Unit): self
    {
        $this->Unit = $Unit;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->Amount;
    }

    public function setAmount(float $Amount): self
    {
        $this->Amount = $Amount;

        return $this;
    }

    public function getInvoice(): ?Invoices
    {
        return $this->Invoice;
    }

    public function setInvoice(?Invoices $Invoice): self
    {
        $this->Invoice = $Invoice;

        return $this;
    }

    public function getTotalSum(): ?float
    {
        return $this->TotalSum;
    }

    public function setTotalSum(?float $TotalSum): self
    {
        $this->TotalSum = $TotalSum;

        return $this;
    }
}
