<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="date")
     */
    private $yearOfBirth;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $yearOfDead;

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

    public function getYearOfBirth(): ?\DateTimeInterface
    {
        return $this->yearOfBirth;
    }

    public function setYearOfBirth(\DateTimeInterface $yearOfBirth): self
    {
        $this->yearOfBirth = $yearOfBirth;

        return $this;
    }

    public function getYearOfDead(): ?\DateTimeInterface
    {
        return $this->yearOfDead;
    }

    public function setYearOfDead(?\DateTimeInterface $yearOfDead): self
    {
        $this->yearOfDead = $yearOfDead;

        return $this;
    }
}
