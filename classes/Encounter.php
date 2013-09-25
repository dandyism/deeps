<?php
/**
 * Dungeon encounters
 **/
class Encounter
{
    protected $score;
    protected $death;
    protected $text;
    
    function __construct($depth)
    {
        $result = Database::retrieve('encounters',
            array(
                "mindepth <= $depth",
            )
        );

        $index          = mt_rand(0, count($result)-1);
        $row            = $result[$index];
        $this->score    = $row['score'];
        $this->death    = $row['death'];
        $this->text     = $row['text'];
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }

        return $this;
    }
}
