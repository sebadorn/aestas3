<?php

$search = new ae_Search();
$search->search( $_GET['search'] );

?>

<?php while( $s = $search->next() ): ?>



<?php endwhile ?>