<?php
    namespace Database
    {
        class Connection 
        {
            private const DEFAULT_HOSTNAME = "127.0.0.1";
            private const DEFAULT_USERNAME = "root";
            private const DEFAULT_PASSWORD = "";
            private const DEFAULT_DATABASE = "glpay";
            private const DEFAULT_PORT = 3306;
            private const DEFAULT_SOCKET = 3306;
        
            private $HostName;
            private $UserName;
            private $PassWord;
            private $DataBase;
            private $Port;
            private $Socket;
            private $Handle;
        
            public function __construct(string $hostname = self::DEFAULT_HOSTNAME, string $username = self::DEFAULT_USERNAME, string $password = self::DEFAULT_PASSWORD, string $database = self::DEFAULT_DATABASE, int $port = self::DEFAULT_PORT, int $socket = self::DEFAULT_SOCKET) {
                $this->HostName = $hostname;
                $this->UserName = $username;
                $this->PassWord = $password;
                $this->DataBase = $database;
                $this->Port = $port;
                $this->Socket = $socket;
                $this->Connect();
            }

            public function Connect()
            {
                $this->Handle = mysqli_connect($this->HostName, $this->UserName, $this->PassWord, $this->DataBase, $this->Port, $this->Socket);
                if(!$this->Handle) return 0;
                else return 1;
            }
            
            private function ReturnHandle()
            {
                return $this->Handle;
            }

            public function Query(string $query)
            {
                if(!$this->ReturnHandle()) return 0;
                else return mysqli_query($this->ReturnHandle(), $query);
            }

            public function Close()
            {
                if($this->ReturnHandle()) return 0;
                else return mysqli_close($this->ReturnHandle());
            }
        }
        class Table
        {
            private $Name;
            private $ColumnDatas;

            public function __construct(Connection $connection, string $name, string $columnsDatas)
            {
                $this->Name = $name;
                $this->ColumnDatas = $columnsDatas;
                $connection->Query("CREATE TABLE IF NOT EXISTS `$name` ($columnsDatas)");
            }

            public function SetTableName(Connection $connection, string $newName)
            {
                $current_name = $this->Name;
                $connection->Query("RENAME TABLE $current_name TO $newName");
                $this->Name = $newName;
            }

            public function GetTableName(Connection $connection)
            {
                return $this->Name;
            }
        }
    }
?>