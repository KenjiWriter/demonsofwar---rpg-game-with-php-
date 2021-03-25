<?php

/** User.php
 * Class for managing item data
 */

class Item {
    // properties (variable)
    public $id;

    public $name;
    public $required_level;

    public $price;

    public $strength;
    public $intelligence;
    public $endurance;
    public $luck;

    public $damage;

    public $item_type;
    public $type;
    public $rare;







    // Methods (functions)
    public function __construct($item_id, $con)
    {
        $item_id = (int)$item_id;
        $query = "SELECT * FROM `items` WHERE id='$item_id'";
        $result = mysqli_query($con,$query);
        $row = mysqli_fetch_array($result);
        if($row < 1)
        {
            echo 'Item does not exist!';
        }


        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->strength = $row['strength'];
        $this->intelligence = $row['intelligence'];
        $this->price = $row['purchase_cost'];
        $this->endurance = $row['endurance'];
        $this->luck = $row['luck'];
        $this->damage = $row['damage'];
        $this->item_type = $row['item_type'];
        $this->type = $row['type'];
        $this->required_level = $row['required_level'];
    }

}
