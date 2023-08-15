<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleType;
use App\Form\CategoryType;
use Cocur\Slugify\Slugify;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBlogController extends AbstractController
{
    #[Route('/admin', name: 'admin_blog_home')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/blog/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/admin/category/add', name: 'admin_blog_category_add')]
    public function addCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('admin_blog_home');
        }

        return $this->render('admin/blog/add_category.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/article/add', name: 'admin_blog_article_add')]
    public function addArticle(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $slugify = new Slugify();

            $article->setCreatedAt(new \DateTime('now'))
                    ->setSlug($slugify->slugify($article->getTitle()));
            
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('admin_blog_home');
        }

        return $this->render('admin/blog/add_article.html.twig', [
            'form' => $form,
        ]);
    }
}
