<?php
/**
 * Класс для хранения значений, передаваемых в html-шаблон.
 * При получении значений из объектов данного класса автоматически происходит замена HTML сущностей (тегов) на их эквиваленты.
 * 
 * @example 
 * Пример использования:
 * 	$v = new ViewValues();
 * 	$v[0] = array('A&B');
 * 	echo $v[0][0]; // A&amp;B
 *  echo $v[0]->html(0); //A&B
 * 
 * @version 1.0
 * @author Илья Савинков <staliniv@gmail.com>
 */
class ViewValues extends ArrayObject{
	/** @var array Кэш для отфильтрованных значений */
	protected $cache = array();

	/**
	 * Рекурсивный конструктор
	 *
	 * @param array $array
	 * @return ViewValues
	 */
	function __construct(&$array = array()){
		foreach ($array as $key => $value){
			if (is_array($value)){
				$array[$key] = new self($value);
			}
		}
		parent::__construct($array);
	}

	/**
	 * Перегрузка метода класса ArrayObject:
	 * возвращаемые значения фильтруются через htmlentities() и кешируются
	 *
	 * @param mixed $offset Ключ
	 * @return mixed Отфильтрованное значение
	 */
	function offsetGet($offset){
		//Необходимо для простой работы с многомерными массивами в случае, если значение не было инициализировано
		if (!parent::offsetExists($offset)){
			$this->offsetSet($offset, new self());

			//как и обычный array() - кидаем notice
			$trace = debug_backtrace();
			$callee = $trace[0];
			trigger_error("Undefined offset: '$offset' in {$callee['file']} on line {$callee['line']}");
		}

		$value = parent::offsetGet($offset);

		//кеширование
		if (!isset($cache[$offset])){
			if (is_string($value)){
				$this->cache[$offset] = htmlentities($value, ENT_QUOTES, 'UTF-8');
			}else{
				$this->cache[$offset] = $value;
			}
		}

		return $this->cache[$offset];
	}

	/**
	 * Возвращает значение в исходном (неотфильтрованном) виде
	 * Используется, когда нужно отобразить текст с html тегами, не экранируя их
	 * 
	 * @param mixed $offset Ключ
	 * @return mixed Значение
	 */
	function html($offset){
		return parent::offsetGet($offset);
	}

	/**
	 * Перегрузка метода класса ArrayObject::offsetSet(): добавлена очистка кеша
	 *
	 * @param mixed $offset
	 * @param mixed $value
	 * @return void
	 */
	function offsetSet($offset, $value){
		unset($this->cache[$offset]);
		if (is_array($value)){
			$value = new ViewValues($value);
		}
		return parent::offsetSet($offset, $value);
	}

	/**
	 * Перегрузка ArrayObject::offsetUnset(): добавлена очистка кеша
	 *
	 * @param unknown_type $offset
	 * @return unknown
	 */
	function offsetUnset($offset){
		unset($this->cache[$offset]);
		return parent::offsetUnset($offset);
	}

	/**
	 * Возвращает массив. Только первый уровень.
	 * Для преобразования всего объекта в многоуровневый массив используйте getAllRec()
	 *
	 * @param bool $html Вернуть исходные значения(true) или отфильтрованные(false)
	 * @return array
	 */
	function getAll($html = false){
		if ($html){
			return parent::getArrayCopy();
		}
		//Если не все значения отфильтрованы, то наполняем кеш
		if ($this->count() != count($this->cache)){
			$arr = parent::getArrayCopy();
			$keys = array_diff(array_keys($arr), array_keys($this->cache));
			foreach ($keys as $key){
				if (is_string($arr[$key])){
					$this->cache[$key] = htmlentities($arr[$key], ENT_QUOTES, 'UTF-8');
				}else{
					$this->cache[$key] = $arr[$key];
				}
			}
		}
		return $this->cache;
	}

	/**
	 * Рекурсивно преобразует объект в многомерный отфильтрованный массив
	 * См. также метод getAll()
	 *
	 * @param bool $html Вернуть исходные значения(true) или отфильтрованные(false)
	 * @return array
	 */
	function getAllRec($html = false){
		$result = $this->getAll($html);
		foreach ($result as $key => $value){
			if ($value instanceof self){
				$result[$key] = $value->getAllRec();
			}
		}
		return $result;
	}

	/**
	 * Магический метод для преобразования в строку
	 *
	 * @return string
	 */
	function __toString(){
		return '';
	}

	/**
	 * Явный запрет на использование итератора.
	 * Итерирование через ArrayObject слишком медленное
	 */
	function getIterator(){
		throw new Exception('Для итерирования преобразуйте объект ' . __CLASS__ . ' в исходный массив с помощью getAll() или getAllRec()');
	}
}