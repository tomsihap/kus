<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Entity\Team;
use App\Form\TournamentType;
use App\Form\TeamType;
use App\Repository\TournamentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @Route("/tournament")
 */
class TournamentController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/", name="tournament_index", methods={"GET"})
     */
    public function index(TournamentRepository $tournamentRepository): Response
    {

        $usr = $this->getUser();
    
        return $this->render('tournament/index.html.twig', [
            'tournaments' => $usr->getTournament(),
        ]);
    }

    /**
     * @Route("/new", name="tournament_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {

        
        $tournament = new Tournament();
        $form = $this->createForm(TournamentType::class, $tournament);

        //retieve the id of the current user
        $actualUser = $this->getUser();
        //set the organizer with the current user
        $tournament->setOrganizer($actualUser);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tournament);
            $entityManager->flush();

            return $this->redirectToRoute('tournament_index');
        }

        return $this->render('tournament/new.html.twig', [
            'tournament' => $tournament,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tournament_show", methods={"GET", "POST"})
     */
    public function show(Tournament $tournament, Request $request, ObjectManager $manager): Response
    {
        $usr = $this->getUser();
        $usrId = $this->getUser()->getId();
        $organizerId = $tournament->getOrganizer()->getId();

        if ($usrId == $organizerId){

            $team = new Team();
            $form = $this->createForm(TeamType::class, $team);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isvalid()) {

                $team->setTournament($tournament);

                $manager->persist($team);
                $manager->flush();

                return $this->redirectToRoute('tournament_show', [
                    'id' => $tournament->getId()
                ]);
            }

        return $this->render('tournament/show.html.twig', [
            'tournament' => $tournament,
            'teamForm'   => $form->createView()
        ]);
        }

        else {
            return $this->render('tournament/index.html.twig', [
                'tournaments' => $usr->getTournament(),
            ]);

        }
    }

    /**
     * @Route("/{id}/edit", name="tournament_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tournament $tournament): Response
    {
        $form = $this->createForm(TournamentType::class, $tournament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tournament_index', [
                'id' => $tournament->getId(),
            ]);
        }

        return $this->render('tournament/edit.html.twig', [
            'tournament' => $tournament,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tournament_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tournament $tournament): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tournament->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tournament);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tournament_index');
    }
}
