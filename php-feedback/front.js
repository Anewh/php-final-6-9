class FormValidator{
                                        // Следующие 4 поля класса - имена input для ввода соответствующего значения
    fullname_field = 'fullname';        // полное имя автора обращения
    email_field = 'email';              // почтовый адрес автора обращения
    phone_field = 'phone';              // телефон автора обращения
    content_field = 'content';          // текст обращения автора

    // Массив соответствия поля и регулярного выражения для валидации соответствующего значения
    patterns = {
        [this.fullname_field]: /^([A-ЯA-Z]{1}[a-zа-я]{1,}\s?){2,}$/,
        [this.email_field]: /((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9А-Яа-я]{1}[-0-9А-я\.]{1,}[0-9А-Яа-я]{1}))@([-0-9A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})/,
        [this.phone_field]: /((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}/
    };

    // Массив соответствия поля и сообщения об ошибке
    // Вынесено в отдельный массив для удобства расширяемости при внесении новых не текстовых полей
    errors = {
        [this.fullname_field]: 'Полное имя должно иметь вид: Имя Фамилия Отчество <br>',
        [this.email_field]: 'Email должен иметь вид: example@example.com<br>',
        [this.phone_field]: 'Телефон должен иметь вид: 8 *** *** ** **<br>',
        [this.content_field]: 'Заполните поле с обращением<br>'
    };

    constructor(form){
        this.form = form;
    }

    // Валидация всех полей формы
    // Выходные данные:
    // true - если все поля провалидированы корректно
    // false - если хотя бы 1 поле заполнено с ошибкой
    validate_all() {
        let is_fullname_correct = this.validate_field(this.fullname_field);
        let is_email_correct = this.validate_field(this.email_field);
        let is_phone_correct = this.validate_field(this.phone_field);
        let is_content_correct = true;
        $("#content").removeClass('invalid');
        if(this.form.elements[this.content_field].value.trim() == ''){ 
            is_content_correct = false;
            this.showError(this.content_field);
        }
        return is_fullname_correct && is_email_correct && is_phone_correct && is_content_correct;
    }

    // Валидация конкретного поля формы
    // Входные данные: field_name - имя input элемента из формы.
    // Выходные данные:
    // True - валидация прошла
    // False - валидация не прошла
    validate_field(field_name) {
        let is_valid = this.#isCorrect(field_name);
        if (is_valid) {
            this.#removeError(field_name);
        } else {
            this.showError(field_name);
        }
        return is_valid;
    }

    // Проверка соответствия текстового значения поля регулярному выражению
    // Входные данные: Field_name - имя input элемента из формы.
    // Выходные данные:
    // True - валидация прошла
    // False - валидация не прошла
    #isCorrect(field_name){
        return this.patterns[field_name].test(this.form.elements[field_name].value);
    }

    // Удаление класса ошибки из элемента формы input 
    // Входные данные: Field_name - имя input элемента из формы.    
    #removeError(field_name){
        let selector = '#' + field_name;
        if($(selector).hasClass('invalid')){
            $(selector).removeClass('invalid');
        }
    }

    // Добавление класса ошибки элементу формы input и отображение сообщения об ошибке 
    // Входные данные: Field_name - имя input элемента из формы.    
    showError(field_name) {
        $('#' + field_name).addClass('invalid');
        $('.error_message').removeClass('close').addClass('open');
        $('.error_message').append(this.errors[field_name]);
    }

    // Получить информацию о всех полях формы
    // Выходные данные: объект data для ajax, значения полей соответствуют вводимым значениям в поля input формы
    getData(){
        return {
                fullname: this.form.elements[this.fullname_field].value,
                email: this.form.elements[this.email_field].value,
                phone: this.form.elements[this.phone_field].value,
                content: this.form.elements[this.content_field].value
        };
    } 
}

let validator = new FormValidator(document.form);


$('#submit').click(function(e){
    e.preventDefault();
    $('.error_message').empty();
    if(validator.validate_all()){
        $.ajax({
            url: 'EmailController.php',
            type: 'POST',
            dataType: 'json',
            data: validator.getData(),
            success: function (data){
                if(data.status) {
                    $('#form').addClass('close');
                    $('#response_content').empty();
                    $('#response_content').removeClass('close').addClass('open');
                    $('#response_content').append(data.message);
                }
                else{
                    $('#error_message').text("");
                    $('#error_message').addClass('open').append(data.message);
                }
            }
        });
    }
});

$(".form").on("submit", function(e){
    e.preventDefault();
    return false;
})