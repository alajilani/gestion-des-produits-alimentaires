<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;





#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index', methods: ['GET','POST'])]
    public function index( Request $request , ArticleRepository $articleRepository ): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setAuthor($this->getUser());
            $image = $form->get('image')->getData();
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $article->setImage($fichier);

                $image1 = $form->get('imageMultiple')->getData();
    
                // On boucle sur les images
                foreach($image1 as $multiimage){
                    // On génère un nouveau nom de fichier
                    $fichier = md5(uniqid()).'.'.$multiimage->guessExtension();
                    
                    // On copie le fichier dans le dossier uploads
                    $multiimage->move(
                        $this->getParameter('images_directory'),
                        $fichier
                    );
                    $article->setImageMultiple($fichier);
                }    
                
            $articleRepository->save($article,true);
       
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request,ArticleRepository $articleRepository): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
         {

            
            $article->setAuthor($this->getUser());
            $articleRepository->save($article, true);
            
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/show/{id}', name: 'app_article_show', methods: ['GET','POST'])]
    public function show(Request $request, Article $article, ArticleRepository $articleRepository): Response
    
    {
        
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $article->setAuthor($this->getUser());
            $image = $form->get('image')->getData();
            if ($image) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $article->setImage($fichier);
            } else {
                // Si aucune nouvelle image n'est sélectionnée, conservez l'image existante
                $existingImage = $article->getImage();
                $article->setImage($existingImage);
            }


                $image1 = $form->get('imageMultiple')->getData();
    
                // On boucle sur les images
                foreach($image1 as $multiimage){
                    // On génère un nouveau nom de fichier
                    $fichier = md5(uniqid()).'.'.$multiimage->guessExtension();
                    
                    // On copie le fichier dans le dossier uploads
                    $multiimage->move(
                        $this->getParameter('images_directory'),
                        $fichier
                    );
                    $article->setImageMultiple($fichier);
                }    

            $articleRepository->save($article, true);
        
            return $this->redirectToRoute('app_article_index', ['id' => $article->getId()]);
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    

    
    

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET'])]

    public function edit(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setAuthor($this->getUser());
            $image = $form->get('image')->getData();
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $article->setImage($fichier);
                

             

            $articleRepository->save($article, true);
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
            
        ]);
    }

    #[Route('/delete/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
    
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $articleRepository->remove($article, true);
        }
       
        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/supprime/{id}', name: 'annonces_delete_image', methods: ['DELETE'])]
    public function deleteImage(Article $article, Request $request, ArticleRepository $articleRepository ){
        $imageMultiple = $article->getImageMultiple();
        $data = json_decode($request->getContent(), true);
    
        // On vérifie si le token est valide
        if($this->isCsrfTokenValid('delete'.$article->getImageMultiple(), $data['_token'])){
            // On récupère le nom de l'image
            $nom = $article->getName();
            // On supprime le fichier
            unlink($this->getParameter('images_directory').'/'.$nom);
    
            // On supprime l'entrée de la base
            $articleRepository->remove($article, true);
            // On répond en json
            return new JsonResponse(['success' => 1]);
        }else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }


    

    
}
