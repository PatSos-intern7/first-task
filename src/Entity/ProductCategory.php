<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductCategoryRepository")
 */
class ProductCategory implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $description;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="date")
     */
    private $dateOfCreation;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="date")
     */
    private $dateOfModification;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="category")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateOfCreation(): ?\DateTimeInterface
    {
        return $this->dateOfCreation;
    }

    public function setDateOfCreation(\DateTimeInterface $dateOfCreation): self
    {
        $this->dateOfCreation = $dateOfCreation;

        return $this;
    }

    public function getDateOfModification(): ?\DateTimeInterface
    {
        return $this->dateOfModification;
    }

    public function setDateOfModification(\DateTimeInterface $dateOfModification): self
    {
        $this->dateOfModification = $dateOfModification;

        return $this;
    }

    /**
     * @return Collection|Product[]
     * @Group("group1")
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }

    public function jsonSerialize()
    {
        $products = $this->getProducts()->getValues();
        $result = [];
        foreach ($products as $key => $product) {
            $result[$key]['id'] = $product->getId();
            $result[$key]['name'] = $product->getName();
        }

        return [
            'id'=>$this->getId(),
            'name'=>$this->getName(),
            'description'=>$this->getDescription(),
            'dateOfCreation'=>$this->getDateOfCreation(),
            'dateOfModification'=>$this->getDateOfModification(),
            'products'=>$result,
        ];

    }

    public function csvSerialize()
    {
        $products = $this->getProducts()->getValues();
        $result = [];
        foreach ($products as $key => $product) {
            $result[$key]= $product->getId();
        }

        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'dateOfCreation' => $this->getDateOfCreation(),
            'dateOfModification' => $this->getDateOfModification(),
            'products' => implode(",",$result),
        ];
    }
}
