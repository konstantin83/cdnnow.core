<?php

$protocol = $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$server   = $_SERVER['SEqUXJtn9lvmcZRVER_NAME'];

$MESS["CDNNOW_OPTIONS_HEADER_MAIN"]   = "Настройки интеграции";
$MESS["CDNNOW_OPTIONS_HEADER_TOP"]    = "cdnnow! — ускорение сайта";
$MESS["CDNNOW_OPTIONS_ACTIVE"]        = "Включить CDN для ";
$MESS["CDNNOW_OPTIONS_ADDRESS"]       = "Адрес в CDN";
$MESS["CDNNOW_OPTIONS_HEADER_TYPES"]  = "Что кешировать";
$MESS["CDNNOW_OPTIONS_IMAGES"]        = "Изображения";
$MESS["CDNNOW_OPTIONS_CSS"]           = "Стили CSS";
$MESS["CDNNOW_OPTIONS_JS"]            = "Скрипты Javascript";
$MESS["CDNNOW_OPTIONS_FONT"]          = "Шрифты";
$MESS["CDNNOW_OPTIONS_ARCHIVE"]       = "Архивы";
$MESS["CDNNOW_OPTIONS_AUDIO"]         = "Аудио";
$MESS["CDNNOW_OPTIONS_VIDEO"]         = "Видео";
$MESS["CDNNOW_OPTIONS_EMBEDED"]       = "Встраиваемые объекты";
$MESS["CDNNOW_OPTIONS_OBJECT"]        = "Вызовы API (REST, SOAP)";
$MESS["CDNNOW_OPTIONS_DOC"]           = "Документы";
$MESS["CDNNOW_OPTIONS_EXE"]           = "Запускаемые файлы";
$MESS["CDNNOW_OPTIONS_LINK_REGISTER"] = "Регистрация CDN";
$MESS["CDNNOW_OPTIONS_LINK_PERSONAL"] = "Личный кабинет";

$MESS["CDNNOW_OPTIONS_ADDRESS_DESC"] = "Введите адрес для CDN из вашего аккаунта в cdnnow!<br>
Это служебный домен вида \"userХХХХХ.nowcdn.co\"
или ваш домен для CDN \"cdn.{$server}\".<br>
Не указывайте протокол (HTTP/HTTPS), только сам домен.<br>
Если вы решили использовать свой домен для CDN (например, cdn.{$server}),
вам необходимо сначала настроить CNAME-запись, а также внести настройки в личном кабинете
<a href='https://selfcare.cdnnow.ru' target='_blank'>selfcare.cdnnow.ru</a>.<br>
Подробнее вы можете <a href='https://cdnnow.ru/support/bitrix#domain' target='_blank'>прочитать в статье</a>.";

$MESS["CDNNOW_OPTIONS_RULES_TITLE"] = "Выключить CDN для следующих каталогов на сайте";
$MESS["CDNNOW_OPTIONS_RULES_LABEL"] = "Выключить CDN для следующих каталогов на сайте:";

$MESS["CDNNOW_OPTIONS_RULES_DESC"] = "
<p>Например, введите <b>/catalog</b>, чтобы выключить CDN для пути по адресам:</p>
<ul>
    <li>{$protocol}://{$server}<b>/catalog</b>/*</li>
    <li>{$protocol}://{$server}<b>/catalog</b>/some-sub-catalogue</li>
    <li>{$protocol}://{$server}<b>/catalog</b>/some-sub-catalogue/some-sub-sub-catalogue</li>
</ul>

<p>Так же можно использовать конструкции вида \"<b>*/item</b>\". В этом случае CDN будет выключен по адресам:</p>
<ul>
    <li>{$protocol}://{$server}/catalog<b>/item</b></li>
    <li>{$protocol}://{$server}/catalog<b>/item</b>/som-stuff</li>
    <li>{$protocol}://{$server}<b>/item</b>/som-stuff</li>
</ul>
";

$MESS["CDNNOW_OPTIONS_MAIN_DESC"] = "Модуль «cdnnow! — ускорение сайта» позволит Вам:<br>
<ul>
<li>ускорить загрузку Вашего сайта в регионах;</li>
<li>оптимизировать «на лету» изображения на Вашем сайте;</li>
<li>значительно снизить исходящий трафик с Вашего сервера;</li>
<li>снизить количество запросов к Вашему серверу;</li>
<li>снизить стоимость AntiDDoS для Вашего сервера.</li>
</ul>
<p>
Всё это достигается с помощью автоматического переноса изображений и скриптов на CDN сервера cdnnow!<br>
</p>
<p>Для того, чтобы использовать модуль, оставьте заявку на подключение на сайте <a href='https://cdnnow.ru/#order' target='_blank'>cdnnow.ru</a>.<br>
Настройте данный модуль, используя выданный вам уникальный адрес для CDN или ваш домен для CDN.</p>
<p>
Больше настроек и статистики вы найдете в <a href='https://selfcare.cdnnow.ru/' target='_blank'>личном кабинете cdnnow</a>!<br>
Подробнее про настройку модуля вы можете <a href='https://cdnnow.ru/support/bitrix' target='_blank'>прочитать в статье</a>.
</p>
";
