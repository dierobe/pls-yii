<?php
/**
 * @var HelpController     $this
 * @var SimpleXMLElement[] $updates
 */

$this->pageTitle = Yii::app()->name . ' - ' . Yii::t('pls', 'Latest Updates');
$this->breadcrumbs = [
	Yii::t('pls', 'Latest Updates'),
];
?>
<h1><?= Yii::t('pls', 'Latest Updates') ?></h1>

<div class="row">
	<?php
	if (!empty($items)) {
		foreach ($items as $item) {
			?>
			<div class="col-md-12 update">
				<h3><a href="<?= $item->link ?>" target="_blank"><?= $item->title ?></a></h3>
				<p><?= $item->description ?></p>
				<p><?= date('m/d/Y', (int) $item->timestamp); ?></p>
			</div>
			<?php
		}
	} else {
		?>
		<?= Yii::t('pls', 'No updates are available at this time.') ?>
		<?php
	}
	?>
</div>