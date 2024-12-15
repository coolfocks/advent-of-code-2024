<?php

namespace Stane\Day06\Models;

final class Position {

    /**
     * Pozice na ose x
     * @var null|int
     */
    public ?int $x;

    /**
     * Pozice na ose y
     * @var null|int
     */
    public ?int $y;


    /**
     * Vytvoření modelu pozice
     * @param int $x 
     * @param int $y 
     * @return Position 
     */
    public static function make(int $x, int $y): self {
        return new self(x: $x, y: $y);
    }


    /**
     * @param int $x 
     * @param int $y 
     * @return void 
     */
    public function __construct(int $x, int $y) {
        $this->x = $x;
        $this->y = $y;
    }


    /**
     * @return string 
     */
    public function getFlatPosition(): string {
        return implode(':', [$this->x, $this->y]);
    }

}
