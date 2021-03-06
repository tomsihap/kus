<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Entity\Team;
use App\Entity\Player;
use App\Entity\Game;
use App\Entity\Contest;
use App\Form\TournamentType;
use App\Form\TeamType;
use App\Form\PlayerType;
use App\Form\GameType;
use App\Form\ContestType;
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
     * @Route("/", name="tournament_index", methods={"GET", "POST"})
     */
    public function index(TournamentRepository $tournamentRepository, Request $request): Response
    {

        $usr = $this->getUser();
        $isUser = isset($usr);
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
        
    
        return $this->render('tournament/index.html.twig', [
            'tournaments' => $usr->getTournament(),
            'form' => $form->createView(),
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

        // get the actual user to make sure the user only see his tournaments

        $usr = $this->getUser();
        $usrId = $this->getUser()->getId();
        $organizerId = $tournament->getOrganizer()->getId();

       

        if ($usrId == $organizerId){

            //Add team's form    
            $team = new Team();
            $teamForm = $this->createForm(TeamType::class, $team);
            $teamForm->handleRequest($request);


            if ($teamForm->isSubmitted() && $teamForm->isvalid()) {

                /**********************************************************************/

                //retrive the file send in the request
                $file = $request->files->get('team')['photo'];

                //put the path to the folder that will stock our files in a var
                $uploads_team_directory = $this->getParameter('uploads_team_directory');

                //create a var to change the name of the file
                $filename = 'team' . md5(uniqid()) . '.' . $file->guessExtension();

                //move the file into the folder
                $file->move(
                    $uploads_team_directory,
                    $filename
                );

                //set the event photo's attribut
                $team->setPhoto($filename);

                /**********************************************************************/     

                $team->setTournament($tournament);

                $manager->persist($team);
                $manager->flush();

                return $this->redirectToRoute('tournament_show', [
                    'id' => $tournament->getId()
                ]);
            }

            //Add player's form 
            $player = new Player();
            $playerForm = $this->createForm(PlayerType::class, $player);
            $playerForm->handleRequest($request);

            

            if($playerForm->isSubmitted() && $playerForm->isValid()) {

                /**********************************************************************/

                

                //retrive the file send in the request
                $file = $request->files->get('player')['profilPic'];

                if ($file === null) {
                    $player->setProfilPic(null);
                } 

                else {

                    //put the path to the folder that will stock our files in a var
                    $uploads_player_directory = $this->getParameter('uploads_player_directory');

                    //create a var to change the name of the file
                    $filename = 'player' . md5(uniqid()) . '.' . $file->guessExtension();

                    //move the file into the folder
                    $file->move(
                        $uploads_player_directory,
                        $filename
                    );

                    //set the event photo's attribut
                    $player->setProfilPic($filename);

                }

                

                /**********************************************************************/ 

                

                $manager->persist($player);
                $manager->flush();

                return $this->redirectToRoute('tournament_show', [
                    'id' => $tournament->getId()
                ]);
            }

            //Add game's form 
            $game = new Game();
            $gameForm = $this->createForm(GameType::class, $game);
            $gameForm->handleRequest($request);

            if ($gameForm->isSubmitted() && $gameForm->isValid()) {

                /**********************************************************************/

                //retrive the file send in the request
                $file = $request->files->get('game')['photo'];

                //put the path to the folder that will stock our files in a var
                $uploads_game_directory = $this->getParameter('uploads_game_directory');

                //create a var to change the name of the file
                $filename = 'game' . md5(uniqid()) . '.' . $file->guessExtension();

                //move the file into the folder
                $file->move(
                    $uploads_game_directory,
                    $filename
                );

                //set the event photo's attribut
                $game->setPhoto($filename);

                /**********************************************************************/   

                $game->setTournament($tournament);

                $manager->persist($game);
                $manager->flush();

                return $this->redirectToRoute('tournament_show', [
                    'id' => $tournament->getId()
                ]);
            }

            //Add contest's form 
            $contest = new Contest();
            $contestForm = $this->createForm(ContestType::class, $contest);
            $contestForm->handleRequest($request);

            if ($contestForm->isSubmitted() && $contestForm->isValid()) {

                $contest->setTournament($tournament);
        
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($contest);
                $entityManager->flush();

                $manager->persist($contest);
                $manager->flush();

                return $this->redirectToRoute('tournament_show', [
                    'id' => $tournament->getId()
                ]);
            }


        return $this->render('tournament/show.html.twig', [
            'tournament'   => $tournament,
            'teamForm'     => $teamForm->createView(),
            'playerForm'   => $playerForm->createView(),
            'gameForm'     => $gameForm->createView(),
            'contestForm'  => $contestForm->createView()
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
