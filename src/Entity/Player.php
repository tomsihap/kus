<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player
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
    private $pseudo;

  
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profilPic;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="players")
     */
    private $team;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contest", mappedBy="player", orphanRemoval=true)
     */
    private $contests;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tournament", inversedBy="players")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournament;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contest", mappedBy="loser", orphanRemoval=true)
     */
    private $contestslose;

    public function __construct()
    {
        $this->contests = new ArrayCollection();
        $this->contestslose = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }


    public function getVictories() {

        $playerVictories = 0;

        foreach ($this->getContests() as $c) {
            $playerVictories += 1;
        }

        return $playerVictories;
    }


    public function setVictories(int $victories): self
    {
        $this->victories = $victories;

        return $this;
    }

   

    public function getScore() {

        $playerScore = 0;

        foreach ($this->getContests() as $c) {
            
            $playerScore += $c->getGame()->getVictoryValue();
        }

        return $playerScore;
    }

   

    public function getProfilPic(): ?string
    {
        return $this->profilPic;
    }

    public function setProfilPic(?string $profilPic): self
    {
        $this->profilPic = $profilPic;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }

    /**
     * @return Collection|Contest[]
     */
    public function getContests(): Collection
    {
        return $this->contests;
    }

    public function addContest(Contest $contest): self
    {
        if (!$this->contests->contains($contest)) {
            $this->contests[] = $contest;
            $contest->setPlayer($this);
            
        }

        return $this;
    }

    public function removeContest(Contest $contest): self
    {
        if ($this->contests->contains($contest)) {
            $this->contests->removeElement($contest);
            // set the owning side to null (unless already changed)
            if ($contest->getPlayer() === $this) {
                $contest->setPlayer(null);
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

  
    public function getContestPlayed()
    {
        $won = 0;

        foreach ($this->getContests() as $c) {
           $won += 1;
        }

        $played = $won + $this->getLose();
        return $played;

    }


    public function getLose() 
    {
        $loses = 0;
        foreach ($this->getContestslose() as $c) {

            $loses += 1;
        }

        return $loses;
    }

    /**
     * @return Collection|Contest[]
     */
    public function getContestslose(): Collection
    {
        return $this->contestslose;
    }

    public function addContestslose(Contest $contestslose): self
    {
        if (!$this->contestslose->contains($contestslose)) {
            $this->contestslose[] = $contestslose;
            $contestslose->setLoser($this);
        }

        return $this;
    }

    public function removeContestslose(Contest $contestslose): self
    {
        if ($this->contestslose->contains($contestslose)) {
            $this->contestslose->removeElement($contestslose);
            // set the owning side to null (unless already changed)
            if ($contestslose->getLoser() === $this) {
                $contestslose->setLoser(null);
            }
        }

        return $this;
    }

    public function getPercentVictories()
    {

        if ($this->getContestPlayed() > 0) {

            $playerPercentVictories = $this->getVictories() * 100 / $this->getContestPlayed();

            return round($playerPercentVictories, 2) . "%";
        }

        else {
            return "/";
        }
       
    }

   

    
}
