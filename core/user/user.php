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

            public function __construct(Connection $connection, $userId)
            {
                if($userId != 0)
                {
                    $result = $connection->Query("SELECT * FROM `users` WHERE `user_id` = '$userId';");
                    $result = mysqli_fetch_array($result);
                    $this->UserID = $result['user_id'];
                    $this->UserName = $result['user_name'];
                    $this->UserCPF = $result['user_cpf'];
                    $this->UserBornDate = $result['user_born_date'];
                    $this->UserAge = $this->GetUserAge();
                    $this->UserCash = $result['user_cash'];
                }
                else
                {
                    $this->UserID = 0;
                    $this->UserName = 0;
                    $this->UserCPF = 0;
                    $this->UserBornDate = 0;
                    $this->UserAge = 0;
                    $this->UserCash = 0;
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

            public function RegisterUser(Connection $connection, $userName, $userCPF, $userBornDate)
            {
                $result = $connection->Query("SELECT * FROM `users` WHERE `user_name` = '$userName' AND `user_cpf` = '$userCPF';");
                if(mysqli_num_rows($result)) 
                {
                    return 0;
                }
                else
                {
                    $connection->Query("INSERT INTO `users` (`user_name`, `user_cpf`, `user_born_date`) VALUES ('$userName', '$userCPF', '$userBornDate');");
                    $this->UserName = $userName;
                    $this->UserCPF = $userCPF;
                    $this->UserBornDate = $userBornDate;
                    $this->UserAge = $this->GetUserAge();
                }
            }

            public function GetUserCash()
            {
                return $this->UserCash;
            }
            
            public function GiveUserCash(float $amount)
            {
                $this->UserCash += $amount;
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

            public function SaveUserData(Connection $connection)
            {
                $name = $this->UserName;
                $cash = $this->UserCash;
                $born_date = $this->UserBornDate;
                $CPF = $this->UserCPF;
                $connection->Query("UPDATE `users` SET `user_name` = '$name', `user_cash` = '$cash', `user_born_date` = '$born_date', `user_cpf` = '$CPF'");
            }
        }
    }

?>