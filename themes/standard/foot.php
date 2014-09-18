<?php

$memoryPeak = sprintf( '%.4f', memory_get_peak_usage() / 1024 / 1024 );
$memoryFinal = sprintf( '%.4f', memory_get_usage() / 1024 / 1024 );

$scriptTime = ae_Timer::stop( 'total' );

?>
<footer>
	<span class="stat script-time"><?php echo $scriptTime ?> seconds</span>
	<span class="stat memory-peak"><?php echo $memoryPeak ?> MB (peak)</span>
	<span class="stat memory-final"><?php echo $memoryFinal ?> MB (final)</span>
	<span class="stat db-queries"><?php echo ae_Database::getNumQueries() ?> DB queries</span>
	<?php ae_Log::printAll() ?>
</footer>
