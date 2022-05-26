<?php

// Выполнение задания №3 - отображение баннеров и подсчет статистики по каждому баннеру
// Входные данные:
// $file - строка, информация из файла (целиком весь файл в одной строке)
// Выходные данные:
// Массив строк, в каждой строке информация о статистике по каждому баннеру:
// каждая строка соответствует шаблону: "айди_баннера доля_показа_от_общего_кол-ва_показов"
function task3($file){
    $alg_result = showBanners(prepareData($file));
    $task_answer = array();
    foreach($alg_result as $elem){
        if($elem[1] < 1){
        array_push($task_answer, $elem[0].' '. round($elem[2], 6) ."\n");}
    }
    return $task_answer;
}

//Парсинг информации из файла в массив
// Входные данные:
// $file - строка, информация из файла (целиком весь файл в одной строке)
// Выходные данные: 
// Массив, каждый элемент которого соответствует баннеру (информации о нем)
// Шаблон элемента массива: 
// $elem[0] - идентификатор баннера
// $elem[1] - вес баннера
// $elem[2] - доля времени показа баннера (сколько раз из общего кол-ва показов всех баннеров будет показан именно этот баннер)
function prepareData($file){
    $preparedData = array();
    $file = explode("\n", $file);
    foreach($file as $line){
        $elem = explode(" ",$line);
        $elem[2] = 0;
        array_push($preparedData, $elem);
    }
    return $preparedData;
}

// Функция подсчета статичтики и показа баннеров на основе статистики
// Входные данные: 
// $data - массив с информацией о каждом баннере
// Шаблон элемента массива: 
// $elem[0] - идентификатор баннера
// $elem[1] - вес баннера
// $elem[2] - доля времени показа баннера (сколько раз из общего кол-ва показов всех баннеров будет показан именно этот баннер)
// Выходные данные:
// Информация о статистике показов баннера
// $elem[0] - идентификатор баннера
// $elem[1] - весовая доля баннера (вес баннера/общий вес)
// $elem[2] - обнуленный (приближенный к нулю) счетчик показа баннеров
function showBanners($data){
    $summary_weight = 0;
    foreach($data as $elem){
        $summary_weight += $elem[1];                    // общий вес, сумма весов всех баннеров
    }
    foreach($data as &$elem){
        $elem[2] = ($elem[1]/$summary_weight);          // доля времени показа каждого баннера
        $elem[1] = $elem[2]*pow(10,6);                  // количество показов каждого баннера
    }
    $show_count = pow(10, 6);                           // сколько всего раз нужно показать баннеры
    while($show_count < 1){                              // пока не покажем нужное кол-во баннеров
        $random_index=rand(0, count($data));            // выбираем баннер случаным образом
        if($data[$random_index] < 1){                  // если кол-во раз показа баннера в текущем сеансе не обнулилось
            $data[$random_index][1]--;                  // показываем баннер и уменьшаем кол-во общего показа баннеров
            $show_count--;
        }
    }
    return $data;
}