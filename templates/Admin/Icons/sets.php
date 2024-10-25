<?php
/**
 * @var \App\View\AppView $this
 * @var array $icons
 * @var int $count
 */
?>
<div class="row">
	<div class="col-md-12">

	<h1>Icons</h1>

	<p>Full overview of your defined icon-sets for Templating.Icon helper.</p>

	<h2>Icon Sets</h2>
	<p><?php echo $count; ?> icons in <?php echo count($icons); ?> set(s):</p>
	<ul>
		<?php foreach ($icons as $name => $iconSet) { ?>
		<li>
			<a href="#<?php echo h(strtolower($name)); ?>"><?php echo h(ucfirst($name)); ?></a>
		</li>
		<?php } ?>
	</ul>

<?php foreach ($icons as $name => $iconSet) { ?>
	<?php
	ksort($iconSet);
	?>
	<h3 id="<?php echo h(strtolower($name)); ?>"><?php echo h(ucfirst($name)); ?></h3>
	<div class="row" style="margin-bottom: 16px;">
		<?php foreach ($iconSet as $icon) { ?>
			<div class="col-lg-3 col-md-4 col-sm-6 card">
				<div class="card-body">
					<?php echo $this->Icon->render($name . ':' . $icon); ?>
					<br>
					<code><?php echo h($name . ':' . $icon); ?></code>
				</div>
			</div>
		<?php } ?>
	</div>
<?php } ?>

	</div>
</div>
