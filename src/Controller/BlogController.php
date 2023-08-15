<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    public function getCategories(EntityManagerInterface $entityManager) : Response
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();

        return $this->render('templates/_header.html.twig', [
            'categories' => $categories,
        ]);
    }
    
    #[Route('/', name: 'blog_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        return $this->render('blog/index.html.twig');
    }

    #[Route('/{name}', name: 'blog_category')]
    public function showCategory(Category $category, ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findByCategory($category);
        
        return $this->render('blog/show_category.html.twig', [
            'category' => $category,
            'articles' => $articles,
        ]);
    }

    #[Route('/{name}/{slug}', name: 'blog_article')]
    public function showArticle(Article $article): Response
    {
        return $this->render('blog/show_article.html.twig', [
            'article' => $article,
        ]);
    }
}
