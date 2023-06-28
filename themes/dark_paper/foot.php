<?php

$memoryPeak = sprintf( '%.4f', memory_get_peak_usage() / 1024 / 1024 );
$memoryFinal = sprintf( '%.4f', memory_get_usage() / 1024 / 1024 );

$scriptTime = ae_Timer::stop( 'total' );

$filter = array(
	'ORDER BY' => 'ca_title ASC'
);
$caList = new ae_CategoryList( $filter, array(), FALSE );

?>
<footer class="main-footer">

	<div>
		<h6>Kategorien</h6>
		<ul class="categories">
		<?php while( $ca = $caList->next() ): ?>
			<li><a href="<?php echo $ca->getLink() ?>"><?php echo $ca->getTitle() ?></a></li>
		<?php endwhile ?>
		</ul>
	</div>

	<div>
		<h6>Seiten</h6>
		<ul class="pages">
			<li><a href="<?php echo URL ?>ueber">Über</a></li>
			<li><a href="<?php echo URL ?>datenschutzerklaerung">Datenschutz</a></li>
			<li><a href="<?php echo URL ?>impressum">Impressum</a></li>
		</ul>
	</div>

	<div class="benchmark">
		<h6>Benchmark</h6>
		<ul class="stats">
			<li class="stat script-time">
				<span>Sec.</span><code><?php echo $scriptTime ?></code>
			</li>
			<li class="stat memory-peak">
				<span>MB (peak)</span><code><?php echo $memoryPeak ?></code>
			</li>
			<li class="stat memory-final">
				<span>MB (final)</span><code><?php echo $memoryFinal ?></code>
			</li>
			<li class="stat db-queries">
				<span>DB queries</span><code><?php echo ae_Database::getNumQueries() ?></code>
			</li>
		</ul>
	</div>

	<?php ae_Log::printAll() ?>

</footer>
