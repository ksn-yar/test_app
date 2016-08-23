<?php
namespace models;

use core\Model;

/**
 * Результаты, 'data'
 */
class DataModel extends Model
{
	const TYPE_IMG = 'img';
	const TYPE_LINK = 'link';
	const TYPE_TEXT = 'text';

	public $id;
	public $type_element;
	public $site;
	public $data;
	public $total_elements;

	/**
     * @inheritdoc
     */
	public function getTableName()
	{
		return 'data';
	}

	/**
     * @inheritdoc
     */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'type_element' => 'Тип элемента',
			'site' => 'Сайт',
			'data' => 'Элементы',
			'total_elements' => 'Кол-во элементов',

			// виртуальное поле
			'text' => 'Текст',
		];
	}

	/**
	 * Опции типов элементов
	 * @return array
	 */
	public static function listTypes()
	{
		return [
			self::TYPE_IMG => 'Изображения',
			self::TYPE_LINK => 'Ссылки',
			self::TYPE_TEXT => 'Текст',
		];
	}

	/**
	 * Получение названия типа
	 * @param string $value
	 * @return string
	 */
	public static function getTypeLabel($value)
	{
		$list = self::listTypes();
		return ( isset($list[$value]) ) ? $list[$value] : null;
	}

	/**
	 * Сохранение результата поиска, предпологается, что валидация уже сделана
	 * @param string $type_element
	 * @param string $site
	 * @param array $data
	 */
	public function saveAfterSearch($type_element, $site, $data)
	{
		$this->type_element = $type_element;
		$this->site = $site;
		$this->data = serialize($data);
		$this->total_elements = count($data);
		$this->save();
	}

	/**
	 * Непосредственно сохранение
	 * @return boolean результат сохранения
	 */
	public function save()
	{
		$db = $this->getDB();

		$set = [
			'`type_element` = :b_type_element',
			'`site` = :b_site',
			'`data` = :b_data',
			'`total_elements` = :b_total_elements',
		];

		$set = implode(', ', $set);

		// обновление
		if ( $this->{$this->primaryKey()} ) {

			$sql = 'UPDATE';
			$where = 'WHERE `'. $this->primaryKey() .'` = :b_pk';

		// вставка
		} else {
			$where = '';
			$sql = 'INSERT INTO';
		}

		$sql .= ' `'. $this->getTableName() .'` SET '. $set .' '. $where;

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':b_type_element', $this->type_element, \PDO::PARAM_STR);
		$stmt->bindParam(':b_site', $this->site, \PDO::PARAM_STR);
		$stmt->bindParam(':b_data', $this->data, \PDO::PARAM_STR);
		$stmt->bindParam(':b_total_elements', $this->total_elements, \PDO::PARAM_INT);

		if ( $this->{$this->primaryKey()} ) {
			$stmt->bindParam(':b_pk', $this->$this->primaryKey(), PDO::PARAM_INT);
		}

		$result = $stmt->execute();

		if ( ! $this->{$this->primaryKey()} && $result ) {
			$this->{$this->primaryKey()} = $db->lastInsertId();
		}

		return $result;
	}

	/**
	 * Найти все
	 * @return \self[]
	 */
	public function findAll()
	{
		$result = [];		
		$db = $this->getDB();

		$rows = $db->query('SELECT * FROM `'. $this->getTableName() .'`')->fetchAll();

		foreach ( $rows as $value ) {
			$model = new self();
			$model->_load($value);
			$result[] = $model;
		}

		return $result;
	}

	/**
	 * Найти одну запись по id
	 * @return \self
	 */
	public function findOne($id)
	{
		$id = (int)$id;

		$result = null;
		$db = $this->getDB();

		$stmt = $db->prepare('SELECT * FROM `'. $this->getTableName() .'` WHERE `'. $this->primaryKey() .'` = "'. $id .'"');
		$stmt->execute();
		$row = $stmt->fetch();

		if ( $row ) {
			$model = new self();
			$model->_load($row);
			$result = $model;
		}

		return $result;
	}

	/**
	 * Загружаем данные в модель, попутно приводим к типу
	 * @param array $values
	 */
	protected function _load($values)
	{
		$this->id = (int)$values['id'];
		$this->type_element = $values['type_element'];
		$this->site = $values['site'];
		$this->total_elements = (int)$values['total_elements'];
		$this->data = unserialize($values['data']);
	}
}
