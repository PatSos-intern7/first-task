<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuthorRepository")
 */
class Author
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
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $countryOfOrigin;

    /**
     * @ORM\Column(type="integer")
     */
    private $yearOfBirth;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $yearOfDead;

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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getCountryOfOrigin(): ?string
    {
        return $this->countryOfOrigin;
    }

    public function setCountryOfOrigin(string $countryOfOrigin): self
    {
        $this->countryOfOrigin = $countryOfOrigin;

        return $this;
    }

    public function getYearOfBirth(): ?int
    {
        return $this->yearOfBirth;
    }

    public function setYearOfBirth(?int $yearOfBirth): self
    {
        $this->yearOfBirth = $yearOfBirth;

        return $this;
    }

    public function getYearOfDead(): ?int
    {
        return $this->yearOfDead;
    }

    public function setYearOfDead(?int $yearOfDead): self
    {
        $this->yearOfDead = $yearOfDead;

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
}
