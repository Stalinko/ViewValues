ViewValues
==========

Удобный класс для автоматической фильтрации HTML-тегов
Позволит вам навсегда забыть об экранированни данных в шаблонах

Класс реализован на основе SPL::ArrayObject. За счёт этого все объекты данного класса выглядят внешне как массивы

Примеры использования:

Запись и чтение:
```php
$v = new ViewValues();
$v[0] = array('A&B');
echo $v[0][0]; // A&amp;B
echo $v[0]->html(0); //A&B
```

Преобразование:
```php
$v = new ViewValues('A&B');
$v[] = new ViewValues([1, 2]);
print_r($v->getAll());          //array('A&amp;B', ViewValues([1, 2]));
print_r($v->getAllRec());       //array('A&amp;B', array(1, 2));
print_r($v->getAllRec(true);    //array('A&B', array(1, 2));
```

Итерирование:
```php
$v = new ViewValues(['<a>', 'M&Ms', 'Come on >>>']);
foreach($v->getAll() as $one){
  echo $one, "\n";
}
/*
&lt;a&gt;
M&amp;Ms
Come on&gt;&gt;&gt;
*/
```

Важно - итерирование напрямую запрещено в целях оптимизации! Итерирование через методы ArrayObject происходит слишком медленно. Для итерирования обязательно требуется работать с исходным массивом.
