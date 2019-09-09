<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("group1")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
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
    private $dateOfLastModification;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ProductCategory", inversedBy="products")
     * @ORM\JoinColumn(name ="category_id", referencedColumnName="id", onDelete = "CASCADE", nullable=false)
     */
    private $category;

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

    public function setDescription(?string $description): self
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

    public function getDateOfLastModification(): ?\DateTimeInterface
    {
        return $this->dateOfLastModification;
    }

    public function setDateOfLastModification(\DateTimeInterface $dateOfLastModification): self
    {
        $this->dateOfLastModification = $dateOfLastModification;

        return $this;
    }

    public function getCategory(): ?ProductCategory
    {
        return $this->category;
    }

    public function setCategory(?ProductCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id'=>$this->getId(),
            'name'=>$this->getName(),
            'description'=>$this->getDescription(),
            'dateOfCreation'=>$this->getDateOfCreation(),
            'dateOfLastModification'=>$this->getDateOfLastModification(),
            'category'=>[
                'id'=>$this->getCategory()->getId(),
                'name'=>$this->getCategory()->getName(),
            ]
        ];
    }
}
