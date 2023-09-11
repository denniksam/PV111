<?php

?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<title>PV-111</title>
	<!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
	<!--Import Google Icon Font-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
	<nav>
		<div class="nav-wrapper orange">
		  <a href="/" class="brand-logo">PV-111</a>
		  <ul id="nav-mobile" class="right hide-on-med-and-down">
			<li><a href="sass.html">Sass</a></li>
			<li><a href="badges.html">Components</a></li>
			<li><a href="collapsible.html">JavaScript</a></li>
		  </ul>
		</div>
	</nav>
	<div class="container">
	<h1>PHP. Вступ.</h1>
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
		Змінні у РНР мають особливість - їх імена мають починатись з символа "$".
		Суперглобальні масиви. У РНР є ряд масивів, досупних у довільній частині
		коду. Один з них - $_SERVER:
		<pre>
		<?php print_r( $_SERVER ) ; ?>
		</pre>
	</p>
	</div>
<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>