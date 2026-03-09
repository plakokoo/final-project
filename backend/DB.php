<?php

class DB {
    private $host;
    private $user;
    private $password;
    private $dbName;

    private $conn = null;

    public function __construct() {
        $this->loadEnv();

        $this->host = getenv('DB_HOST');
        $this->user = getenv('DB_USER');
        $this->password = getenv('DB_PASS');
        $this->dbName = getenv('DB_NAME');
    }

 private function loadEnv() {
    $envFile = __DIR__ . '/../.env';
    if (!file_exists($envFile)) return;

    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

       foreach ($lines as $line) {
       
        if (strpos(trim($line), '#') === 0) continue;

        
        [$name, $value] = explode('=', $line, 2);

        
        $name = trim($name);
        $value = trim($value, " \t\n\r\0\x0B\"'");

        
        putenv("$name=$value");
        $_ENV[$name] = $value;
    }

}

    public function createConnection() {
        if ($this->conn === null ) {
            $this->conn = new mysqli(
                $this->host,
                $this->user,
                $this->password,
                $this->dbName
            );

            if($this->conn->connect_error) {
                error_log("Database Connection Error: " . $this->conn->connect_error);
                throw new Exception("Database Connection Failed");
            }
            
            $this->conn->set_charset("utf8mb4");
        }

        return $this->conn;
    }
}

?>