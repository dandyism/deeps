<?php
class Player {
    protected $name;
    protected $score;
    protected $strength;

    public function name($name = null) {
        if ($name == null) {
            return $this->name;
        }

        $this->name = $name;
    }

    public function increase_score_by($points) {
        $this->score += $points;
        if ($this->points < 0) $this->points = 0;
    }

    public function equip_weapon($new_str) {
        if ($this->strength > $new_str) {
            $this->strength = $new_str;
            return true;
        }

        return false;
    }
}
