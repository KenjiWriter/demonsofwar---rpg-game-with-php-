<?php

/** User.php
* Class for managing user data
*/

class User {
	// Members/properties (variable)
	public $id;
	
	public $username;
	public $password;
	
	public $level;
	public $stats_points;
	public $exp;
	public $exp_to_lvl_up;

	public $health;
	public $max_health;
	public $stamina;
	public $max_stamina;
	public $mana;
	public $max_mana;

	public $monster_que;


	public $silver_coin;
	
	public $strength;
	public $intelligence;
	public $dexterity;
	public $endurance;
	public $luck;

	public $damage;
	public $crit_chance;

	public $attacks;
	public $equipped_attacks;
	public $max_equipped_attacks;
    public $attacks_equipped;
    public $friends;

	public $items;
	public $weapon;
	public $armor;
	public $talisman;
	public $ring;

	
	
	
	
	
	
	// Methods (functions)
	public function __construct($user_id, $con)
    {
        $user_id = (int)$user_id;
        $query = "SELECT * FROM `user` WHERE id='$user_id'";
        $result = mysqli_query($con,$query);
        $row = mysqli_fetch_array($result);
        if($row < 1)
        {
            throw new Exception ('User does not exist!');
        }


        $this->id = $row['id'];
        $this->username = $row['username'];
        $this->password = $row['password'];
        $this->level = $row['level'];
        $this->stats_points = $row['stats_points'];
        $this->exp = $row['exp'];
        $this->exp_to_lvl_up = $this->level * 14.8;
        $this->health = $row['health'];
        $this->max_health = $row['max_health'];
        $this->monster_que = $row['monster_que'];
        $this->max_stamina = $row['stamina'];
        if(!isset($_SESSION['stamina'])){
            $_SESSION['stamina'] = $this->max_stamina;
        }
        $this->stamina = $_SESSION['stamina'];
        $this->max_mana = $row['mana'];
        if(!isset($_SESSION['mana'])){
            $_SESSION['mana'] = $this->max_mana;
        }
        $this->mana = $_SESSION['mana'];
        $this->silver_coin = $row['silver_coin'];
        $this->strength = $row['strength'];
        $this->intelligence = $row['intelligence'];
        $this->dexterity = $row['dexterity'];
        $this->endurance = $row['endurance'];
        $this->luck = $row['luck'];
        $this->damage = $row['min_dmg'];
        $this->crit_chance = $row['crit_chance'];
        $this->equipped_attacks = $row['equipped_attacks'];
        $this->max_equipped_attacks = $row['max_equipped_attacks'];
        $this->weapon = $row['weapon'];
        $this->armor = $row['armor'];
        $this->talisman = $row['talisman'];
        $this->ring = $row['ring'];

        $this->attacks = array();
        if($row['attacks'] ) {
            $this->attacks = json_decode($row['attacks'], true);
        }
        $this->attacks_equipped = array();
        if($row['attacks_equipped'] ) {
            $this->attacks_equipped = json_decode($row['attacks_equipped'], true);
        }
        $this->items = array();
        if($row['items'] ) {
            $this->items = json_decode($row['items'], true);
        }

        $this->friends = array();
        if($row['friends'] ) {
            $this->friends = json_decode($row['friends'], true);
        }


    }

    public function update() {
	    global $con;
	    $attacks = json_encode($this->attacks);
	    $items = json_encode($this->items);
        $attacks_equipped = json_encode($this->attacks_equipped);
        $sql = "UPDATE user SET 
                level ='{$this->level}',
                stats_points ='{$this->stats_points}',
                exp ='{$this->exp}',
                health ='{$this->health}',
                max_health ='{$this->max_health}',
                stamina ='{$this->max_stamina}',
                mana ='{$this->max_mana}',
                monster_que ='{$this->monster_que}',
                silver_coin ='{$this->silver_coin}+{$this->silver_coin}',
                strength ='{$this->strength}',
                intelligence ='{$this->intelligence}',
                dexterity ='{$this->dexterity}',
                endurance ='{$this->endurance}',
                luck ='{$this->luck}',
                min_dmg ='{$this->damage}',
                crit_chance ='{$this->crit_chance}',
                items ='{$items}',
                attacks ='{$attacks}',
                equipped_attacks ='{$this->equipped_attacks}',
                weapon ='{$this->weapon}',
                armor ='{$this->armor}',
                talisman ='{$this->talisman}',
                ring ='{$this->ring}',
                friends ='{$this->friends}',
                attacks_equipped ='{$attacks_equipped}' 



            WHERE id='{$this->id}' ";

        if (mysqli_query($con, $sql)) {
        } else {
            echo "Error updating record: " . mysqli_error($con);
        }
    }

    public function update_stamina_mana() {
        $_SESSION['mana'] = $this->mana;
        $_SESSION['stamina'] = $this->stamina;

    }


}
