<?php

namespace Stane\Day06\Models;

use Stane\Day06\Models\Position;
use Stane\Day06\Models\Block;

use Stane\Day06\Enums\Direction;
use Stane\Day06\Enums\Node;
use Stane\Day06\Exceptions\Runtime\PositionNotFound;
use Stane\Day06\Exceptions\Runtime\WinningMove;

final class Player {


    /**
     * History of steps
     * @var array<Position>
     */
    public array $historyOfSteps = [];

    /**
     * What is player's current position
     * @var null|Position
     */
    public ?Position $currentPosition;

    /**
     * What direction is player currently facing
     * @var null|Direction
     */
    public ?Direction $currentDirection;

    /**
     * What is player currently standing on
     * @var null|Block
     */
    public ?Block $currentBlock;

    /**
     * What is ahead of the player
     * @var null|Block
     */
    public ?Block $currentlyFacing;


    /**
     * @param Position $startingPosition 
     * @return Player 
     */
    public static function make(Position $startingPosition): self {
        $model = new self;
        $model->setPosition(position: $startingPosition);
        $model->setDirection(direction: Direction::Up);
        return $model;
    }


    /**
     * Umístění hráče na pozici
     * @param Position $position 
     * @return Player 
     */
    public function setPosition(Position $position): self {
        $this->currentPosition = $position;
        $this->addStepToHistory(position: $position);
        return $this;
    }


    /**
     * Nastavení směru hráče
     * @param Direction $direction 
     * @return Player 
     */
    public function setDirection(Direction $direction): self {
        $this->currentDirection = $direction;
        return $this;
    }


    /**
     * Přidání kroku do historie kroků
     * @param Position $position 
     * @return Player 
     */
    public function addStepToHistory(Position $position): self {
        $this->historyOfSteps[] = $position;
        return $this;
    }


    /**
     * Získání pouze unikátních pozic, kterými hráč šel
     * @return int 
     */
    public function getNumberOfDistinctStepsFromHistory(): int {

        $output = 0;
        $seenPositionsFlat = [];

        if (!empty($this->historyOfSteps)) {
            foreach ($this->historyOfSteps as $step) {
                if (!in_array($step->getFlatPosition(), $seenPositionsFlat)) {
                    $seenPositionsFlat[] = $step->getFlatPosition();
                    $output++;
                }
            }
        }

        return $output;

    }


    /**
     * Returns correct str char based on player's direction
     * @return string 
     */
    public function getChar(): string {
        return $this->currentDirection->getChar();
    }


    /**
     * What block is player currently standing on
     * @param Block $block 
     * @return Player 
     */
    public function isStandingOn(Block $block): self {
        $this->currentBlock = $block;
        return $this;
    }


    /**
     * Get position of a block that player is currently facing
     * @return Position 
     * @throws PositionNotFound 
     */
    public function whereIsPlayerLooking(): Position {
        return $this->currentBlock->calculatePositionOfFriend(direction: $this->currentDirection);
    }


    /**
     * Set block that player is currently facing
     * @param Block $block 
     * @return Player 
     */
    public function isFacing(Block $block): self {
        $this->currentlyFacing = $block;
        return $this;
    }


    /**
     * Is space ahead going to be the winning step ?
     * @return bool 
     */
    public function canEscape(): bool {
        if ($this->currentlyFacing->type === Node::BoundaryNode) {
            return true;
        }
        return false;
    }


    /**
     * Is space ahead empty so that player can move there ?
     * @return bool 
     */
    public function canMove(): bool {
        if ($this->currentlyFacing->type === Node::EmptyNode) {
            return true;
        }
        return false;
    }


    /**
     * Move player to the block which was ahead and reset what player can see
     * @return Player 
     */
    public function move(): self {
        $this->currentBlock = clone $this->currentlyFacing;
        $this->setPosition(position: clone $this->currentBlock->position);
        $this->currentlyFacing = null;
        return $this;
    }


    /**
     * Rotate the player according to game rules
     * @return Player 
     */
    public function rotate(): self {
        switch ($this->currentDirection) {
            case Direction::Up:
                $this->currentDirection = Direction::Right;
                break;
            case Direction::Right:
                $this->currentDirection = Direction::Down;
                break;
            case Direction::Down:
                $this->currentDirection = Direction::Left;
                break;
            case Direction::Left:
                $this->currentDirection = Direction::Up;
                break;
        }
        return $this;
    }
    

}
