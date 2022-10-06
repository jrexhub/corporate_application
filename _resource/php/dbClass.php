<?php
class dbClass extends formClass
{
    public $dbh;
    public $default = array();

    public $production = array(
        'dbname' => '*_db',
        'user' => '*_user',
        'pass' => '*_pass',
    );

    public $development = array(
        'dbname' => '*_db',
        'user' => '*_user',
        'pass' => '*_pass',
    );

    public $pref_arr = array();

    public function __construct()
    {

        if ($_SERVER['SERVER_ADDR'] !== '127.0.0.1') {
        //if ($_SERVER['SERVER_ADDR'] === '127.0.0.1') {
            $this->default = $this->production;
        } else {
            $this->default = $this->development;
        }

        try {
		    $this->dbh = new PDO('mysql:host=localhost;dbname=' . $this->default["dbname"], $this->default["user"], $this->default["pass"]);
		    /*foreach($this->dbh->query('SELECT * from mtr_preves') as $row) {
		        print_r($row);
		    }*/
		    //$dbh = null;
		} catch (PDOException $e) {
		    print "DB Error : " . $e->getMessage();
            die();
		}

        $this->pref_arr = $this->get_address_from_pcode();

    }

    public function get_address_from_pcode(){
        $sql = 'SELECT * from mtr_preves';
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_COLUMN, 1);
        array_unshift($result, '選択してください');
        return $result;
    }

}

$dbObj = new dbClass();
