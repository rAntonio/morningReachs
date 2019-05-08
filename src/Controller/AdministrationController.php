<?php 
    namespace App\Controller;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\Routing\Annotation\Route;
    use Twig\Environment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Proxies\__CG__\App\Entity\Article;
use App\Repository\AuteurRepository;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Entity\Auteur;

class AdministrationController extends AbstractController{

        /**
         * @Route("/setting/{page}", name="index_setting",defaults = {"page" = 1 },requirements = {"page" = "\d+"})
         */
        public function index(Environment $twig,SessionInterface $session,ArticleRepository $arepo,$page){
            $template = 'setting/pages/index.html.twig';

            //session verification
            $logs=$session->get('current');
            if(!isset($logs)){
                return new Response($twig->render('setting/pages/login.html.twig'));
            }
            
            $articles=$arepo->findPaginateByAuthor($page,$logs->getId());
            $count=$arepo->count([])/9;

            $content = $twig->render($template,["articles" => $articles,"current"=>$page,"count" => (int) $count]);

            return new Response($content);
        }

        /**
         * @Route("/setting/sign/{error}.html" , name="sign_setting",defaults = {"error" = "form" } )
         */
        public function sign(Environment $twig){
            $template = 'setting/pages/sign.html.twig';
            //$template = 'setting/index.html.twig';
            $content = $twig->render($template);

            return new Response($content);
        }

        /**
         * @Route("/setting/signup.jsp" , name="signup_setting" )
         */
        public function signup( AuteurRepository $aurepo,Request $request){
            try{
                if(!$request->request->has('mail') 
                || !$request->request->has('word')
                || !$request->request->has('nom') )
                    throw new Exception("Formulaire incomplet");

                $auteur = new Auteur();
                $auteur->setEmail($request->request->get('mail'))
                ->setPassword(md5($request->request->get('word')))
                ->setNom($request->request->get('nom'));

                $em=$this->getDoctrine()->getManager();
                $em->persist($auteur);

                $em->flush();
            }catch(Exception $e){
                return $this->redirectToRoute('sign_setting',['error' => $e->getMessage()]);
            }
                
            return $this->redirectToRoute('index_setting');
        }

        /**
         * @Route("/setting/Edit-{id}/{erreur}", name="setting_postupdate",defaults = {"erreur" = "" })
         */
        public function update(Environment $twig,$id,$erreur,SessionInterface $session,ArticleRepository $arepo,CategorieRepository $crepo){
            //session verification
            $logs=$session->get('current');
            if(!isset($logs)){
                return $this->redirectToRoute('index_setting');
            }
            $article=$arepo->findOneById($id);
            $categories=$crepo->findAll();

            $content = $twig->render('setting/pages/update.html.twig',["article" => $article,"categories" => $categories,"erreur"=> $erreur]);

            return new Response($content);
        }

        /**
         * @Route("/setting/save/{id}", name="setting_save_update")
        */
        public function save($id,Request $request,ArticleRepository $arepo,
        CategorieRepository $crepo,AuteurRepository $aurepo){
            try{
                $article = $arepo->findOneById($id);

                
                $categorie = $crepo->findOneById($request->request->get('categorie'));
                if(isset($categorie))
                    $article->setCategory($categorie);

                if(!$request->request->has('article-body'))
                    throw new Exception("Contenu de l'article vide");

                if(!$request->request->has('title'))
                    throw new Exception("Titre de l'article vide");

                if(!$request->request->has('date'))
                    throw new Exception("Date de creation invalide");

                if(!$request->request->has('description'))
                    throw new Exception("Desription de l'article vide");


                $article->setContenu($request->request->get('article-body'))
                ->setCreated(new \DateTime($request->request->get('date')))
                
                ->setTitre($request->request->get('title'))
                ->setImage('default.jpg')
                ->setImggm('default-big.jpg')
                ->setPresentation($request->request->get('description'));

                $manager=$this->getDoctrine()->getManager();
                $manager->flush();

            }catch(Exception $e){
                return $this->redirectToRoute('setting_postupdate',["id" => $id,"erreur"=> $e->getMessage()]);
            }

            return $this->redirectToRoute('setting_postupdate',["id" => $id,"erreur"=>""]);
        }

