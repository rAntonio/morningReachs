<?php 
    namespace App\Controller;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    use Twig\Environment;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController{  

    /* @Route("/{page}", name="index",defaults = {"page" = "1" },requirements = {"page" = "[1-999]+"}) */
        /**
         * @Route("/{page}", name="index",defaults = {"page" = "1" })
         */
        public function index(Environment $twig,ArticleRepository $arepo,CategorieRepository $crepo,$page){

            $twig->addExtension(new \nochso\HtmlCompressTwig\Extension(true));
            
            $articles=$arepo->findLast($page);
            $count=$arepo->count([])/9;

            $categories=$crepo->findAll();
            $content = $twig->render('Main/index.html.twig',['title' => "Blog de Tana","categories"=>$categories,"articles" => $articles,"current"=>$page,"count" => (int) $count]);

            return new Response($content);
        }

        /**
         * @Route("/pages/contact.html", name="contact")
         */
        public function contact(Environment $twig,CategorieRepository $crepo){
            $twig->addExtension(new \nochso\HtmlCompressTwig\Extension(true));

            $categories=$crepo->findAll();

            $content = $twig->render('Pages/contact.html.twig',['title' => "Blog de Tana","categories"=>$categories]);

            return new Response($content);
        }

        /**
         * @Route("/pages/about.html", name="about")
         */
        public function about(Environment $twig,CategorieRepository $crepo){
            $twig->addExtension(new \nochso\HtmlCompressTwig\Extension(true));

            $categories=$crepo->findAll();

            $content = $twig->render('Pages/about.html.twig',['title' => "Blog de Tana","categories"=>$categories]);

            return new Response($content);
        }

        /**
         * @Route("/categories/{name}", name="categories")
         */
        public function categories(Environment $twig,$name,CategorieRepository $crepo){
            $twig->addExtension(new \nochso\HtmlCompressTwig\Extension(true));
            
            $categories=$crepo->findAll();

            $ctg=$crepo->findOneByCatnom($name);

            $articles=$ctg->getArticles();

            $content = $twig->render('Pages/category.html.twig',['title' => $name,"categories"=>$categories,"articles"=>$articles]);

            return new Response($content);
        }

        /**
         * @Route("/article/{id}/{titre}.html", name="index_detail",requirements = {"id" = "\d+"})
         */
        public function article(Environment $twig,$titre,$id,CategorieRepository $crepo,ArticleRepository $arepo,Request $request){
            
            $twig->addExtension(new \nochso\HtmlCompressTwig\Extension(true));
            
            $categories=$crepo->findAll();

            $article=$arepo->findOneById($id);

            $content = $twig->render('Pages/article_detail.html.twig',['title' => $id,"categories"=>$categories,"article"=>$article]);

            return new Response($content);
        }

        /**
         * @Route("/search", name="search")
         */
        public function search(Environment $twig,Request $request,CategorieRepository $crepo){
            $twig->addExtension(new \nochso\HtmlCompressTwig\Extension(true));
            
            $search = $request->query->get('search','');

            $categories=$crepo->findAll();
            
            $content = $twig->render('Pages/result.html.twig',['title' => $search,"categories"=>$categories]);

            return new Response($content);
        }
    }
?>