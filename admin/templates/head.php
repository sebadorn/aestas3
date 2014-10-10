<head>
	<meta charset="utf-8" />
	<title>aestas › <?php echo $data->title ?></title>
	<link rel="stylesheet" href="css/style.css" />
	<link rel="stylesheet" href="css/<?php echo $data->css ?>.css" />
<?php if( $data->js !== FALSE ): ?>
	<script src="js/<?php echo $data->js ?>.js"></script>
<?php endif ?>
</head>
