<?php foreach ($chat as $value): ?>
	<div class="comment">
		<div class="comment-name"><?= $value->name ?> (<?= ($value->is_confirm == 0) ? "Tidak bisa hadir" : (($value->is_confirm == 1) ? "Akan hadir" : "Mungkin Hadir") ; ?>)</div>
		<div class="comment-desc"><?= $value->chat ?></div>
	</div>
<?php endforeach ?>