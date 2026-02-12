<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/article', name: 'admin_article_')]
final class ArticleController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        $user = $this->getUser();
		if (!$user instanceof \App\Entity\User) {
			throw $this->createAccessDeniedException();
		}

		$articles = $articleRepository->findForAdminIndex(
			$user,
			$this->isGranted('ROLE_ADMIN')
		);

        return $this->render('pages/admin/article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

	#[Route('/new', name: 'new', methods: ['GET', 'POST'])]
	public function new (
        Request $request,
        EntityManagerInterface $em, // fetching object from the database
        ArticleRepository $articleRepository,
        SluggerInterface $slugger
    ): Response {
        $article = new Article();

		$user = $this->getUser();
		if (!$user instanceof \App\Entity\User) {
			throw $this->createAccessDeniedException();
		}
		$article->setUser($user);

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

		if ($form->isSubmitted()) {
			$title = (string) $form->get('title')->getData();

			if ($title !== '') {
				$slug = $this->generateUniqueSlug($title, $articleRepository, $slugger);
				$article->setSlug($slug);
			}
		}

        if ($form->isSubmitted() && $form->isValid()) {
            // Slug auto (unique)
            $slug = $this->generateUniqueSlug($article->getTitle(), $articleRepository, $slugger);
            $article->setSlug($slug);

			// persister les données
            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'Article créé avec succès.');
            return $this->redirectToRoute('admin_article_index');
        }

        $status = ($form->isSubmitted() && !$form->isValid())
			? Response::HTTP_UNPROCESSABLE_ENTITY
			: Response::HTTP_OK;

		return $this->render('pages/admin/article/new.html.twig', [
			'form' => $form->createView(),
		], new Response('', $status));
	}

	#[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' =>'\\d+'])]
	public function edit(
		Article $article,
		Request $request,
        EntityManagerInterface $em // fetching object from the database
		): Response {
			$this->denyIfNotOwner($article);
			$form = $this->createForm(ArticleType::class, $article);
			$form->handleRequest($request);

			// vérifie si form valid
			if ($form->isSubmitted() && $form->isValid()){

			// persister les données
            $em->persist($article);
            $em->flush();

			// message retour
            $this->addFlash('success', 'Article modifié avec succès.');
            return $this->redirectToRoute('admin_article_index');
        }
		$status = ($form->isSubmitted() && !$form->isValid())
			? Response::HTTP_UNPROCESSABLE_ENTITY
			: Response::HTTP_OK;

		return $this->render('pages/admin/article/edit.html.twig', [
			'article' => $article,
			'form' => $form->createView(),
		], new Response('', $status));
	}

	#[Route('/{id}', name: 'delete', methods: ['POST'], requirements: ['id' =>'\\d+'])]
	public function delete(
		Article $article,
		Request $request,
        EntityManagerInterface $em // fetching object from the database
		): Response {
			$this->denyIfNotOwner($article);
			$token = $request->request->get('_token');

			if ($this->isCsrfTokenValid('delete_article_' . $article->getId(), $token)) {
				$em->remove($article);
				$em->flush();
				$this->addFlash('success', 'Article supprimé.');
			} else {
				$this->addFlash('error', 'Token CSRF invalide.');
			}

		return $this->redirectToRoute('admin_article_index');
    }
	private function generateUniqueSlug(
        string $title,
        ArticleRepository $repo,
        SluggerInterface $slugger
    ): string {
        $base = $slugger->slug($title)->lower()->toString();
        $slug = $base;
        $i = 2;

        while ($repo->findOneBy(['slug' => $slug])) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }
	private function denyIfNotOwner(Article $article): void
	{
		// Admin peut tout faire (optionnel mais pratique)
		if ($this->isGranted('ROLE_ADMIN')) {
			return;
		}

		$user = $this->getUser();
		if (!$user instanceof User || $article->getUser()?->getId() !== $user->getId()) {
			throw $this->createAccessDeniedException("Vous ne pouvez pas modifier cet article.");
		}
	}
}
