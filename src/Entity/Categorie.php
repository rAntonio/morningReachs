<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategorieRepository")
 */
class Categorie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $catnom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="category")
     */
    private $articles;

    private $url="categorie";

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCatnom(): ?string
    {
        return $this->catnom;
    }

    public function setCatnom(string $catnom): self
    {
        $this->catnom = $catnom;
        $this->build();
        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setCategory($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getCategory() === $this) {
                $article->setCategory(null);
            }
        }

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
       $titre= $this->SupprimeLesAccents (strtolower($this->getCatnom()));
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
