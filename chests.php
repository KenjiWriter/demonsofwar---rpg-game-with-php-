<?php
/** User.php
 * Class for managing chests data
 */

class chests
{

    public $id;

    public $name;
    public $cost;
    public $items;


    public function __construct($chest_id, $con)
    {
        $chest_id = (int)$chest_id;
        $query = " SELECT * FROM chests WHERE id=$chest_id";
        $result = mysqli_query($con, $query);
        $chest = mysqli_fetch_array($result);
        if ($chest < 1) {
            echo "Invalid chest!";
        }

        $this->id = $chest['id'];
        $this->name = $chest['id'];
        $this->cost = $chest['id'];
        $this->items = array();
        if($chest['attacks'] ) {
            $this->items = json_decode($chest['items'], true);
        }


    }



}