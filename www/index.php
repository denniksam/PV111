<h1>PHP. Вступ.</h1>
<img src='/php.png' width="100" />
<p>
	Встановлення: потрібен веб-сервер (Apache) та окремо - РНР.
	Простіше за все встановити збірку на кшталт XAMPP, у ній 
	налаштовані взаємні конфігурації серверу та мови.
</p>
<p>
	Налаштування: при встановленні утворюється один локальний хост
	(localhost), він розміщений у папці htdocs (xampp).
	Можна видалити все з цієї папки та замінити на власний сайт.
	Віртуальний хост можна налаштувати через конфігурацію Apache
	редагуванням файлу /conf/extra/httpd-vhosts.conf (зразок є у файлі).
	Якщо хосту задається власне ім'я, то його треба зазначити у 
	DNS-файлі системи (/windows/system32/drivers/etc/hosts) (зразок є у файлі)
</p>
<p>
	У локальному хості створюємо файл index.php (цей файл).
	РНР є надбудовою над HTML, тобто довільний HTML файл є валідним РНР
	файлом. РНР надає можливість додавати до HTML активність через
	вставки &lt;?php код ?> або для виразів &lt;?= вираз ?>
</p>
<p>
	Шаблонізація. Для забезпечення однакового вигляду сторінок бажано 
	створити одну сторінку-шаблон та підставляти у неї різний контент.
	Необхідно: створити диспетчер доступу - точку через яку проходять усі
	запити (інакше кожен запит буде звертатись до свого файлу).
	Ця задача вирішується файлом локальних налаштувань сервера (.htaccess), що 
	розміщується у папці з сайтом
</p>
<p>
	Змінні у РНР мають особливість - їх імена мають починатись з символа "$".
	Суперглобальні масиви. У РНР є ряд масивів, доступних у довільній частині
	коду. Один з них - $_SERVER:
	<pre>
	<?php print_r( $_SERVER ) ; ?>
	</pre>
</p>
	