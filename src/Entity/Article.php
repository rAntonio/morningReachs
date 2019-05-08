<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
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
    private $titre;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="text")
     */
    private $contenu;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="text")
     */
    private $presentation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Auteur", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idAuteur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imggm;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tags", mappedBy="article", orphanRemoval=true)
     */
    private $tags;

    /**
     * @ORM\Column(type="smallint")
     */
    private $level=1;

    private $url="article";

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        if($titre == ""){
            throw new Exception("Titre requis");
        }
        $this->build();

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getCategory(): ?Categorie
    {
        return $this->category;
    }

    public function setCategory(?Categorie $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getIdAuteur(): ?Auteur
    {
        return $this->idAuteur;
    }

    public function setIdAuteur(?Auteur $idAuteur): self
    {
        $this->idAuteur = $idAuteur;

        return $this;
    }

    public function getImggm(): ?string
    {
        return $this->imggm;
    }

    public function setImggm(string $imggm): self
    {
        $this->imggm = $imggm;

        return $this;
    }

    /**
     * @return Collection|Tags[]
     */
    public function getTags(): Collection 
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->setArticle($this);
        }

        return $this;
    }

    public function removeTag(Tags $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            // set the owning side to null (unless already changed)
            if ($tag->getArticle() === $this) {
                $tag->setArticle(null);
            }
        }

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    private function SupprimeLesAccents($mot){
        return strtr( $mot, "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ", 
                            "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn" );
    }

    public function build()
    {
       //Désaccentue la chaîne passée en paramètre
       $titre= $this->SupprimeLesAccents (strtolower($this->getTitre()));
       //Remplace tous les caractères autres que alphanumérique par des tirets
       $titre= preg_replace('#[^a-z0-9_-]#','-',$titre); 
       while (strpos($titre,'--') !== false){
         $titre= str_replace('--','-',$titre); //Nettoyage des tirets superflus
       }
       $this->setUrl($titre);
    }

    public function getUrlsFormat()
    {
       //Désaccentue la chaîne passée en paramètre
       $titre= $this->SupprimeLesAccents (strtolower($this->getTitre()));
       //Remplace tous les caractères autres que alphanumérique par des tirets
       $titre= preg_replace('#[^a-z0-9_-]#','-',$titre); 
       while (strpos($titre,'--') !== false){
         $titre= str_replace('--','-',$titre); //Nettoyage des tirets superflus
       }
       return $titre;
    }
}
