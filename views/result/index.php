<?php
use models\DataModel;

/* @var $models DataModel[] */
/* @var $model DataModel */
?>
<h1>Результат</h1>

<div class="row">
	<div class="col-md-12">
		<table class="table table-striped table-bordered">
			<tr>
				<th><?= $model->getAttributeLabel('id'); ?></th>
				<th><?= $model->getAttributeLabel('site'); ?></th>
				<th><?= $model->getAttributeLabel('type_element'); ?></th>
				<th><?= $model->getAttributeLabel('total_elements'); ?></th>
			</tr>
			<?php if ( !empty($models) ): ?>
				<?php foreach ( $models as $m ): ?>
			<tr>
				<td><?= $m->id; ?></td>
				<td><button type="button" class="btn btn-link js_class_show_elements" data-id="<?= $m->id; ?>"><?= $m->site; ?></button></td>
				<td><?= DataModel::getTypeLabel($m->type_element); ?></td>
				<td><?= $m->total_elements; ?></td>
			</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan="4">Нет записей</td>
				</tr>
			<?php endif; ?>
		</table>
	</div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="js_id_result_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Элементы</h4>
      </div>
	<div class="modal-body" id="js_id_result_modal_body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->