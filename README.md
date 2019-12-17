<h1>Route</h1>
<p>Класс для создания Route и их методов.</p>
<p>Для его использования обязательно должен быть создан конфигурационный файл <code>.htaccess</code> на сервере <code>Apache</code>.</p>

<b>File ".htaccess"</b>
<br>
<code>
RewriteEngine On<br>
RewriteBase /suit.ru/<br>
RewriteCond %{REQUEST_FILENAME} !-f<br>
RewriteCond %{REQUEST_FILENAME} !-d<br>
RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]
</code>

<h4>Как использовать класс?</h4>
<p>Создаём экземпляр класса.</p>
<ol>
<li>
    Method "add" get two param:
    <ul>
    <li>URI - this is page</li>
    <li>METHOD - create class for this page</li>
    </ul>
</li>
<li>
    Method "submit" - отпределяет по URI страницу переданную в параметре как <code>?uri=</code> и вызывает нужный класс или функцию
</li>
</ol>

<h4>Какая структура?</h4>
<p>Структура была взята из уже продуманной <code>cms WordPress</code>.</p>
<p>Обязательные файлы:</p>
<ul>
<li><code>style.css</code></li>
<li><code>index.php</code></li>
</ul>
<p>Необязательные файлы:</p>
<ul>
<li><code>page.php</code> по приоритету выше чем <code>index.php</code></li>
<li><code>header.php</code></li>
<li><code>footer.php</code></li>
</ul>