<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InscriptionRepository::class)
 */
class Inscription
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Personne::class, inversedBy="inscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pers;

    /**
     * @ORM\ManyToOne(targetEntity=Trajet::class, inversedBy="inscriptions")
     */
    private $trajet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPers(): ?Personne
    {
        return $this->pers;
    }

    public function setPers(?Personne $pers): self
    {
        $this->pers = $pers;

        return $this;
    }

    public function getTrajet(): ?Trajet
    {
        return $this->trajet;
    }

    public function setTrajet(?Trajet $trajet): self
    {
        $this->trajet = $trajet;

        return $this;
    }
}
