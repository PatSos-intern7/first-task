<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 */
class Book
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
    private $title;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $Publish;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(type="boolean")
     */
    private $availability;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BookGenre", inversedBy="books")
     * @ORM\JoinColumn(name ="genre_id", referencedColumnName="id", onDelete = "CASCADE", nullable=false)
     */
    private $genre;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="date")
     */
    private $entryDate;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="date")
     */
    private $lastModyfication;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Author", inversedBy="books")
     * @ORM\JoinColumn(name ="author_id", referencedColumnName="id", onDelete = "CASCADE", nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $CoverImage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getPublish(): ?int
    {
        return $this->Publish;
    }

    public function setPublish(int $Publish): self
    {
        $this->Publish = $Publish;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getAvailability(): ?bool
    {
        return $this->availability;
    }

    public function setAvailability(bool $availability): self
    {
        $this->availability = $availability;

        return $this;
    }

    public function getGenre(): ?BookGenre
    {
        return $this->genre;
    }

    public function setGenre(?BookGenre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getEntryDate(): ?\DateTimeInterface
    {
        return $this->entryDate;
    }

    public function setEntryDate(\DateTimeInterface $entryDate): self
    {
        $this->entryDate = $entryDate;

        return $this;
    }

    public function getLastModyfication(): ?\DateTimeInterface
    {
        return $this->lastModyfication;
    }

    public function setLastModyfication(\DateTimeInterface $lastModyfication): self
    {
        $this->lastModyfication = $lastModyfication;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->CoverImage;
    }

    public function setCoverImage(?string $CoverImage): self
    {
        $this->CoverImage = $CoverImage;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id'=>$this->getId(),
            'title'=>$this->getTitle(),
            'description'=>$this->getDescription(),
            'publish'=>$this->getPublish(),
            'country'=>$this->getCountry(),
            'availability'=>$this->getAvailability(),
            'genre'=>$this->getGenre(),
            'entryDate'=>$this->getEntryDate(),
            'lastModification'=>$this->getLastModyfication(),
            'author'=>$this->getAuthor(),
            'coverImage'=>$this->getCoverImage(),
        ];
    }
}