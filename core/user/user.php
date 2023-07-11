<?php
    namespace User
    {

        use Database\Connection;
        use DateTime;

        class User
        {
            private $UserID;
            private $UserName;
            private $UserCPF;
            private $UserBornDate;
            private $UserAge = 0;
            private $UserCash;

            public function __construct($userId)
            {
                if($userId != 0)
                {
                    $database = new Connection();
                    $result = $database->Query("SELECT * FROM `users` WHERE `user_id` = '$userId';");
                    $result = mysqli_fetch_array($result);
                    $this->UserID = $result['user_id'];
                    $this->UserName = $result['user_name'];
                    $this->UserCPF = $result['user_CPF'];
                    $this->UserBornDate = $result['user_born_date'];
                    $this->UserAge = $this->GetUserAge();
                    $this->UserCash = $result['user_cash'];
                    $database->Close();
                }
            }

            private function GetUserAge()
            {
                if($this->UserAge == 0)
                {
                    $born_date = new DateTime($this->UserBornDate);
                    $current_date = new DateTime();
                    $age = $current_date->diff($born_date)->y;
                    $this->UserAge = $age;
                    return $age;
                }
                else return $this->UserAge;
            }

            public function RegisterUser($userName, $userCPF, $userBornDate)
            {
                $database = new Connection();
                $result = $database->Query("SELECT * FROM `users` WHERE `user_name` = '$userName' AND `user_cpf` = '$userCPF';");
                if($result) 
                {
                    $database->Close();
                    return 0;
                }
                else
                {
                    $database->Query("INSERT INTO `users` (`user_name`, `user_CPF`, `user_born_date`) VALUES ($userName, $userCPF, $userBornDate);");
                    $this->UserName = $userName;
                    $this->UserCPF = $userCPF;
                    $this->UserBornDate = $userBornDate;
                    $this->UserAge = $this->GetUserAge();
                    $database->Close();
                }
            }

            public function GetUserCash()
            {
                return $this->UserCash;
            }
            
            public function SetUserCash(float $amount)
            {
                $this->UserCash = $amount;
            }

            public function GetUserName()
            {
                return $this->UserName;
            }

            public function SetUserName(string $name)
            {
                $this->UserName = $name;
            }

            public function GetUserID()
            {
                return $this->UserID;
            }

            public function SaveUserData()
            {
                $name = $this->UserName;
                $cash = $this->UserCash;
                $born_date = $this->UserBornDate;
                $CPF = $this->UserCPF;
                $database = new Connection();
                $database->Query("UPDATE `users` SET `user_name` = '$name', `user_cash` = '$cash', `user_born_date` = '$born_date', `user_cpf` = $CPF");
                $database->Close();
            }

        }
    }

?>