<?php

namespace App\Entity;

use App\Repository\PersonneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonneRepository::class)
 */
class Personne
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $prenom;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateNaiss;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ville", cascade={"persist"})
     * @ORM\JoinColumn(name="ville_id", referencedColumnName="id")
     */
    private Ville $ville;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Voiture", cascade={"persist"})
     * @ORM\JoinColumn(name="voiture_id", referencedColumnName="id")
     */
    private Voiture $voiture;

    /**
     * @ORM\ManyToMany(targetEntity=Trajet::class, mappedBy="pers")
     */
    private $trajets;

    /**
     * @ORM\OneToOne(targetEntity=user::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false, name="user_id", referencedColumnName="id")
     */
    private $user;



    /**
     * @ORM\OneToMany(targetEntity=Inscription::class, mappedBy="pers", orphanRemoval=true)
     */
    private $inscriptions;

    public function __construct()
    {
        $this->trajets = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
    }

   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaiss(): ?\DateTimeInterface
    {
        return $this->dateNaiss;
    }

    public function setDateNaiss(?\DateTimeInterface $dateNaiss): self
    {
        $this->dateNaiss = $dateNaiss;

        return $this;
    }

    /**
     * @return Collection|Trajet[]
     */
    public function getTrajets(): Collection
    {
        return $this->trajets;
    }

    public function addTrajet(Trajet $trajet): self
    {
        if (!$this->trajets->contains($trajet)) {
            $this->trajets[] = $trajet;
            $trajet->addPer($this);
        }

        return $this;
    }

    public function removeTrajet(Trajet $trajet): self
    {
        if ($this->trajets->removeElement($trajet)) {
            $trajet->removePer($this);
        }

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

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
            $inscription->setPers($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getPers() === $this) {
                $inscription->setPers(null);
            }
        }

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getVoiture(): ?Voiture
    {
        return $this->voiture;
    }

    public function setVoiture(?Voiture $voiture): self
    {
        $this->voiture = $voiture;

        return $this;
    }   
}
