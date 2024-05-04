<?php
    header('Content-Type: text/html; charset=utf-8');
    setlocale(LC_ALL, 'ru_RU.65001', 'rus_RUS.65001', 'Russian_Russia. 65001', 'russian');

    $json = file_get_contents('php://input');

    $data = json_decode($json);

    $znew_version = $data->new_version;
    $zlib_name = $data->lib_name;


    /* Подключение к серверу MySQL */
    $link = mysqli_connect(
        'localhost', /* Хост, к которому мы подключаемся */
        'root', /* Имя пользователя */
        '', /* Используемый пароль */
        'web_lab6'); /* База данных для запросов по умолчанию */

    if (!$link) {
        printf("Невозможно подключиться к базе данных. Код ошибки: %s\n",
        mysqli_connect_error());
        exit;
    }

    $query = "UPDATE libraries
              SET last_version = '$znew_version'
              WHERE libraries.id = '$zlib_name'";

    if (mysqli_query($link, $query)) {
        echo "Версия успешно обновлена <br>";
    } else {
        echo "Ошибка: " . $query . "<br>" . mysqli_error($link);
    }



     if ($result = mysqli_query($link, 'SELECT DISTINCT Name, Last_version, developers.first_name, developers.second_name, Description FROM libraries
                           RIGHT JOIN developers ON libraries.Developer_id = developers.id
                   ')) {
        print("Библиотеки:\n");
        echo "<br>";
        echo "<table>
                <tr>
                    <th>Название</th>
                    <th>Последняя версия</th>
                    <th>Разработчик</th>
                    <th>Описание</th>
                </tr>";
        while( $row = mysqli_fetch_assoc($result) ){

            printf("<tr><td>".$row['Name']."</td><td>".$row['Last_version']."</td><td>".$row['first_name'].' '.$row['second_name']."</td><td>".$row['Description']."</td></tr>");
        }
        echo "</table>";
    /* Освобождаем используемую память */
        mysqli_free_result($result);
    }

    /* Закрываем соединение */
    mysqli_close($link);
?>