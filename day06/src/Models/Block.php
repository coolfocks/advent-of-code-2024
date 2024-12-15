<?php

namespace Stane\Day06\Models;

use Stane\Day06\Models\Position;

use Stane\Day06\Enums\Node;
use Stane\Day06\Enums\Direction;

use Stane\Day06\Exceptions\Runtime\PositionNotFound;

final class Block {


    /**
     * x:y bloku
     * @var null|Position
     */
    public ?Position $position;

    /**
     * Typ bloku
     * @var null|Node
     */
    public ?Node $type;

    
    /**
     * @param Position $position 
     * @param Node $type 
     * @return Block 
     */
    public static function make(Position $position, Node $type): self {
        return new self(position: $position, type: $type);
    }


    /**
     * @param Position $position 
     * @param Node $type 
     * @return void 
     */
    public function __construct(Position $position, Node $type) {
        $this->position = $position;
        $this->type = $type;
    }


    public function calculatePositionOfFriend(Direction $direction): Position {

        switch ($direction) {
            case Direction::Up:
                $x = $this->position->x;
                $y = $this->position->y;
                $y -= 1;
                break;
            case Direction::Down:
                $x = $this->position->x;
                $y = $this->position->y;
                $y += 1;
                break;
            case Direction::Left:
                $x = $this->position->x;
                $y = $this->position->y;
                $x -= 1;
                break;
            case Direction::Right:
                $x = $this->position->x;
                $y = $this->position->y;
                $x += 1;
                break;
        }

        if (!isset($x) || !isset($y)) {
            throw new PositionNotFound('invalid position requested');
        }

        return Position::make(x: $x, y: $y);

    }


}
