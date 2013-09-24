<?php
class Player {
    protected $name;
    protected $score;
    protected $strength;
    protected $email;

    public function __construct() {
        $row = Database::retrieve('users', array('email' => fAuthorization::getUserToken()));
        $this->name = $row['username'];
        $this->score = $row['score'];
        $this->strength = $row['strength'];
        $this->email = $row['email'];
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
