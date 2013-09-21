<?php
/**
 */

require 'ViewValues.php';

//тестовый набор данных
$tags = array(
	array(
		'name' => '<a>',
		'description' => 'Тег <a> является одним из важных элементов HTML и предназначен для создания ссылок. В зависимости от присутствия атрибутов name или
			href тег <a> устанавливает ссылку или якорь. Якорем называется закладка внутри страницы, которую можно указать в качестве цели ссылки. При
			использовании ссылки, которая указывает на якорь, происходит переход к закладке внутри веб-страницы.',
		'syntax' => '<a href="URL">...</a>' .
					'<a name="идентификатор">...</a>',
		'example' => '<p><a href="images/xxx.jpg">Посмотрите на мою фотографию!</a></p>' .
  					 '<p><a href="tip.html">Как сделать такое же фото?</a></p>',
	),
	array(
		'name' => '<b>',
		'description' => 'Устанавливает жирное начертание шрифта. Допустимо использовать этот тег совместно с другими тегами, которые определяют начертание
			текста.',
		'syntax' => '<b>Текст</b>',
		'example' => '<p><b>Lorem ipsum dolor sit amet</b></p>
			<p><b>Lorem ipsum</b> dolor sit amet, consectetuer adipiscing elit,
			sed diem nonummy nibh euismod tincidunt ut lacreet dolore magna aliguam erat
			volutpat. <b>Ut wisis</b> enim ad minim veniam, quis nostrud exerci
			tution ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</p>',
	),
	array(
		'name' => '<h1>',
		'description' => 'HTML предлагает шесть заголовков разного уровня, которые показывают относительную важность секции, расположенной после заголовка.
			Так, тег <h1> представляет собой наиболее важный заголовок первого уровня, а тег <h6> служит для обозначения заголовка шестого уровня и является
			наименее значительным. По умолчанию, заголовок первого уровня отображается самым крупным шрифтом жирного начертания, заголовки последующего уровня
			по размеру меньше. Теги <h1>,...,<h6> относятся к блочным элементам, они всегда начинаются с новой строки, а после них другие элементы отображаются
			на следующей строке. Кроме того, перед заголовком и после него добавляется пустое пространство.',
		'syntax' => '<h1>Заголовок первого уровня</h1>',
		'example' => '<h1>Lorem ipsum dolor sit amet</h1>
			<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diem
			nonummy nibh euismod tincidunt ut lacreet dolore magna
			aliguam erat volutpat.</p>

			<h2>Ut wisis enim ad minim veniam</h2>
			<p>Ut wisis enim ad minim veniam, quis nostrud exerci tution
			ullamcorper suscipit lobortis nisl ut aliquip ex ea
			commodo consequat.</p>',
	),
	array(
		'name' => '<hr>',
		'description' => 'Рисует горизонтальную линию, которая по своему виду зависит от используемых параметров, а также браузера. Тег <hr> относится к блочным
		 	элементам, линия всегда начинается с новой строки, а после нее все элементы отображаются на следующей строке.',
		'syntax' => '<hr>',
		'example' => '<hr>
			<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diem nonummy nibh
			euismod tincidunt ut lacreet dolore magna aliguam erat volutpat. Ut wisis enim
			ad minim veniam, quis nostrud exerci tution ullamcorper suscipit lobortis nisl
			ut aliquip ex ea commodo consequat.</p>
			<hr>',
	),
);

$tags = new ViewValues($tags);

?>
<html>
<head>
	<meta charset="utf-8">
	<title>Пример использования ViewValues</title>
	<style>
		td { border: 1px solid #ccc; padding: 5px 10px}
		tr:hover td{ background-color: #eee  }
	</style>
</head>
<body>
	<h3><i>Пример использования ViewValues</i></h3>
	<hr>

<h1>Таблица HTML-тегов</h1>
<table>
	<tr>
		<th>Тег</th>
		<th>Описание</th>
		<th>Синтаксис</th>
		<th>Пример</th>
	</tr>
	<?php foreach($tags->getAll() as $one){ /** @var ViewValues|ViewValues[] $one */ ?>
	<tr>
		<td><?=$one['name']?></td>
		<td width="30%"><?=$one['description']?></td>
		<td><nobr><?=$one['syntax']?></nobr></td>
		<td><?=$one->html('example')?></td>
	</tr>
	<?php } ?>
</table>
</body>
</html>