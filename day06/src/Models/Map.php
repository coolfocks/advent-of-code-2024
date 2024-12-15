<?php

namespace Stane\Day06\Models;

/**
 * Models
 */
use Stane\Day06\Models\Position;
use Stane\Day06\Models\Block;
use Stane\Day06\Enums\Node;

/**
 * Traits
 */
use Stane\Day06\Traits\Makeable;

/**
 * Exceptions
 */
use Stane\Day06\Exceptions\Input\MapNotFound;
use Stane\Day06\Exceptions\Input\MapProcessingError;
use Stane\Day06\Exceptions\Runtime\BlockNotFound;

final class Map {


    use Makeable;


    private const BOUNDARY_CHAR = 'X';


    /**
     * Blocks
     * @var array
     */
    public array $data = [];

    /**
     * Starting player position
     * @var null|Position
     */
    protected ?Position $playerLocation;


    /**
     * @param string $uid 
     * @return Map 
     * @throws MapNotFound 
     * @throws MapProcessingError 
     */
    public function processMap(string $uid): self {

        /**
         * Existuje soubor ?
         */
        $filename = APP_ROOT_DIR . '/input/' . $uid . '.txt';
        if (!file_exists($filename)) {
            throw new MapNotFound('input file with map was not found');
        }

        /**
         * Získáme obsah souboru
         */
        $map = file_get_contents($filename);
        if ($map === false) {
            throw new MapNotFound('could not ready input map properly');
        }

        /**
         * Rozdělíme soubor na řádky
         */
        $lines = explode(PHP_EOL, $map);
        if (!isset($lines[0])) {
            throw new MapProcessingError('could not process map input properly');
        }

        /**
         * Pomocníci
         */
        $lengthOfLine = strlen($lines[0]);

        /**
         * Na začátek a na konec souboru přidáme hranici
         */
        array_unshift($lines, str_repeat(self::BOUNDARY_CHAR, $lengthOfLine));
        array_push($lines, str_repeat(self::BOUNDARY_CHAR, $lengthOfLine));
        // $lines = array_values($lines);

        $y = 0;
        foreach ($lines as $line) {
            $x = 0;
            $chars = str_split($line, 1); // rozstřelíme str do pole
            array_unshift($chars, self::BOUNDARY_CHAR); // na začátek a na konec přidáme hranici
            array_push($chars, self::BOUNDARY_CHAR); // na začátek a na konec přidáme hranici
            foreach ($chars as $char) {
                switch ($char) {
                    case '.':
                        $this->data[$y][$x] = Block::make(position: Position::make(x: $x, y: $y), type: Node::EmptyNode);
                        break;
                    
                    case '^':
                        $this->data[$y][$x] = Block::make(position: Position::make(x: $x, y: $y), type: Node::EmptyNode);
                        $this->playerLocation = Position::make(x: $x, y: $y);
                        break;

                    case '#':
                        $this->data[$y][$x] = Block::make(position: Position::make(x: $x, y: $y), type: Node::ObstacleNode);
                        break;
                    
                    case 'X':
                        $this->data[$y][$x] = Block::make(position: Position::make(x: $x, y: $y), type: Node::BoundaryNode);
                        break;
                }
                $x++;
            }
            $y++;
        }

        return $this;

    }


    /**
     * Get starting player position
     * @return bool|Position 
     */
    public function getPlayerPosition(): bool|Position {
        if (!isset($this->playerLocation)) {
            return false;
        }
        return $this->playerLocation;
    }


    /**
     * Get block model for specific position
     * @param Position $position 
     * @return Block 
     * @throws BlockNotFound 
     */
    public function getBlock(Position $position): Block {
        if (!isset($this->data[$position->y][$position->x])) {
            throw new BlockNotFound('requested block is not present on map');
        }
        return $this->data[$position->y][$position->x];
    }


    /**
     * @return string 
     */
    public function debugPrint(Player $player): string {

        $output = '';

        foreach ($this->data as $keyX => $line) {
            foreach ($line as $keyY => $block) {
                /**
                 * Pokud se na herním políčku nachází hráč, vytiskneme jeho orientaci
                 */
                if ($block->position->x === $player->currentPosition->x && $block->position->y === $player->currentPosition->y) {
                    $output .= $player->getChar();
                }
                /**
                 * Pokud tam není hráč, vytiskneme blok na kterém stojí
                 */
                else {
                    $output .= $block->type->getChar();
                }
            }
            $output .= PHP_EOL;
        }

        return $output;

    }


}
