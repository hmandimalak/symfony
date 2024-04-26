<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategoryRepository;


use App\Entity\Article; // Import the Article entity if not already imported
use App\Entity\Category; // Import the Category entity
use App\Form\CategoryType; // Import the CategoryType form

class IndexController extends AbstractController
{
    #[Route('/article/save')]
    public function save()
    {
        // Replace this code with database insertion using PDO
        $db = new \PDO('mysql:host=localhost;dbname=symfony', 'root', '');
        $stmt = $db->prepare("INSERT INTO article (nom, prix) VALUES (:nom, :prix)");
        $nom = $article->getNom();
        $prix = $article->getPrix();
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prix', $prix);

        $stmt->execute();

        return new Response('Article enregistré avec id ' . $db->lastInsertId());
    }

    #[Route('/', name: 'article_list')]
    public function home()
    {
        // Establish connection to the database
        $db = new \PDO('mysql:host=localhost;dbname=symfony', 'root', '');

        // Prepare and execute SQL query to fetch articles
        $stmt = $db->query("SELECT * FROM article");
        $articles = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $this->render('articles/index.html.twig', ['articles' => $articles]);
    }

    #[Route('/article/new', name: 'article_new')]
    #[Method('GET')]
    #[Method('POST')]
    public function new(Request $request)
    {
        $article = new Article();
        $form = $this->createFormBuilder($article)
            ->add('nom', TextType::class)
            ->add('prix', TextType::class)
            ->add('save', SubmitType::class, [
                'label' => 'Créer'
            ])
            
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            // Replace this code with database insertion using PDO
            $db = new \PDO('mysql:host=localhost;dbname=symfony', 'root', '');
            $stmt = $db->prepare("INSERT INTO article (nom, prix) VALUES (:nom, :prix)");
            $nom = $article->getNom();
            $prix = $article->getPrix();
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prix', $prix);

            $stmt->execute();

            return $this->redirectToRoute('article_list');
        }

        return $this->render('articles/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/article/{id}', name: 'article_show')]
    public function show($id)
    {
        // Establish connection to the database
        $db = new \PDO('mysql:host=localhost;dbname=symfony', 'root', '');

        // Prepare and execute SQL query to fetch the article by ID
        $stmt = $db->prepare("SELECT * FROM article WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $article = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Render the article details
        return $this->render('articles/show.html.twig', ['article' => $article]);
    }

    #[Route('/article/edit/{id}', name: 'edit_article')]
    #[Method('GET')]
    #[Method('POST')]
    public function edit(Request $request, $id)
    {
        // Establish connection to the database
        $db = new \PDO('mysql:host=localhost;dbname=symfony', 'root', '');

        // Fetch the article by ID
        $stmt = $db->prepare("SELECT * FROM article WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $articleData = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Create a form for editing the article
        $form = $this->createFormBuilder($articleData)
            ->add('nom', TextType::class)
            ->add('prix', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Modifier'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the updated data from the form
            $articleData = $form->getData();

            // Update the article in the database
            $stmt = $db->prepare("UPDATE article SET nom = :nom, prix = :prix WHERE id = :id");
            $stmt->execute([
                ':nom' => $articleData['nom'],
                ':prix' => $articleData['prix'],
                ':id' => $id
            ]);

            return $this->redirectToRoute('article_list');
        }

        return $this->render('articles/edit.html.twig', ['form' => $form->createView()]);
    }

    #[Route("/article/delete/{id}", name: "delete_article")]
    public function delete(Request $request, $id)
    {
        // Establish connection to the database
        $db = new \PDO('mysql:host=localhost;dbname=symfony', 'root', '');

        // Delete the article from the database
        $stmt = $db->prepare("DELETE FROM article WHERE id = :id");
        $stmt->execute([':id' => $id]);

        // Create a response to send back
        $response = new Response();
        $response->send();

        return $this->redirectToRoute('article_list');
    }

    #[Route("/category/newCat", name:"new_category")]
    #[Method('GET')]
    #[Method('POST')]
    public function newCategory(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get data from the form
            $category = $form->getData();

            // Insert category into the database
            $db = new \PDO('mysql:host=localhost;dbname=symfony', 'root', '');
            $stmt = $db->prepare("INSERT INTO category (titre, description) VALUES (:titre, :description)");
            $titre = $category->getTitre();
            $description = $category->getDescription();
            $stmt->bindParam(':titre', $titre);
            $stmt->bindParam(':description', $description);
            $stmt->execute();

            return $this->redirectToRoute('article_list');
        }

        return $this->render('articles/newCategory.html.twig', ['form' => $form->createView()]);
    }
}
