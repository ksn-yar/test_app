<?php
use models\DataModel;

/* @var $model DataModel */
?>

<ol>
	<?php foreach ( $model->data as $data ): ?>
	<li><?= htmlentities($data); ?></li>
	<?php endforeach; ?>
</ol>