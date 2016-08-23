<?php
namespace core;

use core\Error;
use core\Registry;

class App
{
	const CONTROLLER_PART_NAME = 'Controller';
	const ACTION_PART_NAME = 'action';
	const DEFAULT_CONTROLLER = 'site';
	const DEFAULT_ACTION = 'index';

	/** @var Registry  */
	public static $registry;

	/**
	 * 
	 */
	public function __construct()
	{
		App::$registry = new Registry();

		try {
			$this->_connectDB();
			$this->_handleRequest();
		}
		catch ( \Exception $exc ) {
			Error::show(500, 'ОЙ! Что-то пошло не так.');
		}
	}

	/**
	 * Передача управления контроллеру, если возможно
	 */
	public function run()
	{
		// если контроллер не указан, то используем по умолчанию
		if ( App::$registry->getController() === null ) {
			App::$registry->setController(self::DEFAULT_CONTROLLER);
		}
		
		$controller = ucfirst(App::$registry->getController()) . self::CONTROLLER_PART_NAME;
		
		$file = APP_DIR_CONTROLLERS .'/'. $controller .'.php';

		// файл не существует
		if ( ! file_exists($file) || ! is_file($file) ) {
			Error::show(404, 'ОЙ! Такой страницы нету.');
		}

		// если action не указан, то используем по умолчанию
		if ( App::$registry->getAction() === null ) {
			$action = App::$registry->setAction(self::DEFAULT_ACTION);
		}
		
		$action = self::ACTION_PART_NAME . ucfirst(App::$registry->getAction());

		try {
			// подключаем контроллер
			$controller = APP_REL_DIR_CONTROLLERS .'\\'. $controller;
			$controller_class = new $controller();
		}
		catch ( \Exception $exc ) {
			Error::show(404, 'ОЙ! Такой страницы нету.');
		}

		// действие не доступно
		if ( ! is_callable([$controller_class, $action]) ) {
			Error::show(404, 'ОЙ! Такой страницы нету.');
		}

		try {
			// поехали
			$content = $controller_class->$action();
		}
		catch ( \Exception $exc ) {
			Error::show(500, 'ОЙ! Что-то пошло не так.');
		}

		if ( $content !== null ) {
			echo $content;
		}
	}

	/**
	 * Соединяемся с БД
	 * @throws \Exception
	 */
	protected function _connectDB()
	{
		if ( ! defined('APP_PDO_DSN') || ! defined('APP_PDO_USERNAME') || ! defined('APP_PDO_PASSWORD') ) {
			throw new \Exception('Не заданы параметры для подключения к БД', 500);
		}

		App::$registry->setConnectionDB(new \PDO(APP_PDO_DSN, APP_PDO_USERNAME, APP_PDO_PASSWORD));
	}

	/**
	 * Разбор урл и установка параметров запроса
	 * @return boolean
	 * @throws \Exception
	 */
	protected function _handleRequest()
	{
		// убираем первый слеш и гет параметры
		$controller_action = trim(strtok($_SERVER["REQUEST_URI"], '?'), '/');

		// если пустой, или указан index.php, то используем по умолч. контроллер и дейстивие
		if ( empty($controller_action) || $controller_action == 'index.php' ) {
			return false;
		}

		$parts = explode('/', $controller_action);

		// здесь еще что-то есть, кроме контроллера и дейстивия
		//@todo улучшить, отдавать, что 404
		if ( count($parts) > 2 ) {
			throw new \Exception('ОЙ!', 500);
		}

		// контроллер точно есть
		App::$registry->setController($parts[0]);

		// а вот действие может и не быть
		if ( isset($parts[1]) ) {
			App::$registry->setAction($parts[1]);
		}
	}
}