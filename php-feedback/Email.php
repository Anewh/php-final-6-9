<?php


class Email
{

    protected $pdo;

    public function __construct()
    {
        if (!($pdoConfig = parse_ini_file('config\\pdo.ini'))) {
            throw new Exception("Ошибка парсинга файла конфигурации", 1);
        }
        $this->pdo = new PDO('mysql:host='. $pdoConfig['host'] .';dbname='. $pdoConfig['dbname'], $pdoConfig['login'], $pdoConfig['password']);
    }

    // Отправка нового обращения в БД
    // Входные данные:
    // $fullname - полное имя автора обращения
    // $email - почта автора обращения
    // $phone - телефон автора обращения
    // $sendtime - время отправки обращения
    // $content - текст обращения
    // Выходные данные:
    // true - удалось сохранить запись в БД
    // false - не удалось сохранить запись в БД
    public function postNewContent($fullname, $email, $phone, $sendtime, $comment){
        try {
            $proc = $this->pdo->prepare("INSERT INTO emails (fullname, email, phone, sendtime, content) 
                                            VALUES (:fullname, :email, :phone, :sendtime, :comment); ");
            $proc->bindValue(":fullname" , $fullname);
            $proc->bindValue(":email" , $email);
            $proc->bindValue(":phone" , $phone);
            $proc->bindValue(":sendtime" , $sendtime);
            $proc->bindValue(":comment" , $comment);
            $proc->execute();
        } catch (PDOException $e) {
            echo "Error was found with save " . $e->getMessage();
            return false;
        }
        return true;
    }

    // Получение информации о времени последнего обращения автора с почтовым адресом $email
    // Входные данные:
    // $email - почта автора, у которого нужно узнать время предпоследнего обращения
    // Выходные данные:
    // Время последнего обращения
    public function getLastMailTimeByEmail($email)
    {
        try{
            $proc = $this->pdo->prepare("SELECT MAX(sendtime) 
                                        FROM emails 
                                        WHERE email=:email;");
            $proc->bindValue(":email", $email, PDO::PARAM_STR);
            $proc->execute();
            return $proc->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            echo "Error was found with save " . $e->getMessage(); //!!!
            return $e->getMessage();
        }
    }
}