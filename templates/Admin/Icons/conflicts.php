<?php
/**
 * @var \App\View\AppView $this
 * @var array $conflicting
 */
?>
<div class="row">
	<div class="col-md-12">

		<h1>Icons</h1>

		<h2>Conflicting icons</h2>

		<p>Icons that are defined with the same name through different sets.</p>

		<div class="row">
			<?php foreach ($conflicting as $name => $sets) { ?>
				<div class="col-lg-3 col-md-4 col-sm-6 card">
					<div class="card-body">
						<?php
						$set = array_shift($sets);
						?>
						<span class="float-right pull-right">( <?php echo $name?> )</span>
						<?php echo $this->Icon->render($set . ':' . $name); ?>
						<br>
						<code><?php echo h($set . ':' . $name); ?></code>

						<div style="padding-top: 10px; padding-bottom: 10px">vs</div>
						<?php
						foreach ($sets as $set) {
							echo $this->Icon->render($set . ':' . $name) . '<br><code>' . h($set . ':' . $name) . '</code>';
						} ?>
					</div>
				</div>
			<?php } ?>
		</div>

	</div>
</div>
