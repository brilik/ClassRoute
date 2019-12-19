# Route
<p>Класс для создания Route и их методов.</p>
<p>Для его использования обязательно должен быть создан конфигурационный файл <code>.htaccess</code> на сервере <code>Apache</code>.</p>

<b>File ".htaccess"</b>

> RewriteEngine On <br>
> RewriteBase /suit.ru/<br>
> RewriteCond %{REQUEST_FILENAME} !-f<br>
> RewriteCond %{REQUEST_FILENAME} !-d<br>
> RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]

<h4 id="use">Как использовать класс?</h4>
<p>Создаём экземпляр класса.</p>
<ol>
<li>
    Method "add" is build collection <code>URI</code> and get two param:
    <ul>
    <li>URI - slug page</li>
    <li>METHOD - <code>[null:default]</code> use class for this page</li>
    </ul>
</li>
<li>
    Method "start" is route to class and get one param
    <ul>
    <li>METHOD - <code>[param:default\folder]</code> is style get <code>URI</code> request</li>
    </ul>
</li>
</ol>

<h4 id="struct">Какая структура?</h4>
<p>Структура была взята из уже продуманной <code>CMS</code> <code>WordPress</code>.</p>
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
<li><code>404.php</code></li>
</ul>