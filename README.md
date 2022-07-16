<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Zelenka</h1>
    <br>
</p>

Установка
------------

---
* Сделать базу данных для приложение
* git clone git@github.com:kmolchanov/zelenka.git .
* Скопировать config/db.php в config/db-local.php
* В файле config/db-local.php указать нужные данные для созданной базы данных
* Запустить ./deploy.sh (Либо выполнить команды из deploy.sh вручную)
---

Использование
------------

Получить файл из сети<br>
`./yii order/update-net https://zelenka.ru/sample/orders.json`

Выдать информацию о заказе<br>
`./yii order/info [order-id]`