<?php


    class config
    {
        private $host      = 'localhost';
        private $user      = 'root';
        private $pass      = '';
        private $dbname    = 'csv';
        
        private $pdo;
        private $stmt;
        private $error;
        public function __construct()
        {
            $dsn     = "mysql:host=" . $this->host . ";dbname=" . $this->dbname;
            $options = array( 
            PDO::ATTR_PERSISTENT         => true,
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        
            );

            // Create PDO instance
            try
            {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
            } 
            catch (PDOException $e) 
            {
                $this->error = $e->getMessage();
                echo $this->error;
            }
        }    
        public function file($file)
        {
            $handle = fopen($file,'r');
            if($handle !== false)
            {
                $header = fgetcsv($handle);
                $arr = array_flip($header);
                while($data = fgetcsv($handle))
                {
                    $data_arr =[ 
                    'name' => $data[0],
                    'email' =>   $data[1],
                    'age' => $data[2],
                    'address' => $data[3]
                    ];
                    $columns = array_keys($data_arr);
                    $columnsSql = implode(',',$columns);
                    $bindingSql = ':' . implode(',:',$columns);
                    $sql = "INSERT INTO file ($columnsSql) VALUES ($bindingSql)";
                    $stm = $this->pdo->prepare($sql);
                    foreach($data_arr as $key=>$value)
                    {
                        $stm->bindValue(":" . $key,$value);
                    }
                    $status = $stm->execute();
                }if($status)
                    {
                        echo "Successfully Inserted";
                        header("location:index.php");
                    }
            }
        }


    }


?>