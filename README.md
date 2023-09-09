# cdnnow.core
Модуль интеграции Битрикс с cdnnow.ru.

## Как это работает?
- Установите модуль. В настройках модуля в админке нажмите кнопку `Регистрация в CDNnow` и зарегистрируйтесь
- В дальнейшем посмотреть статистику работы CDN в личном кабинете можно нажав на кнопку `Перейти в личный кабинет`
- Имея на руках ваш адрес в системе CDNnow введите его в поле `Адрес в CDN` без http/https, только домен (например userXXXXX.clients-cdnnow.ru)
- Активируйте CDN кэширование установкой галочки `Использовать CDN`
- Ниже выберите, какие типы файлов сервис будет кэшировать для вас на своей стороне
- Сохраните изменения

После этого все выбранные типы файлов на любой странице вашего сайта будут автоматически заменяться на URL в CDNnow.
Что разгрузит ваш сервер и его интернет-канал, а также позволит посетителям скачивать ваши файлы с ближайшего к ним CDN сервера в автоматическом режиме. Что обеспечит более высокую скорость открытия страниц вашего сайта.

Разработка модуля:\
tg: @salah_k\
https://github.com/konstantin83/cdnnow.core
