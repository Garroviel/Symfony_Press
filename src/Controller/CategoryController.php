<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    #[Route('/category/{slug}', name: 'category_show', methods: ['GET'], requirements: ['slug' => '[a-z0-9-]+'])]
    public function show(string $slug, CategoryRepository $categoryRepository, ArticleRepository $articleRepository): Response
    {
        $category = $categoryRepository->findOneBy(['slug' => $slug]);
		$articles = $articleRepository->findBy(['category' => $category], ['createdAt' => 'DESC']);
		

        if (!$category) {
            throw $this->createNotFoundException('Catégorie introuvable.');
        }

        return $this->render('pages/category/show.html.twig', [
			'category' => $category,
			'articles' => $articles,
			
		]);
    }
}
