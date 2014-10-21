<?php

header( 'HTTP/1.1 503 Service Temporarily Unavailable' );
header( 'Status: 503 Service Temporarily Unavailable' );

?>
<!DOCTYPE html>

<html>
<head>
	<meta charset="utf-8" />
	<title>aestas3 error: no database connection</title>
	<style>
		html {
			background-color: #303030;
			color: #ffffff;
			font-family: "Open Sans", "DejaVu Sans", Arial, sans-serif;
			font-size: 18px;
		}
		body {
			padding: 40px 20px 20px 60px;
		}
		h1 {
			font-size: 32px;
			font-weight: bold;
			margin-bottom: 16px;
		}
	</style>
</head>
<body>

<h1>Database error</h1>

<?php ae_Log::printAll() ?>

</body>
</html>