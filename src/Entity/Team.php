<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 */
class Team
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

   
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Player", mappedBy="team", orphanRemoval=true)
     */
    private $players;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tournament", inversedBy="teams")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournament;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @return Collection|Player[]
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->setTeam($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
            // set the owning side to null (unless already changed)
            if ($player->getTeam() === $this) {
                $player->setTeam(null);
            }
        }

        return $this;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): self
    {
        $this->tournament = $tournament;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getScore(): ?int {
        $tScore = 0;
        foreach ($this->getPlayers() as $p) {
            $tScore += $p->getScore();
        }
        return $tScore;
    }

    public function getVictories(): ?int
    {
        $tVictories = 0;
        foreach ($this->getPlayers() as $p) {
            $tVictories += $p->getVictories();
        }
        return $tVictories;
    }

    public function getLose(): ?int
    {
        $tLoses = 0;
        foreach ($this->getPlayers() as $p) {
            $tLoses += $p->getLose();
        }
        return $tLoses;
    }

    public function getPlayed(): ?int
    {
        $tPlayed = 0;
        foreach ($this->getPlayers() as $p) {
            $tPlayed += $p->getContestPlayed();
        }
        return $tPlayed;
    }

    public function getPercentVictories()
    {

        if($this->getPlayed() > 0) {

            $teamPercentVictories = $this->getVictories() * 100 / $this->getPlayed();
            return round($teamPercentVictories, 2) . "%";
        } else {
            return "/";
        }
        
    }

    public function allExceptThis(Team $team) {
        
        $teams = $team->getTournament()->getTeams();
        $availablePlayers = [];

        foreach ($teams as $t) {
            if ($t != $team) {

                foreach ($t->getPlayers() as $p) {
                    array_push($availablePlayers, $p);
                }
            }
        }
         return $availablePlayers;
    }

    public function thisTournamentTeam(Team $team)
    {
        $teams = $team->getTournament()->getTeams();

        return $teams;
    }
   
   
}
