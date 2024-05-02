<?php
    echo "<html>";
    echo "<head>";
    echo "<title>Информация о графических редакторах</title>";
    echo "<link rel=\"stylesheet\" href=\"page2.css\">";
    echo "</head>";
    echo "<body>";
    echo "<div class=\"cont\">";
    echo "<header>
              <img src=\"python-logo.png\" alt=\"Логотип сайта\" width=\"100\" height=\"100\">
              <h1>Источники</h1>
          </header>
          <hr>
          <nav>
              <ul>
            <li><a href=\"page1.html\">Главная</a></li>
            <li><a href=\"page2.html\">Страница HTML5</a></li>
            <li><a href=\"page3.html\">Использованные источники</a></li>
            <li><a href=\"form.html\">Форма посетителя сайта</a></li>
            <li><a href=\"cool.php\">Информация о python</a></li>
              </ul>
          </nav>
          <hr>
          <main>";

    echo "<form method='POST' action=''>";
    echo "<input type='text' name='library_name' placeholder='Enter library name'>";
    echo "<input type='submit' name='delete' value='Delete'>";
    echo "</form>";

    if (isset($_POST['delete'])) {
        $library_name = $_POST['library_name'];

        /* Подключение к серверу MySQL */
        $link = mysqli_connect(
            'localhost:3306', /* Хост, к которому мы подключаемся */
            'root', /* Имя пользователя */
            '', /* Используемый пароль */
            'web_lab6'); /* База данных для запросов по умолчанию */

        if (!$link) {
            printf("Невозможно подключиться к базе данных. Код ошибки: %s\n",
            mysqli_connect_error());
            exit;
        }

        /* Посылаем запрос серверу */
        $query = "DELETE FROM libraries WHERE Name = ?";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, 's', $library_name);
        mysqli_stmt_execute($stmt);

        /* Закрываем соединение */
        mysqli_close($link);
    }


    /* Подключение к серверу MySQL */
    $link = mysqli_connect(
        'localhost:3306', /* Хост, к которому мы подключаемся */
        'root', /* Имя пользователя */
        '', /* Используемый пароль */
        'web_lab6'); /* База данных для запросов по умолчанию */
    if (!$link) {
        printf("Невозможно подключиться к базе данных. Код ошибки: %s\n",
        mysqli_connect_error());
        exit;
    }
    /* Посылаем запрос серверу */
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

    echo "</main>";
    echo "<hr>
          <footer>
              <p>© 2024 Язык Python</p>
          </footer>";
    echo "</div>";
    echo "</body>";
    echo "</html>";
?>