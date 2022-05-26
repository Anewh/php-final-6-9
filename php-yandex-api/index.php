<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>PHP 8 | API </title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <form name='form' class="form" method='POST'>
              <label for="adress" class="form__label form__item">Введите адрес для поиска метро и коориднат: </label><br>
              <label for="adress" class="form_comment form__item">Если введенное значение не является адресов, информация не будет выведена</label><br>
              <input class="form__input form__item" type="text" id="adress" name="adress" required="required"><br>
              <button class="btn submit" id="submit" name="submit">Поиск</button>
        </form>
        <div class="result close" id="result">
            <div class="result_message form__label form__item" id="result_message">
            </div>
        </div>
    </div>
</body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="front.js"></script>
</html>