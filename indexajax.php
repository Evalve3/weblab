<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 transitional//EN"
        "http://www.w3.org/TR/HTML4.01/loose.dtd">
<html>
    <head>
        <title>Применение XMLHttpRequest</title>
        <meta charset="UTF-8">
        <script type="text/javascript">
            var req=false;
            if(window.XMLHttpRequest) {
                req=new XMLHttpRequest();
            } else {
                try { req=new ActiveXObject('Msxml2.XMLHTTP');
                } catch (e)
                { req=new ActiveXObject('Microsoft.XMLHTTP'); } }
            if (! req) // если объект XMLHttpRequest не поддерживается
                alert('Объект XMLHttpRequest не поддерживается данным браузером');
            function Load() {
                if (!req) return;
                req.onreadystatechange = receive;
                
                const lib_for_update = document.getElementsByName("lib_name")[0].value;
                const new_version = document.getElementsByName("version")[0].value;

                var data = {
                    lib_name: lib_for_update,
                    new_version: new_version,
                }

                req.open("POST", "myajax.php", true);
                req.setRequestHeader("Content-type", "application/json");
                req.send(JSON.stringify(data)); // посылаем запрос серверу
            }
            function receive() { // получение данных от сервера
                if (req.readyState == 4){ // если запрос завершен
                    if (req.status == 200) { // если запрос завершен без ошибок
                        document.getElementById('content').innerHTML = req.responseText;
                    }
                    else {
                        alert('Ошибка '+ req.status+': \n' + req.statustext);
                    }
                }
            }
        </script>
        <style>
            .message {
                border: 1px solid #ccc;
                padding: 10px;
                margin-bottom: 10px;
            }
            table td th{
                border: none;
            }
            body{
                background-image: linear-gradient(white, rgb(152, 152, 152));
                margin: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
            }
            .cont{
                width: 800px;
                padding: 20px;
                background-color: white;
            }
            td {
                vertical-align: top;
            }
        </style>
    </head>

    <body>
        <div class="cont">
            <header>
                <img src="python-logo.png" alt="Логотип сайта" width="100" height="100">
                <h1>Источники</h1>
            </header>
            <hr>
            <nav>
                 <ol>
                    <li><a href="page1.html">Главная</a></li>
                    <li><a href="page2.html">Страница HTML5</a></li>
                    <li><a href="page3.html">Использованные источники</a></li>
                    <li><a href="form.html">Форма посетителя сайта</a></li>
                    <li><a href="cool.php">Информация о python</a></li>
                    <li><a href="indexajax.php">Обновить версию библиотеки</a></li>
                </ol>
            </nav>
            <hr>

            <main>
                Обновить данные о версии библиотеки<br><br>
                <table>
                    <tr>
                        <td>Библиотека</td>
                        <td>Версия</td>

                    </tr>
                    <tr>

                        <td>
                            <?php
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

                                /* Посылаем запрос серверу */
                                if ($result = mysqli_query($link, 'SELECT ID, Name FROM libraries')) {
                                        echo "<select name='lib_name'>";
                                    /* Выборка результатов запроса */
                                    while( $row = mysqli_fetch_assoc($result) ){
                                        printf("<option value='%s'>%s</option>", $row['ID'], $row['Name']);
                                    }
                                    echo "</select>";
                                    /* Освобождаем используемую память */
                                    mysqli_free_result($result);
                                }

                            ?>
                        </td>
                        <td><input type="text" name="version" value=""> </td>
                    </tr>
                </table>
                <br>
                <br>
                <input type="button" value="Обновить версию библиотеки" onclick="Load()"/>
                <br><br>
                <?php
                    echo "<div id=\"content\">";
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
                echo "</div>";
                ?>

            </main>
            <hr>
            <footer>
                <p>© 2024 Язык Python</p>
            </footer>
        </div>
    </body>
</html>