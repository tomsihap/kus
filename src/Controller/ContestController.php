<?php

namespace App\Controller;

use App\Entity\Contest;
use App\Entity\Player;
use App\Entity\Game;
use App\Form\ContestType;
use App\Repository\ContestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/contest")
 */
class ContestController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/", name="contest_index", methods={"GET"})
     */
    public function index(ContestRepository $contestRepository): Response
    {
        return $this->render('contest/index.html.twig', [
            'contests' => $contestRepository->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="contest_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $contest = new Contest();
        $form = $this->createForm(ContestType::class, $contest);
        $form->handleRequest($request);
 

        if ($form->isSubmitted() && $form->isValid()) {

            $playerEntity = $form->get('player')->getData();
            $gameEntity = $form->get('game')->getData();

            //Getting player(s)'s score and victories before the contest
            $initialPlayerScore = $playerEntity->getScore();
            $initialPlayerVictories = $playerEntity->getVictories();

            //Getting player's team score and victories before the contest
            $selectedTeam = $playerEntity->getTeam();
            $initalTeamScore = $selectedTeam->getScore();
            $initialTeamVictories = $selectedTeam->getVictories();

            //Getting the points value of the game played
            $selectedScore = $gameEntity->getVictoryValue();


            //Update player's score
            $updatedPlayerScore = $initialPlayerScore+$selectedScore;
            $playerEntity->setScore($updatedPlayerScore);

            //Update player's victories    
            $updatedPlayerVictories =  $initialPlayerVictories+1;
            $playerEntity->setVictories($updatedPlayerVictories);
            
            //Update player's team's score
            $updatedTeamScore = $initalTeamScore+$selectedScore;
            $selectedTeam->setScore($updatedTeamScore);

            //Update player's team's victories    
            $updatedTeamVictories =  $initialTeamVictories+1;  
            $selectedTeam->setVictories($updatedTeamVictories);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contest);
            $entityManager->flush();

            return $this->redirectToRoute('contest_index');
        }

        return $this->render('contest/new.html.twig', [
            'contest' => $contest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contest_show", methods={"GET"})
     */
    public function show(Contest $contest): Response
    {
        return $this->render('contest/show.html.twig', [
            'contest' => $contest,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="contest_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Contest $contest): Response
    {
        $form = $this->createForm(ContestType::class, $contest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tournament_index', [
                'id' => $contest->getId(),
            ]);
        }

        return $this->render('contest/edit.html.twig', [
            'contest' => $contest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contest_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Contest $contest): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contest->getId(), $request->request->get('_token'))) {

            $playerEntity = $contest->getPlayer();
            $loserEntity = $contest->getLoser();
            $gameEntity = $contest->getGame();

            //Getting player(s)'s score and victories before the contest
            $initialPlayerScore = $playerEntity->getScore();
            $initialPlayerVictories = $playerEntity->getVictories();

            //Getting loser(s)'s score and victories before the contest
            
            $initialLoserContestsPlayed = $loserEntity-> getContestPlayed();
            $initialLoserContestsLose   = $loserEntity->getLose();

           
            //Getting the points value of the game played
            $selectedScore = $gameEntity->getVictoryValue();


            //Update player's score
            $updatedPlayerScore = $initialPlayerScore - $selectedScore;
            $playerEntity->setScore($updatedPlayerScore);

            //Update player's victories    
            $updatedPlayerVictories =  $initialPlayerVictories - 1;
            $playerEntity->setVictories($updatedPlayerVictories);

            
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contest);
            $entityManager->flush();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tournament_index');
    }
}
