<?php

class Database {
    private $host = HOST;
    private $username = USERNAME;
    private $password = PASSWORD;
    private $database = DATABASE;
    private $dbObject;

    private $pdo;
    
    private $cloudStoragePath;
    private $cloudTempPath;
    private $shutdownUpload;

    public function __construct(){   
        $this->cloudStoragePath = 'https://storage.googleapis.com/chromeseabed-file-storage/public/chromeseabed.sqlite';
        $this->cloudTempPath = '/tmp/chromeseabed.sqlite';
        if($this->isLocalEnv()){
            $this->pdo = $this->getLocalSqliteConnection();
        }else{
            $this->pdo = $this->getCloudSqliteConnection();
        }
    }

    public function downloadFromCloud() {
        $fileContent = file_get_contents($this->cloudStoragePath);
        if ($fileContent !== false) {
            return file_put_contents($this->cloudTempPath, $fileContent);
        }
        return false;
    }
    
    public function initializeDatabase() {
        // If no local database exists, download from cloud
        if (!file_exists($this->cloudTempPath) || filesize($this->cloudTempPath) == 0) {
            echo "📥 Downloading database from Cloud Storage...\n";
            return $this->downloadFromCloud();
        }
        return true;
    }
    
    
    private function isLocalEnv(){
        if(ENVIRONMENT == 'DEVELOPMENT'){
            return true;
        }else{
            return false;
        }
    }

    public function getLocalSqliteConnection(){
        $dbPath = dirname(dirname(__DIR__)) . '/public/chromeseabed.sqlite' ;
        //$dbPath = BASE_MEDIA_ROOT . '/public/vibewalls.sqlite';
        //echo $dbPath;
        if (!file_exists($dbPath)) {
            touch($dbPath);
            chmod($dbPath, 0666);
        }
        $pdo = new PDO("sqlite:$dbPath");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $pdo->exec("PRAGMA foreign_keys = ON");
        return $pdo;
    }

    private function getCloudSqliteConnection() {
        // Your Cloud Run database connection (SQLite or MySQL)
        $this->shutdownUpload = new ShutdownUpload();
        $this->initializeDatabase();
        $pdo = new PDO("sqlite:$this->cloudTempPath");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $pdo->exec("PRAGMA foreign_keys = ON");
        return $pdo;
    }

    public function query($sql, $params = array(), $fetchMode = ''){
        //$handler = $this->dbObject;
        $handler = $this->pdo;
        $statement = $handler->prepare($sql);
        $statement->execute($params);
        if($fetchMode == ''){
            return $statement;
        }
        $results = $statement->$fetchMode();
        return $results;

        // //return $statement->$fetchMode();
        // //return $result;
        
        //return $statement->$fetchMode();
    }

    // public function __construct(){

    //     $dsn = "mysql:host=".$this->host.";dbname=".$this->database;
    //     $options = [
    //         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    //         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    //     ];
    //     try{
    //         $this->dbObject = new PDO($dsn, $this->username, $this->password, $options);
    //     }catch(PDOException $e){
    //         print "<b>Database Error: </b> ".$e->getMessage();
    //     }
    // }

    // public function query($sql, $params = array(), $fetchMode = ''){
    //     $handler = $this->dbObject;
    //     $statement = $handler->prepare($sql);
    //     $statement->execute($params);
    //     if($fetchMode == ''){
    //         return $statement;
    //     }
    //     return $statement->$fetchMode();
    //     //return $result;
    // }
}
?>