<?php


/** User.php
 * Class for managing monster data
 */

class Monster
{
    // Members/properties (variable)
    public $id;
    public $name;

    public $level;

    public $max_health;
    public $health;

    public $min_dmg;


    public $strength;
    public $intelligence;
    public $endurance;

    public $attacks;
    public $drops;


    // Methods (functions)
    public function __construct($monster_id, $con)
    {
        $monster_id = (int)$monster_id;
        $query = " SELECT * FROM monsters WHERE id=$monster_id ORDER BY 'level' ";
        $result = mysqli_query($con, $query);
        $monster = mysqli_fetch_array($result);
        if ($monster < 1) {
            echo "Invalid monster!";
        }


        $this->id = $monster['id'];
        $this->name = $monster['name'];
        $this->level = $monster['level'];
        $this->max_health = $monster['max_health'];
        if(!isset($_SESSION['monster_health'])){
            $_SESSION['monster_health'] = $this->max_health;
        }
        $this->health = $_SESSION['monster_health'];
        $this->min_dmg = $monster['min_dmg'];
        $this->strength = $monster['strength'];
        $this->intelligence = $monster['intelligence'];
        $this->endurance = $monster['endurance'];
        $this->attacks = $monster['attacks'];

        $this->attacks = array();
        if($monster['attacks'] ) {
            $this->attacks = json_decode($monster['attacks'], true);
        }
        $this->drops = array();
        if($monster['drops'] ) {
            $this->drops = json_decode($monster['drops'], true);
        }
    }

    public function update() {
        $_SESSION['monster_health'] = $this->health;

    }

}
