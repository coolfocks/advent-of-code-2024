<?php

namespace Stane\Day06\Enums;

enum Node: string {


    case EmptyNode = 'empty';

    case ObstacleNode = 'obstacle';

    case BlockerNode = 'blocker';

    case BoundaryNode = 'boundary';


    /**
     * @return string 
     */
    public function getChar(): string {
        return match ($this) {
            static::EmptyNode => '.',
            static::ObstacleNode => '#',
            static::BlockerNode => 'O',
            static::BoundaryNode => 'X',
        };
    }


}
