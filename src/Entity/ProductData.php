<?php

namespace App\Entity;

use App\Repository\ProductDataRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductDataRepository::class)]
#[ORM\Table(name: "tblProductData")]
class ProductData
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(name: "intProductDataId", type: "integer", options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\Column(name: "strProductName", type: "string", length: 50)]
    private ?string $ProductName = null;

    #[ORM\Column(name: "strProductDesc", type: "string", length: 255)]
    private ?string $ProductDesc = null;

    #[ORM\Column(name: "strProductCode", type: "string", length: 10)]
    private ?string $ProductCode = null;

    #[ORM\Column(name: "dtmAdded", type: "datetime", nullable: true)]
    private ?\DateTimeInterface $Added = null;

    #[ORM\Column(name: "dtmDiscontinued", type: "datetime", nullable: true)]
    private ?\DateTimeInterface $Discontinued = null;

    #[ORM\Column(nullable: true)]
    private ?int $stock = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $price = null;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->ProductName;
    }

    public function setProductName(string $ProductName): self
    {
        $this->ProductName = $ProductName;

        return $this;
    }

    public function getProductDesc(): ?string
    {
        return $this->ProductDesc;
    }

    public function setProductDesc(string $ProductDesc): self
    {
        $this->ProductDesc = $ProductDesc;

        return $this;
    }

    public function getProductCode(): ?string
    {
        return $this->ProductCode;
    }

    public function setProductCode(string $ProductCode): self
    {
        $this->ProductCode = $ProductCode;

        return $this;
    }

    public function getAdded(): ?\DateTimeInterface
    {
        return $this->Added;
    }

    public function setAdded(?\DateTimeInterface $Added): self
    {
        $this->Added = $Added;

        return $this;
    }

    public function getDiscontinued(): ?\DateTimeInterface
    {
        return $this->Discontinued;
    }

    public function setDiscontinued(?\DateTimeInterface $Discontinued): self
    {
        $this->Discontinued = $Discontinued;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): static
    {
        $this->price = $price;

        return $this;
    }

}
