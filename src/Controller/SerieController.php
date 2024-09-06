<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/series', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'list')]
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

    #[Route('/details/{id}', name: 'details')]
    public function details(int $id, SerieRepository $serieRepository): Response
    {
        //todo: aller chercher les series en bdd
        $serie = $serieRepository->find($id);

        return $this->render('serie/details.html.twig', [
    "serie" => $serie
        ]);
    }
    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        dump($request);
        return $this->render('serie/create.html.twig', [

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

}
