<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use function mysql_xdevapi\getSession;

#[Route('/series', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'list',methods: ['GET'])]
    public function list(SerieRepository $serieRepository): Response
    {
        //todo: aller chercher les series en bdd
        //findAll récupère tout
        //$series = $serieRepository->findAll();

        //Permet de trier par popularity descendante, et si deux egal alors on trie par vote. On ne veut que 30 affichage !
        //$series = $serieRepository->findBy([],['popularity' => 'DESC', 'vote' => 'DESC'], limit: 30);

        //TEst appel avec DQL dans SerieRepository
        $series = $serieRepository->findBestSeries();

        //Affiche le tableau dans la page html
        //dd($series);
        return $this->render('serie/list.html.twig', [
            'series' => $series
        ]);
    }

    #[Route('/details/{id}', name: 'details',methods: ['GET'])]
    public function details(int $id, SerieRepository $serieRepository): Response
    {
        //todo: aller chercher les series en bdd
        $serie = $serieRepository->find($id);

        //Si il n'y a pas de series
        if(!$serie){
            throw $this->createNotFoundException('Serie not found');
        }

        return $this->render('serie/details.html.twig', [
    "serie" => $serie
        ]);
    }
    //GET ET POST
    #[Route('/create', name: 'create',methods: ['GET', 'POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response
    {
        //afficher formulaire
        $serie = new Serie();
        $serieForm = $this->createForm(SerieType::class, $serie);

        //traiter le formulaire
        $serieForm->handleRequest($request);

        //Si on envoi le formulaire et si il est valid
        if($serieForm->isSubmitted() && $serieForm->isValid()){
            //Date de creation ne peut etre nul donc crée la date d'aujourdhui
            $serie->setDateCreated(new \DateTime());
        $entityManager->persist($serie);
        $entityManager->flush();

        //Ajout un message
        $this->addFlash('success','Serie added successfully');
        //details à besoin d'un id donc lui retourner la valeur
            //Bonne pratique toujours faire une redirection sur le bouton envoyer, ca evite que
            // l'utilisateur avec F5 renvoie toujours le meme formulaire plusieurs fois
        return $this->redirectToRoute('serie_details', ['id' => $serie->getId()]);
        //Aller dans base.html.twig pour afficher ce message
        }

        return $this->render('serie/create.html.twig', [
        'serieForm' => $serieForm->createView()
        ]);
    }
    #[Route('/demo', name: 'em-demo')]
    public function demo(EntityManagerInterface $entityManager): Response
    {
        //Crée une instance de mon entité
        $serie = new Serie();

        //hydrate toutes les propriétés
        $serie->setName('pif');
        $serie->setBackdrop('dafsd');
        $serie->setPoster('dafsd');
        $serie->setDateCreated(new \DateTime());
        $serie->setFirstAirDate(new \DateTime("-1 year"));
        $serie->setLastAirDate(new \DateTime("- 6 months"));
        $serie->setGenres('drama');
        $serie->setOverview('bla bla bla');
        $serie->setPopularity(123.00);
        $serie->setVote(8.2);
        $serie->setStatus('cenceled');
        $serie->setTmdbId(329432);

        //affiche sur le site
        dump($serie);

        //Stocker de manière permanete la series dans la base de donnée
        $entityManager->persist($serie);
        //Ne pas oublié d'envoyer la donnée vers la base de donnée'
        $entityManager->flush();

        dump($serie);
        //Pour effacer notre donnée de la SBD
        $entityManager->remove($serie);
        $entityManager->flush();


        return $this->render('serie/create.html.twig', [

        ]);
    }
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Serie $serie,EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($serie);
        $entityManager->flush();

        return $this->redirectToRoute('main_home');
    }
}