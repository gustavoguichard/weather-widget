<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>The Weather</title>
	<link href="style.css" media="screen" rel="stylesheet" type="text/css" />
</head>
<body>
	<h4>The Weather</h4>
	<form id="cityform" method="POST" action="#">
		<input name="cityname" type="text" value="<?php echo $_POST["cityname"];?>" />
	</form>
	<section id="weathersec">
	<?php include_once('weather.php');
		getWeather(); ?>
	</section>
	<script src="js/modernizr.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/jquery-1.6.2.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/yqlgeo.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/weather.js" type="text/javascript" charset="utf-8"></script>
</body>
</html>