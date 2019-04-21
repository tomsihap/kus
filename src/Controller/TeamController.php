<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use App\Entity\Player;
use App\Form\PlayerType;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/team")
 */
class TeamController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/", name="team_index", methods={"GET"})
     */
    public function index(TeamRepository $teamRepository): Response
    {
        return $this->render('team/index.html.twig', [
            'teams' => $teamRepository->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="team_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($team);
            $entityManager->flush();

            return $this->redirectToRoute('team_index');
        }

        return $this->render('team/new.html.twig', [
            'team' => $team,
            'form' => $form->createView(),
        ]);
    }

    /**
     * 
     * @Route("/{id}", name="team_show", methods={"GET"})
     */
    public function show(Team $team): Response
    {
      
        return $this->render('team/show.html.twig', [
            'team' => $team,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="team_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Team $team): Response
    {
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

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


            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('team_index', [
                'id' => $team->getId(),
            ]);
        }

        return $this->render('team/edit.html.twig', [
            'team' => $team,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="team_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Team $team): Response
    {
        if ($this->isCsrfTokenValid('delete'.$team->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($team);
            $entityManager->flush();
        }

        return $this->redirectToRoute('team_index');
    }

    // /**
    //  * @Route("/{id}/test", name="team_test", methods={"GET", "POST"})
    //  */
    // public function count(Team $team)
    // {
        
    //     $tVictories = 0;
    //     $tScore = 0;
    //     foreach ($team->getPlayers() as $p) {
    //         $tVictories += $p->getVictories();
    //         $tScore += $p->getScore();
    //     }

    //     $team->setVictories($tVictories);
    //     $team->setScore($tScore);

    //     return $team;
    // }
    
}
