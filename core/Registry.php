<?php
namespace core;

/**
 * Регистратор глобальных переменных
 */
class Registry
{
	const METHOD_POST = 'POST';
	const METHOD_GET = 'GET';

	const AJAX_REQUEST = 'xmlhttprequest';

	/** @var регистрируемые переменные */
	private $_props;

	/** @var \PDO соединение с БД */
	private $_db;

	/** @var string название контроллера */
	private $_controller;

	/** @var string название действия */
	private $_action;

	/**
	 * Зарегистрировать переменную
	 * @param string $key
	 * @param mixed $val
	 */
	public function __set($key, $val)
	{
		$this->_props[$key] = $val;
	}

	/**
	 * Получить переменную, null если не существует
	 * @param string $key
	 * @return type
	 */
	public function __get($key)
	{
		return ( isset($this->_props[$key]) ) ? $this->_props[$key] : null;
	}

	/**
	 * Зарегистрировать соединение с БД
	 * @param \PDO $db
	 */
	public function setConnectionDB($db)
	{
		$this->_db = $db;
	}

	/**
	 * Получить соединение с БД
	 * @return \PDO
	 */
	public function getConnectionDB()
	{
		return $this->_db;
	}

	/**
	 * Зарегистрировать контроллер
	 * @param string $controller
	 */
	public function setController($controller)
	{
		$this->_controller = $controller;
	}

	/**
	 * Получить контроллер
	 * @return string
	 */
	public function getController()
	{
		return $this->_controller;
	}
	
	/**
	 * Зарегистрировать контроллер
	 * @param string $action
	 */
	public function setAction($action)
	{
		$this->_action = $action;
	}

	/**
	 * Получить контроллер
	 * @return string
	 */
	public function getAction()
	{
		return $this->_action;
	}

	/**
	 * Проверка, является ли запрос post
	 * @return boolean
	 */
	public function isPost()
	{
		return ( $_SERVER['REQUEST_METHOD'] == self::METHOD_POST ) ? true : false;
	}

	/**
	 * Проверка, является ли запрос ajax
	 * @return boolean
	 */
	public function isAjax()
	{
		return ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == self::AJAX_REQUEST ) ? true : false;
	}
}
