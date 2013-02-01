<?php
/*!
 * 
 ______             _                      
|  ____|           | |                     
| |__ _ __ ___  ___| | __ _ _ __   ___ ___ 
|  __| '__/ _ \/ _ \ |/ _` | '_ \ / __/ _ \
| |  | | |  __/  __/ | (_| | | | | (_|  __/
|_|  |_|  \___|\___|_|\__,_|_| |_|\___\___|
 _______ _                _                  _             
|__   __(_)              | |                | |            
   | |   _ _ __ ___   ___| |_ _ __ __ _  ___| | _____ _ __ 
   | |  | | '_ ` _ \ / _ \ __| '__/ _` |/ __| |/ / _ \ '__|
   | |  | | | | | | |  __/ |_| | | (_| | (__|   <  __/ |   
   |_|  |_|_| |_| |_|\___|\__|_|  \__,_|\___|_|\_\___|_|   
   
 * Freelance Timetracker by Xavi Esteve http://xaviesteve.com/
 * Released under Creative Commons Attribution-NonCommercial-ShareAlike
 * https://github.com/luckyshot/freelance-timetracker
 * 
 * Version: 1.0.4
 */

/**
 * Default Settings
 * Set your default settings here
 */
$settings = array(
	'rate' => 35, // default rate per hour
	'currency' => '£',
	'filename' => 'timetracker', // name of the database
	'tasksno' => 10, // TODO: Number of tasks to show
	'saveinterval' => 10 // save every N seconds
);

/**
 * Save database
 */
if (isset($_GET['action'])) {
	if ($_GET['action']=='save' && (strlen($_POST['json'])>2)) {
		$filename = $settings['filename'].".json";
		$filehandle = fopen($filename, 'w') or die("Error: Can't create or save files, please modify folder permissions");
		$fwrite = fwrite($filehandle, stripslashes($_POST['json']));
		fclose($filehandle);
		echo $fwrite;
		die();
	}
}

/**
 * Read database
 */
$data = '';
if (file_exists($settings['filename'].".json")) {
	$data = file_get_contents($settings['filename'].".json");
}

// If no database then generate a blank one
if (strlen($data)<3) {
	$data = '{';
	for ($i=0; $i < $settings['tasksno']; $i++) { 
		$data .= '"'.$i.'":{"date":"","client":"","task":"","rate":'.number_format($settings['rate'], 2).',"total":0,"desc":"","timed":""}';
		if ($i < $settings['tasksno']-1) {$data .= ',';}
	}
	$data .= '}';
}

/**
 * HTML
 */
?><!doctype html>
<head>
	<title>Freelance Timetracker</title>
	<meta charset="utf-8">
	<link href="bootstrap.min.css" rel="stylesheet">
	<style>
	body {padding-top:60px}
	input[type="text"],input[type="checkbox"]{margin-bottom:0}
	input[type=text]{background:transparent;border:none;box-shadow:none}
	input[type=text]:focus{background:#fff}
	input.client,
	input.desc{font-size:80%}
	input.total{font-weight:bold}
	td{transition: all 1s ease-in;-webkit-transition: all 1s ease-in;}
	</style>
</head>
<body>

	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="brand" href="?">Timetracker</a>
				<div class="nav-collapse collapse">
					<ul class="nav pull-right">
						<li>
							<form class="navbar-form">
								<button id="save" class="btn btn-success">Save now</a>
							</form>
						</li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
	</div>
	
	<div class="container">
		<div class="row">
			<div class="span12">
				
				<table id="table" class="table table-striped">
					<thead>
						<tr>
							<th></th>
							<th>Date</th>
							<th>Project</th>
							<th>Task</th>
							<th>Rate</th>
							<th>Total</th>
							<th>Notes</th>
							<th>Time</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>

				<hr>

				<p><small>Freelance Timetracker by <a href="http://xaviesteve.com/">Xavi Esteve</a></small></p>

			</div><!--span12-->
		</div><!--row-->
	</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
<script src="timetracker.js"></script>

</body>
</html>