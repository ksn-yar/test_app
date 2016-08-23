<?php
namespace core;

use core\App;

/**
 * Контроллер-компонент, от которого все наследуются
 */
class Controller
{
	public $layout = 'main';
	public $layout_dir = 'layouts';

	/**
	 * Получить название главного шаблона
	 * @return string
	 */
	public function getLayout()
	{
		return $this->layout;
	}

	/**
	 * Получить название папки главных шаблонов
	 * @return string
	 */
	public function getLayoutDir()
	{
		return $this->layout_dir;
	}

	/**
	 * Рендер подшаблона и шаблона
	 * @param type $view
	 * @param type $params
	 * @return string
	 */
	public function render($view, $params = [])
	{
		$content = $this->renderView($view, $params);
		return $this->renderContent($content);
	}

	/**
	 * Рендер подшаблона
	 * @param string $view название подшаблона
	 * @param array $params массив параметров, которые присутствуют
	 * @return string
	 */
	public function renderView($view, $params)
	{
		$file = APP_DIR_VIEWS .'/'. App::$registry->getController() .'/'. $view .'.php';
		return $this->renderPhpFile($file, $params);
	}

	/**
	 * Рендер главного шаблона
	 * @param string $content подшаблон
	 * @return string
	 */
	public function renderContent($content)
	{
		$file = APP_DIR_VIEWS .'/'. $this->getLayoutDir() .'/'. $this->getLayout() .'.php';
		return $this->renderPhpFile($file, ['content' => $content]);
	}

	/**
	 * Рендер самого php файла
	 * @param string $file абс. путь до файла
	 * @param array $params массив параметров, которые присутствуют в файле
	 * @return string
	 */
	public function renderPhpFile($file, $params = [])
	{
		ob_start();
        ob_implicit_flush(false);
        extract($params, EXTR_OVERWRITE);
        require($file);

        return ob_get_clean();
	}
}
