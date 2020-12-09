<?php
    class csv{
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'csv';
    
    public function __construct()
    {
        $sql = "mysql:host=".$this->host. ";dbname=" .$this->dbname;
        $options = array(
         PDO::ATTR_ERRMODE  => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_PERSISTENT => true,
        );
        try {
            $this->pdo = new PDO($sql, $this->user, $this->pass, $options);
           } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
           }
    }
    public function file($file){
        $data = fopen($file,'r');
        if($data !== false){
            $head = fgetcsv($data);
            $header = array_flip($head);
            while($arr = fgetcsv($data)){
                    $data_arr = [
                        'name' => $arr[0],
                        'email' => $arr[1],
                        'age' => $arr[2],
                        'address' => $arr[3]
                    ];
                    $columns = array_keys($data_arr);
                    $columnsSql = implode(',',$columns);
                    $bindSql = ':'.implode(',:',$columns);
                    $sql = "INSERT INTO file ($columnsSql) VALUES ($bindSql)";
                    $stm = $this->pdo->prepare($sql);
                    foreach($data_arr as $key => $value){
                          $stm->bindValue(":".$key,$value);
                    }
                    $status = $stm->execute();
            }
            if($status){
                echo 'Data have been already inserted';
            }
        }
    }
    }
?> 