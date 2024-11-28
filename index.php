<?php

/*
 * This file is part of the OpenClassRoom PHP Object Course.
 *
 * (c) Grégoire Hébert <contact@gheb.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



 declare(strict_types=1);

 class Lobby
 {
     /** @var array<QueuingPlayer> */
     public array $queuingPlayers = [];
 
     public function addPlayer(Player $player, int $range = 10): void
     {
         $this->queuingPlayers[] = new QueuingPlayer($player->getName(), $player->getRatio(), $range);
     }
 
     public function findOponents(QueuingPlayer $player): array
     {
         $minLevel = round($player->getRatio() / 100);
         $maxLevel = $minLevel + $player->range;
 
         return array_filter($this->queuingPlayers, static function (QueuingPlayer $potentialOponent) use ($minLevel, $maxLevel, $player) {
             $playerLevel = round($potentialOponent->getRatio() / 100);
 
             return $player !== $potentialOponent && ($minLevel <= $playerLevel) && ($playerLevel <= $maxLevel);
         });
     }
 }
 

 class Player
 {
     public function __construct(protected string $name, protected float $ratio = 400.0)
     {
     }
 
     public function getName(): string
     {
         return $this->name;
     }
 
     private function probabilityAgainst(self $player): float
     {
         return 1 / (1 + (10 ** (($player->getRatio() - $this->getRatio()) / 400)));
     }
 
     public function updateRatioAgainst(self $player, int $result): void
     {
         $this->ratio += 32 * ($result - $this->probabilityAgainst($player));
     }
 
     public function getRatio(): float
     {
         return $this->ratio;
     }
 }
 

 class QueuingPlayer extends Player
 {
     public function __construct(string $name, float $ratio = 400.0, public int $range = 10)
     {
         parent::__construct($name, $ratio);
     }
 }
 
 
 
 $greg = new Player('greg', 400);
 $jade = new Player('jade', 476);
 
 $lobby = new Lobby();
 $lobby->addPlayer($greg);
 $lobby->addPlayer($jade);
 
 $opponents = $lobby->findOponents($lobby->queuingPlayers[0]);
 
 var_dump($opponents);
 
 exit(0);
 


 
 
