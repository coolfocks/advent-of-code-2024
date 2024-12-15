<?php

namespace Stane\Day06\Enums;

enum Direction: string {


    case Up = 'up';

    case Down = 'down';

    case Left = 'left';

    case Right = 'right';


    /**
     * @return string 
     */
    public function getChar(): string {
        return match ($this) {
            static::Up => '^',
            static::Down => 'v',
            static::Left => '<',
            static::Right => '>',
        };
    }
    

}
