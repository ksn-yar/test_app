<?php
namespace controllers;

use core\Controller;
use core\App;
use models\DataModel;

/**
 * Поиск
 */
class SiteController extends Controller
{
	/**
	 * Главная страница
	 * @return string
	 */
	public function actionIndex()
	{
		$types = DataModel::listTypes();
		$model = new DataModel();
		
		return $this->render('index', [
			'types' => $types,
			'model' => $model,
		]);
	}

	/**
	 * Поиск, только аякс и пост запрос
	 * @return mixed
	 */
	public function actionSearch()
	{
		if ( ! App::$registry->isAjax() || ! App::$registry->isPost() ) {
			throw new \Exception('ОЙ! Такой страницы нету.', 404);
		}

		// валидируем данные
		$errors = $this->validateSearch($_POST);

		// есть ошибки, отдать их
		if ( !empty($errors) ) {
			return json_encode(['status' => 'error', 'data' => $errors]);
		}

		// запрашиваем страницу по урл
		$html = $this->getElements($_POST['site']);

		// запрашиваем текст или элементы
		if ( $_POST['type_element'] == DataModel::TYPE_TEXT ) {
			$elements = $this->findText($html, $_POST['text']);
		} else {
			$elements = $this->findElements($html, $_POST['type_element']);
		}

		// сохраняем
		$model = new DataModel();
		$result_save = $model->saveAfterSearch($_POST['type_element'], $_POST['site'], $elements);

		$result = ( $result_save ) ? ['status' => 'fail', 'msg' => 'ОЙ! Произошла ошибка при сохранении'] : ['status' => 'success'];

		return json_encode($result);
	}

	/**
	 * Валидация при поиске
	 * @param array $params
	 * @return array
	 * @todo надо перенести валидатор и сделать универсальным
	 */
	public function validateSearch($params)
	{
		$errors = [];

		$model = new DataModel();

		if ( ! isset($params['site']) || $params['site'] == '' ) {
			$errors[] = $model->getAttributeLabel('site') .': Необходимо заполнить.';
		} elseif ( ! preg_match('/^(http|https):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(?::\d{1,5})?(?:$|[?\/#])/i', $params['site']) ) {
			$errors[] = $model->getAttributeLabel('site') .': Значение не является правильным URL.';
		}
		
		if ( ! isset($params['type_element']) || $params['type_element'] == '' ) {
			$errors[] = $model->getAttributeLabel('type_element') .': Необходимо заполнить.';
		} elseif ( ! array_key_exists($params['type_element'], DataModel::listTypes()) ) {
			$errors[] = $model->getAttributeLabel('type_element') .': Значение не является правильным.';
		}
		
		if ( isset($params['type_element']) && $params['type_element'] == DataModel::TYPE_TEXT && (! isset($params['text']) || $params['text'] == '') ) {
			$errors[] = $model->getAttributeLabel('text') .': Необходимо заполнить.';
		}

		return $errors;
	}

	/**
	 * Курлом берем html
	 * @param string $site
	 * @return string
	 * @todo надо перенести и сделать универсальным
	 */
	public function getElements($site)
	{
		$curl = curl_init( $site );

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$response = curl_exec($curl);
		curl_close($curl);

		return $response;
	}

	/**
	 * Поиск текста, вернет массив с найденными
	 * @param string $html
	 * @param string $text
	 * @return array
	 * @todo надо перенести валидатор и сделать универсальным
	 */
	public function findText($html, $text)
	{
		// удаляем теги и еще повторяющиеся whitespace символы
		$subject = preg_replace('/\s+/u', ' ', strip_tags($html));
		// теперь ищем текст
		preg_match_all("/{$text}/ui", $subject, $matches);

		return ( isset($matches[0]) ) ? $matches[0] : [];
	}

	/**
	 * Поиск элементов, вернет массив с найденными
	 * @param string $html
	 * @param string $element
	 * @return array
	 * @todo надо перенести валидатор и сделать универсальным
	 */
	public function findElements($html, $element)
	{
		if ( $element == DataModel::TYPE_IMG ) {
			$pattern = '<img\s[^>]+>';
		} elseif ( $element == DataModel::TYPE_LINK ) {
			$pattern = '<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>';
		} else {
			return [];
		}

		preg_match_all("/{$pattern}/i", $html, $matches);
		return ( isset($matches[0]) ) ? $matches[0] : [];
	}
}
