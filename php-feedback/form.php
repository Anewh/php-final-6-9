<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>PHP 6 | FEEDBACK</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer></script>
    <script type="text/javascript" src="front.js" defer></script>
</head>
<body>
    <div class="wrapper">
        <div class="form open">
            <form  method="POST" id="form" name="form" class="form__body">
                <h1 class="form__title">Форма обратной связи</h1>
                <div class="form__item">
                    <label for="fullname" class="form__label">ФИО*:</label>
                    <input type="text" id="fullname" class="form__input" name="fullname" placeholder="Фамилия Имя Отчество" pattenr="^([A-ЯA-Z]{1}[a-zа-я]{1,}\s?){2,}$">
                </div>
                <div class="form__item">
                    <label for="email" class="form__label">Email*:</label>
                    <input type="text" id="email" class="form__input" name="email" placeholder="example@example.com" pattern="((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9А-Яа-я]{1}[-0-9А-я\.]{1,}[0-9А-Яа-я]{1}))@([-0-9A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})">
                </div>
                <div class="form__item">
                    <label for="phone" class="form__label">Номер телефона*:</label>
                    <input type="text" id="phone" class="form__input" name="phone" placeholder="8 *** *** ** ** " pattern="^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}">
                </div>
                <div class="form__item">
                    <label for="content" class="form__label">Введите Ваш комментарий*:</label>
                    <textarea name="content" id="content" class="form__text" required></textarea>
                </div>
                <p class="error_message form__label close" name="error_message" id="error_message"></p>
                <div class="form__item">
                    <button class="btn submit_btn" id="submit" name="submit">Отправить</button>
                </div>
            </form>
        </div>
        <div class="response_content form_label close" id="response_content"></div>
    </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer></script>
    <script type="text/javascript" src="front.js" defer></script>
</body>
</html>