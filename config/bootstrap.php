<?php
/**
 * Глобальные настройки
 */

// подключение к БД
define('APP_PDO_DSN', 'mysql:host=localhost;dbname=app_test');
define('APP_PDO_USERNAME', 'root');
define('APP_PDO_PASSWORD', 'ttt');

// папки
// абс. путь к корню проекта
define('APP_DIR_ROOT', dirname(__DIR__));

// относительно корня
define('APP_REL_DIR_CORE', 'core');
define('APP_REL_DIR_CONTROLLERS', 'controllers');
define('APP_REL_DIR_MODELS', 'models');
define('APP_REL_DIR_VIEWS', 'views');
define('APP_REL_DIR_LIBS', 'libs');
define('APP_REL_DIR_WEB', 'public_html');

// абс. путь
define('APP_DIR_CORE', APP_DIR_ROOT .'/'. APP_REL_DIR_CORE);
define('APP_DIR_CONTROLLERS', APP_DIR_ROOT .'/'. APP_REL_DIR_CONTROLLERS);
define('APP_DIR_MODELS', APP_DIR_ROOT .'/'. APP_REL_DIR_MODELS);
define('APP_DIR_VIEWS', APP_DIR_ROOT .'/'. APP_REL_DIR_VIEWS);
define('APP_DIR_LIBS', APP_DIR_ROOT .'/'. APP_REL_DIR_LIBS);
define('APP_DIR_WEB', APP_DIR_ROOT .'/'. APP_REL_DIR_WEB);

// попытка подключить класс
spl_autoload_register(function ($class) {
	$file = str_replace('\\', '/', $class);
	$path = APP_DIR_ROOT .'/'. $file . '.php';
	require_once $path;
});
