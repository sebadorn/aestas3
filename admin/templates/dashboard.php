<?php
	$data = array(
		'aestas' => '<td>' . AE_VERSION . '</td>',
		'PHP' => '<td>' . phpversion() . '</td>',
		'MySQL' => '<td>' . ae_Database::serverVersion() . '</td>',
		'mod_rewrite' => apache_get_modules( 'mod_rewrite' )
		                 ? '<td class="cell-okay">enabled</td>'
		                 : '<td class="cell-warning">disabled</td>',
		'upload_max_filesize'=> '<td>' . ini_get( 'upload_max_filesize' ) . '</td>'
	);

	$phpVersion = explode( '.', phpversion() );

	// Evil features, that have been removed since PHP 5.4
	if( $phpVersion[0] <= 5 && $phpVersion[1] <= 3 ) {
		$data['Magic Quotes'] = get_magic_quotes_runtime()
		                      ? '<td class="cell-danger">enabled</td>'
		                      : '<td class="cell-okay">disabled</td>';
		$data['register_globals'] = ini_get( 'register_globals' )
		                          ? '<td class="cell-danger">enabled</td>'
		                          : '<td class="cell-okay">disabled</td>';
	}
?>
<h1>Dashboard</h1>

<table class="table-system">
	<?php foreach( $data as $key => $value ): ?>
	<tr>
		<th><?php echo $key ?></th>
		<?php echo $value ?>
	</tr>
	<?php endforeach ?>
</table>