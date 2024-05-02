import asyncio
import json

import aiofiles


async def write_file(data, filename):
    async with aiofiles.open(filename, mode='a', encoding='utf-8') as f:
        await f.write(json.dumps(data, ensure_ascii=False) + '\n')


async def main():
    # вывод в таблицу
    import cgi

    form = cgi.FieldStorage()  # извлечь данные из формы

    print("Contenttype: text/html")  # плюс пустая строка
    html1 = """
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Таблица с анкетой</title>
        <link rel="stylesheet" href="page2.css">
    </head>
    <body>
        <div class="cont">
                <header>
                    <img src="python-logo.png" alt="Логотип Python" width="32" height="32">
                </header>
                <hr>
                <nav>
                    <ul>
                        <li><a href="page1.html">Главная</a></li>
                        <li><a href="page2.html">Страница HTML5</a></li>
                        <li><a href="page3.html">Использованные источники</a></li>
                        <li><a href="form.html">Форма посетителя сайта</a></li>
                    </ul>
                </nav>
                <hr>
                <main style="display: flex; justify-content: center;>
                    <table border="2">
                        <tr>
    """

    # печать заголовка  страницы
    print(html1)

    html1 = """
    <TITLE>таблица с анкетой</TITLE>
    <table border =2> <tr>  <td>Имя поля</td><td>Значение</td>  </tr>
    """

    # печать заголовка таблицы
    print(html1)

    ll = ['Фамилия', 'Имя', 'Отчество', 'Вид деятельности', 'С какими библиотеками вы работали?',
          'Какой ваш любимый Python фреймворк?', 'file']

    data = ['', '', '', '', '', '', ''];
    i = 0

    for field in ('secondname', 'firstname', 'patronymic', 'job', 'libraries', 'framework', 'file'):
        if not field in form:
            data[i] = '(unknown)'
        else:

            if not isinstance(form[field], list):
                data[i] = form[field].value
            else:
                values = [x.value for x in form[field]]
                data[i] = ', '.join(values)
        i += 1

    for i in range(min(len(ll), len(data))):
        print('<tr><td> %s </td> <td> %s </td></tr>' % (ll[i], data[i]))

    print(' </table> </main>')
    task = asyncio.create_task(write_file(data, 'data.txt'))
    await task


asyncio.run(main())
