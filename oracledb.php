<?php
class OracleDb {
  private $pdo = FALSE;
  private $tns = " 
    (DESCRIPTION =
        (ADDRESS_LIST =
            (ADDRESS = (PROTOCOL = TCP)(HOST = " . ODB_HOST . ")(PORT = " . ODB_PORT . "))
        )
        (CONNECT_DATA =
            (SERVICE_NAME = " . ODB_SERVICE_NAME . ")
        )
    )";

  function __construct(){
    $this->open();
  }

  public function getPdo() {
		return $this->pdo;
	}

  public function open(){
    if($this->pdo) return;

    try{
        $this->pdo = new PDO("oci:dbname=" . $this->tns . ";charset=UTF8",ODB_USERNAME,ODB_PASSWORD);
        $this->pdo->setAttribute(PDO::ATTR_PERSISTENT, true);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }catch(PDOException $e){
        exit ($e->getMessage());
    }
  }

  public function close(){
    unset($this->pdo);
    $this->pdo = FALSE;
  }

  public function executeSql($sql, $parameters = array()){
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute($parameters);
  }

  public function getOne($sql, $parameters = array()){
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($parameters);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getAll($sql, $parameters = array()){
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($parameters);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function insertReturnLastInsertId($sql, $parameters = array(), $return_value_index = 1){
    $return_id = 0;
    $stmt = $this->pdo->prepare($sql);
    if(count($parameters)){
      foreach($parameters as $k=>$v){
        $stmt->bindParam($k, $v);
      }
    }
    $stmt->bindParam($return_value_index, $return_id, PDO::PARAM_INT, 40000000);
    $stmt->execute();
    return $return_id;
  }
}
?>
