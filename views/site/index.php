<?php
use models\DataModel;
/* @var $types array */
/* @var $model \models\DataModel */
?>
<h1>Поиск</h1>

<form method="post" action="/site/search" id="js_id_search_form">
	<div class="error-summary" style="display: none;"></div>
	<div class="alert alert-success" style="display: none;">Готово</div>

	<div class="row">
		<div class="col-md-3">
			<div class="form-group">
				<label for="site" class="control-label"><?= $model->getAttributeLabel('site'); ?></label>
				<input type="text" maxlength="255" name="site" class="form-control" id="site">
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="type_element" class="control-label"><?= $model->getAttributeLabel('type_element'); ?></label>
				<select name="type_element" class="form-control" id="type_element" data-spec_type="<?= DataModel::TYPE_TEXT; ?>">
						<option value=""></option>
					<?php foreach ( $types as $key => $value ): ?>
						<option value="<?= $key; ?>"><?= $value; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div id="js_id_text_place" class="form-group" style="display: none;">
				<label for="text" class="control-label"><?= $model->getAttributeLabel('text'); ?></label>
				<input type="text" name="text" class="form-control" id="text">
			</div>
		</div>
	</div>
	<div class="form-group">
		<button class="btn btn-success" type="submit">Начать поиск</button>
	</div>
</form>