<?php

require_once "Email.php";

class EmailController {
    private $fullname;          // полное имя автора обращения
    private $email;             // почтовый адрес автора обращения
    private $phone;             // телефон автора обращения
    private $content;           // текст обращения автора
    private $email_model;       // модель для работы с БД, которой соответствует контроллер
    public $error_message;      // сообщение об ошибке

    public function __construct($fullname, $email, $phone, $content)
    {
        $this->email_model = new Email();
        $this->fullname = $fullname;
        $this->email = $email;
        $this->phone = $phone;
        $this->content = $content;
    }

    // Валидация данных на беке. Проверка на наличие обращения от того же автора за последний час.
    // Входные данные: описаны комментариями у полей класса
    // Выходные данные: 
    // true, если за последний час автор с указанным адресом почты не отправлял обращений
    // exit\die, если за последний час автор уже отправил обращение
    private function prepareMessage(){
        $current_datetime = date("y-m-d H:i:s");
        $last_time_message = $this->email_model->getLastMailTimeByEmail($this->email);
        if($last_time_message["MAX(sendtime)"] != NULL){
            array_walk($last_time_message, 
                function($last_send_time){
                    $time_interval = time() - strtotime($last_send_time);
                    if ($time_interval < 3600){ // < 1 hour
                        $message = "Вам запрещено отправлять обращение еще в течении " . strval(intdiv(3600 - $time_interval, 60)) .  " минут";
                        $this->error_message = $message;
                        
                        $response =[
                        "status" =>false,
                        "message" =>$this->error_message
                    ];
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    die();
                    }
                }
            );
        }
        return true;
    }

    // Отправка данных на почту и сохранение информации об обращении в БД
    // Формирование сообщений для пользователя и для менеджера
    // Входные данные: описаны комментариями у полей класса
    // Выходные данные: JSON object
    public function sendMessage(){
        if($this->prepareMessage()) {
            $message_for_manager = "Оставлено сообщение из формы обратной связи\n" . 
                                    "Полное имя: " . $this->fullname . "\n" .
                                    "E-mail: " . $this->email . "\n" .
                                    "Телефон: " . $this->phone . "\n" .
                                    "Обращение" . $this->content . "\n";

            $response_message = "Оставлено сообщение из формы обратной связи <br>
                                Полное имя: " . $this->fullname . "<br>" . 
                                "E-mail: " . $this->email . "<br>" . 
                                "Телефон: " . $this->phone . "<br>" . 
                                "С Вами свяжутся после " . date("y-m-d H:i:s", strtotime("+90 minutes"));

            $current_datetime = date("y-m-d H:i:s");
            if($this->email_model->postNewContent($this->fullname, $this->email, $this->phone, $current_datetime, $this->content)){
                mail('i0ianrusi0i@gmail.com', 'Отправитель: ' . $this->fullname, $message_for_manager, "From: " . $this->email);
                $response =[
                    "status" =>true,
                    "message"=>$response_message,
                ];
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                die();
            }
        }
        else{
            $this->error_message = "Не удалось отправить обращение";
        }
    }

}

$fullname = htmlspecialchars($_POST['fullname']);
$email = htmlspecialchars($_POST['email']);
$phone = htmlspecialchars($_POST['phone']);
$content = htmlspecialchars($_POST['content']);

$emailController = new EmailController($fullname, $email, $phone, $content);

try{
    $emailController->sendMessage();
}
catch (Exception $e) {
    echo "Ошибка сохранения: " . $e->getMessage();
}
    
if ($emailController->error_message=="") {
    $response =[
        "status" =>false,
        "message" =>$emailController->error_message
    ];
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);