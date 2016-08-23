<?php
namespace core;
use core\App;

/**
 * Description of Model
 *
 * @author yarik
 */
abstract class Model
{
	/**
	 * Подключение к БД
	 * @return \PDO
	 */
	public function getDB()
	{
		return App::$registry->getConnectionDB();
	}

	/**
	 * Какое поле используется как первичный ключ
	 * @return string
	 */
	public function primaryKey()
	{
		return 'id';
	}

	/**
	 * Список полей
	 * @return string
	 */
	public function attributes()
	{
		return array_keys($this->attributeLabels());
	}

	/**
	 * Наименование полей
	 * @return string
	 */
	public function attributeLabels()
	{
		return [];
	}

	/**
	 * Получение название атрибута
	 * @return string|null
	 */
	public function getAttributeLabel($attribute)
	{
		$list = $this->attributeLabels();
		return ( isset($list[$attribute]) ) ? $list[$attribute] : null;
	}

	/**
	 * Название таблицы, нужно вернуть строку, пример: return 'tableName'
	 */
	abstract protected function getTableName();

	/**
	 * Сохранение, нужно реализовать в каждом отдельно
	 */
	abstract protected function save();
}
