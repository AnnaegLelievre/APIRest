<?php

namespace App\Entity;

use App\Repository\TrajetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrajetRepository::class)
 */
class Trajet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Personne::class, inversedBy="trajets")
     */
    private $pers;

    /**
     * @ORM\ManyToOne(targetEntity=Ville::class, inversedBy="trajets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ville_dep;

    /**
     * @ORM\ManyToOne(targetEntity=Ville::class, inversedBy="trajets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ville_arr;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbKm;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DateTrajet;

    /**
     * @ORM\OneToMany(targetEntity=Inscription::class, mappedBy="trajet")
     */
    private $inscriptions;

    public function __construct()
    {
        $this->pers = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Personne[]
     */
    public function getPers(): Collection
    {
        return $this->pers;
    }

    public function addPer(Personne $per): self
    {
        if (!$this->pers->contains($per)) {
            $this->pers[] = $per;
        }

        return $this;
    }

    public function removePer(Personne $per): self
    {
        $this->pers->removeElement($per);

        return $this;
    }

    public function getVilleDep(): ?Ville
    {
        return $this->ville_dep;
    }

    public function setVilleDep(?Ville $ville_dep): self
    {
        $this->ville_dep = $ville_dep;

        return $this;
    }

    public function getVilleArr(): ?Ville
    {
        return $this->ville_arr;
    }

    public function setVilleArr(?Ville $ville_arr): self
    {
        $this->ville_arr = $ville_arr;

        return $this;
    }

    public function getNbKm(): ?int
    {
        return $this->nbKm;
    }

    public function setNbKm(int $nbKm): self
    {
        $this->nbKm = $nbKm;

        return $this;
    }

    public function getDateTrajet(): ?\DateTimeInterface
    {
        return $this->DateTrajet;
    }

    public function setDateTrajet(\DateTimeInterface $DateTrajet): self
    {
        $this->DateTrajet = $DateTrajet;

        return $this;
    }

    /**
     * @return Collection|Inscription[]
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
            $inscription->setTrajet($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getTrajet() === $this) {
                $inscription->setTrajet(null);
            }
        }

        return $this;
    }
}
