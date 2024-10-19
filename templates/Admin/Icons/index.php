<?php
/**
 * @var \App\View\AppView $this
 * @var array<string, string> $icons
 * @var array<string, string> $map
 */
?>
<div class="row">
	<div class="col-md-12">

	<h1>Icons</h1>

	<p>Overview of your defined (non-namespaced) icons for Templating.Icon helper.</p>

		<ul>
			<li><?php echo $this->Html->link('Conflicts', ['action' => 'conflicts']); ?></li>
			<li><?php echo $this->Html->link('Full icon sets', ['action' => 'sets']); ?></li>
		</ul>

	<?php if ($map) { ?>
	<h2>Custom Map (Icon.map config)</h2>

		<div class="row">
			<?php foreach ($map as $name => $icon) { ?>
				<div class="col-lg-3 col-md-4 col-sm-6 card">
					<div class="card-body">
						<span class="float-right pull-right">( <?php echo $icon?> )</span>
						<?php echo $this->Icon->render($name); ?>
						<br>
						<code><?php echo h($name); ?></code>
					</div>
				</div>
			<?php } ?>
		</div>

	<?php } ?>

	<h2>Icons</h2>
	<p><?php echo count($icons); ?> icons:</p>

	<div class="row">
		<?php foreach ($icons as $name => $iconSet) { ?>
			<div class="col-lg-3 col-md-4 col-sm-6 card">
				<div class="card-body">
					<span class="float-right pull-right">( <?php echo $iconSet . ':' . $name?> )</span>
					<?php echo $this->Icon->render($name); ?>
					<br>
					<code><?php echo h($name); ?></code>
				</div>
			</div>
		<?php } ?>
	</div>

	</div>
</div>
