<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Auteur;
use App\Entity\Tags;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i=1,$j=1; $i < 4; $i++) { 
            $categorie = new Categorie();
            $categorie->setCatnom("Categorie $i");

            $auteur = new Auteur();
            $auteur->setNom("Auteur $i")
            ->setEmail("auteur.$i@gmail.com")
            ->setImage("auteurdefault.jpg")
            ->setPassword(md5("pass$i"));

            $manager->persist($auteur);
            $manager->persist($categorie);

            for (; $j < 10*$i; $j++) { 
                $article = new Article();
                $article->setTitre("Titre article $i")
                ->setContenu("
                
                <h1 style=\"text-align: center;\"><q><span style=\"font-family:comic sans ms,cursive\"><span style=\"font-size:36px\">Morning-reaches article - $i</span></span></q></h1>

                <p style=\"text-align: center;\"><span style=\"color:#A9A9A9\"> Article n°$i</span></p>

                <p>Ut tristique adipiscing mauris, sit amet suscipit metus porta quis. Donec dictum tincidunt erat, eu blandit ligula. Nam sit amet dolor sapien. Quisque velit erat, congue sed suscipit vel, feugiat sit amet enim. Suspendisse interdum enim at mi tempor commodo. Sed tincidunt sed tortor eu scelerisque. Donec luctus malesuada vulputate. Nunc vel auctor metus, vel adipiscing odio. Aliquam aliquet rhoncus libero, at varius nisi pulvinar nec. Aliquam erat volutpat.</p>

                <p>&nbsp;</p>

                <p>Donec ut neque mi. Praesent enim nisl, bibendum vitae ante et, placerat pharetra magna. Donec facilisis nisl turpis, eget facilisis turpis semper non. Maecenas luctus ligula tincidunt iasdsd vitae ante et, Interdum et malesuada fames ac ante ipsum primis in faucibus. Quisque sed consectetur erat. Maecenas in elementum libero. Sed consequat pellentesque ultricies. Ut laoreet vehicula nisl sed placerat. Duis posuere lectus n, eros et hendrerit pellentesque, ante magna condimentum sapien, eget ultrices eros libero non orci. Etiam varius diam lectus.</p>

                ")
                ->setImage("images/thumbs/masonry/lamp-400.jpg")
                ->setCreated(new \DateTime())
                ->setPresentation("Presentation article n°$j")
                ->setCategory($categorie)
                ->setIdAuteur($auteur)
                ->setImggm("bigdefault.jpg")
                ->setLevel(1);

                $tag = new Tags();
                $tag->setArticle($article)
                ->setMots("meta$i");

                $manager->persist($article);
                $manager->persist($tag);
            }
        }
        $manager->flush();
    }
}