        /**
         * @Route("/setting/delete/{id}", name="setting_delete_update")
        */
        public function delete($id,ArticleRepository $arepo){
            try{
                $article = $arepo->findOneById($id);

                $article->setLevel(0);

                $manager=$this->getDoctrine()->getManager();
                $manager->flush();
            }catch(Exception $e){
                return $this->redirectToRoute('setting_postupdate',["id" => $id,"erreur"=> $e->getMessage()]);
            }

            return $this->redirectToRoute('setting_postupdate',["id" => $id,"erreur"=>""]);
        }

        /**
         * @Route("/setting/adding.html", name="setting_news")
         */
        public function news(Environment $twig,SessionInterface $session,CategorieRepository $crepo){
            //session verification
            $logs=$session->get('current');
            
            $categories=$crepo->findAll();

            if(!isset($logs)){
                return $this->redirectToRoute('index_setting');
            }

            $content = $twig->render('setting/pages/news.html.twig',["categories" => $categories]);

            return new Response($content);
        }

        /**
         * @Route("/setting/insert", name="setting_insert_news")
        */
        public function insert(Request $request,
        CategorieRepository $crepo,AuteurRepository $aurepo){
            if(!isset($logs)){
                return $this->redirectToRoute('index_setting');
            }

            try{
                $article = new Article();
                $article->setIdAuteur($aurepo->findOneById(1));

                
                $categorie = $crepo->findOneById($request->request->get('categorie'));
                if(isset($categorie))
                    $article->setCategory($categorie);

                if(!$request->request->has('article-body'))
                    throw new Exception("Contenu de l'article vide");

                if(!$request->request->has('title'))
                    throw new Exception("Titre de l'article vide");

                if(!$request->request->has('date'))
                    throw new Exception("Date de creation invalide");

                if(!$request->request->has('description'))
                    throw new Exception("Desription de l'article vide");


                $article->setContenu($request->request->get('article-body'))
                ->setCreated(new \DateTime($request->request->get('date')))
                
                ->setTitre($request->request->get('title'))
                ->setImage('default.jpg')
                ->setImggm('default-big.jpg')
                ->setPresentation($request->request->get('description'));

                $manager=$this->getDoctrine()->getManager();
                $manager->persist($article);
                $manager->flush();
            }catch(Exception $e){
                return $this->redirectToRoute('setting_news');
            }

            //var_dump($article);
            return $this->redirectToRoute('index_setting');
        }

        /**
         * @Route("/setting/logout", name="setting_logout")
         */
        public function logout(SessionInterface $session){
            $session->set('current',null);
            return $this->redirectToRoute('index_setting');
        }

        /**
         * @Route("/setting/login", name="setting_login")
         */
        public function login(SessionInterface $session,
        AuteurRepository $aurepo,Request $request){

            /*if($request->request->has('mail') && $request->request->has('word'))
                $auteur = $aurepo->findOneBy(array('email' => $request->request->get('mail')
                ,'password' => md5($request->request->get('word'))),array(),1,0);

            if(isset($auteur))
                $session->set('current',$auteur);*/
                
            //return new Response($request->request->get('mail')."".md5($request->request->get('word')));
            //return $this->redirectToRoute('index_setting');
            
                if($request->request->has('mail') && $request->request->has('word'))
                    $auteur = $aurepo->findOneByLog(
                        $request->request->get('mail'),$request->request->get('word')
                    );

                if(isset($auteur)){
                    $session->set('current',$auteur);
                    return $this->redirectToRoute('index_setting');
                }else{
                    return $this->redirectToRoute('index_setting',['error' => 'Email or password invalid' ]);
                }
            
        }
    }
?>