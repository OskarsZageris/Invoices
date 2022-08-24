<?php

namespace App\Entity;

use App\Repository\InvoicesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoicesRepository::class)]
class Invoices
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Client = null;

    #[ORM\Column(length: 50)]
    private ?string $Type = null;

    #[ORM\Column(length: 255)]
    private ?string $Issuer = null;

    #[ORM\Column(length: 255)]
    private ?string $Owner = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $IssueDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DueDate = null;

    #[ORM\Column]
    private ?float $Amount = null;

    #[ORM\Column(nullable: true)]
    private ?float $Paid = null;

    #[ORM\Column(nullable: true)]
    private ?float $Unpaid = null;

    #[ORM\OneToMany(mappedBy: 'Invoice', targetEntity: Product::class, orphanRemoval: true)]
    private Collection $products;

    #[ORM\OneToOne(mappedBy: 'invoice', cascade: ['persist', 'remove'])]
    private ?Notes $notes = null;

//    #[ORM\OneToOne(mappedBy:Notes::class,cascade: ['all'], orphanRemoval: true)]
//private Notes $notes;



    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?string
    {
        return $this->Client;
    }

    public function setClient(string $Client): self
    {
        $this->Client = $Client;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getIssuer(): ?string
    {
        return $this->Issuer;
    }

    public function setIssuer(string $Issuer): self
    {
        $this->Issuer = $Issuer;

        return $this;
    }

    public function getOwner(): ?string
    {
        return $this->Owner;
    }

    public function setOwner(string $Owner): self
    {
        $this->Owner = $Owner;

        return $this;
    }

    public function getIssueDate(): ?\DateTimeInterface
    {
        return $this->IssueDate;
    }

    public function setIssueDate(\DateTimeInterface $IssueDate): self
    {
        $this->IssueDate = $IssueDate;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->DueDate;
    }

    public function setDueDate(\DateTimeInterface $DueDate): self
    {
        $this->DueDate = $DueDate;

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

    public function getPaid(): ?float
    {
        return $this->Paid;
    }

    public function setPaid(?float $Paid): self
    {
        $this->Paid = $Paid;

        return $this;
    }

    public function getUnpaid(): ?float
    {
        return $this->Unpaid;
    }

    public function setUnpaid(?float $Unpaid): self
    {
        $this->Unpaid = $Unpaid;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setInvoice($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getInvoice() === $this) {
                $product->setInvoice(null);
            }
        }

        return $this;
    }

    public function getNotes(): ?Notes
    {
        return $this->notes;
    }

    public function setNotes(?Notes $notes): self
    {
        // unset the owning side of the relation if necessary
        if ($notes === null && $this->notes !== null) {
            $this->notes->setInvoice(null);
        }

        // set the owning side of the relation if necessary
        if ($notes !== null && $notes->getInvoice() !== $this) {
            $notes->setInvoice($this);
        }

        $this->notes = $notes;

        return $this;
    }
}
