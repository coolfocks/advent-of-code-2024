<?php

namespace Stane\Day06\Traits;

trait Makeable {

    /**
     * 
     * @return Makeable 
     */
    public static function make(): self {
        return new self;
    }

}
