<?php
namespace controllers;

use core\Controller;
use core\App;
use models\DataModel;

/**
 * Список
 */
class ResultController extends Controller
{
	/**
	 * Полный список
	 * @return string
	 */
	public function actionIndex()
	{
		$model = new DataModel();
		//@todo какая-то загадочная ошибка у PDO, если обращаться через статический метод
		// исправить
		$models = $model->findAll();
		
		return $this->render('index', [
			'models' => $models,
			'model' => $model,
		]);
	}

	/**
	 * Получение одной записи по аякс
	 * @throws \Exception
	 */
	public function actionView()
	{
		$id = ( isset($_GET['id']) ) ? (int)$_GET['id'] : null;

		if ( ! App::$registry->isAjax() || ! $id ) {
			throw new \Exception('ОЙ! Такой страницы нету.', 404);
		}

		$t = new DataModel();
		$model = $t->findOne($id);

		if ( ! $model ) {
			throw new \Exception('ОЙ! Такой страницы нету.', 404);
		}
		
		return $this->renderView('view', [
			'model' => $model
		]);
	}
}
