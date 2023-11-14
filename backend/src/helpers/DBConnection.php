<?php
    namespace backend\helpers;

    use mysqli;
    use backend\helpers\ENVManager;

    class DBConnection {
        
        public mysqli $connection;
        public function __construct() {

            $this->connection = new mysqli(
                ENVManager::GetENV("DB_HOST"),
                ENVManager::GetENV("DB_USER"),
                ENVManager::GetENV("DB_PASSWORD"),
                ENVManager::GetENV("DB_NAME")
            );
        }

        public function query(string $query, ?array $data = null) {
            $data = $this->connection->execute_query($query, $data);
            if (!is_bool($data)){
                return $data->fetch_all(MYSQLI_ASSOC);
            }
            return $data;
        }

        public function __destruct()
        {
            $this->connection->close();
        }
    }

?>