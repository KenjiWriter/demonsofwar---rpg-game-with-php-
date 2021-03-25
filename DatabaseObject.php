<?php
/** 
* DatabaseObject.php
* DatabaseObject to handle managing database connection and queries
*/

class DatabaseObject {

    private $con;

	public function __construct($host, $username, $password, $database){
		$this->con = mysqli_connect($host, $username, $password, $database);
		if(!$this->con){
			echo 'Error connection to database!';
			return false;
		}
		return $this;
	}
	public function clean($data){
		return mysqli_real_escape_string($this->con, $data);
	}




    public function query($query)
    {
        $result = mysqli_query($this->con, $query);
    }

    public function fetch($result){
	    return mysqli_fetch_assoc($result);
    }


    public function num_rows($result) {
        return mysqli_num_rows($result);
    }

    public function affected_rows() {
        return mysqli_affected_rows($this->con);
    }
}
