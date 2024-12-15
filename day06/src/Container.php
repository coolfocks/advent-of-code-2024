<?php

namespace Stane\Day06;

use Stane\Day06\Models\Map;
use Stane\Day06\Models\Player;

use Stane\Day06\Exceptions\Runtime\PlayerNotFound;

final class Container {


    /**
     * World
     * @var null|Map
     */
    private ?Map $map;

    /**
     * Player
     * @var null|Player
     */
    private ?Player $player;


    public function run(): void {

        /**
         * Process map file into workable world model
         */
        $this->map = Map::make()->processMap(uid: 'map');

        /**
         * Get player's starting position from world
         */
        $playerPositionOnMap = $this->map->getPlayerPosition();
        if (!$playerPositionOnMap) {
            throw new PlayerNotFound('player not found on map');
        }

        /**
         * Create player
         */
        $this->player = Player::make(startingPosition: $playerPositionOnMap);

        /**
         * What block is player currently standing on ?
         */
        $this->player->isStandingOn(
            block: $this->map->getBlock(
                position: $this->player->currentPosition
            )
        );

        /**
         * What block does player currently see ahead of itself ?
         */
        $this->player->isFacing(
            block: $this->map->getBlock(
                position: $this->player->whereIsPlayerLooking()
            )
        );

        /**
         * Make moves until player can escape
         */
        $playerEscaped = false;
        $steps = 1;
        while (!$playerEscaped) {

            /**
             * If player can NOT move, rotate and see what's ahead now
             */
            if (!$this->player->canMove()) {
                $this->player->rotate()
                ->isFacing(
                    block: $this->map->getBlock(
                        position: $this->player->whereIsPlayerLooking()
                    )
                );
                continue;
            }

            /**
             * Move player
             */
            $this->player->move()
            /**
             * See what block player is standing on
             */
            ->isStandingOn(
                block: $this->map->getBlock(
                    position: $this->player->currentPosition
                )
            )
            /**
             * See what's ahead now
             */
            ->isFacing(
                block: $this->map->getBlock(
                    position: $this->player->whereIsPlayerLooking()
                )
            );

            /**
             * Decide if player can escape in next move
             */
            if ($this->player->canEscape()) {
                $playerEscaped = true;
            }

            echo 'step ' . $steps . PHP_EOL;

            $steps++;

            // $this->debugPrint(); // pro postupné ukázání cesty

            // die after x steps
            // if ($steps > 1000) {
            //     exit;
            // }

        }

    }


    /**
     * Provide active player
     * @return Player 
     */
    public function provideActivePlayer(): Player {
        return $this->player;
    }


    /**
     * Vytisknutí aktuálního stavu světa
     * @return void 
     */
    public function debugPrint(): void {
        echo $this->map->debugPrint(player: $this->player);
        echo PHP_EOL;
        echo PHP_EOL;
        echo PHP_EOL;
        echo PHP_EOL;
        // exit;
    }


}
