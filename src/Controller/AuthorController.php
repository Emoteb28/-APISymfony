<?php

namespace App\Controller;

use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AuthorController
 * @package App\Controller
 */
class AuthorController extends AbstractController
{

    /**
     * @return Response
     * @Route("/author", name="author", methods={"GET"})
     */
    public function authorList()
    {
        $repository = $this->getDoctrine()->getRepository(Author::class);


/*        if ($repository === NULL) {
            return $this->json(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
        }*/

        $authorList = $repository->findAll();

        $responseList = array();

        foreach ($authorList as $author) {
            $responseList [] = array(
                'id' => $author->getId(),
                'Name' => $author->getName(),
                'lastname' => $author->getLastname(),
            );
        }

        $response = new Response();

        $response->setContent(json_encode(array("author"=>$responseList)));
        $response->headers->set("Content-Type", "application/json");
        $response->headers->set("Access-Control-Allow-Origin", "*");

        return $response;


/*         return $this->json([
            "author"=>$responseList,

        ]);*/
    }


    /**
     * @param $id
     * @return Response
     * @Route("/author/{id}", name="author.show", methods={"GET"})
     * Permet d'avoir les livres d'un Author grâce à son id
     */
    public function authorShow($id)
    {
        $repository = $this->getDoctrine()->getRepository(Author::class);
        $author = $repository->find($id);

        if ($author === NULL) {
            return $this->json([
                'message' => 'Author not found'
            ],Response::HTTP_NOT_FOUND);
        }

            $listBook = $author->getBooks();
            $Book = [];
            foreach ($listBook as $Book) {
                $Book[] = array(
                    "id"    => $Book->getId(),
                    "titre" => $Book->getTitle(),
                );
            }

        $response = new Response(json_encode(array(
                'id'     => $author->getId(),
                'name'    => $author->getName(),
                'lastname' => $author->getLastname(),
                'Book' => $Book,
            ))
        );
        $response->headers->set("Content-Type", "application/json");
        $response->headers->set("Access-Control-Allow-Origin", "*");
        return $response;
    }


    /**
     * Permet de supprimer un Author par son id
     * @Route("/author/{id}", name="suppression_Author", methods={"DELETE"})
     */
    public function deleteAuthor($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Author::class);
        $author     = $repository->find($id);
        $entityManager->remove($author);
        $entityManager->flush();
        $response = new Response(json_encode(array(
                'name'    => $author->getName(),
                'lastname' => $author->getLastname(),
            ))
        );
        $response->headers->set("Content-Type", "application/json");
        $response->headers->set("Access-Control-Allow-Origin", "*");
        return $response;
    }


    /**
     * Permet de modifier le name et/ou le lastname d'un Author par id.
     * La gestion des livres de l'Author se fera via l'entité livre
     * @Route("/Authors/modif/{id}/{name}/{lastname}", name="modification_Author", methods={"PUT"})
     */
    public function modifiyAuthor($id, $name, $lastname)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository    = $this->getDoctrine()->getRepository(Author::class);
        $author        = $repository->find($id);
        $author->setName($name);
        $author->setLastname($lastname);
        $entityManager->persist($author);
        $entityManager->flush();
        $response = new Response(json_encode(array(
                'id'     => $author->getId(),
                'name'    => $author->getName(),
                'lastname' => $author->getLastname(),
            ))
        );
        $response->headers->set("Content-Type", "application/json");
        $response->headers->set("Access-Control-Allow-Origin", "*");
        return $response;
    }


    /**
     * Permet de créer un Author
     * @Route("/Authors/new/{name}/{lastname}", name="nouveau_Author", methods={"POST"})
     */
    public function newAuthor($name, $lastname)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $author = new Author();
        $author->setName($name);
        $author->setLastname($lastname);
        $entityManager->persist($author);
        $entityManager->flush();

        $response = new Response(json_encode(array(
                'id'     => $author->getId(),
                'name'    => $author->getName(),
                'lastname' => $author->getLastname()
            )
        ));

        $response->headers->set("Content-Type", "application/json");
        $response->headers->set("Access-Control-Allow-Origin", "*");
        return $response;
    }
    
    
    
    
    
}
