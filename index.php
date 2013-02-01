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
   
 * Freelance Timetracker by Xavi Esteve
 * Released under Creative Commons Attribution-NonCommercial-ShareAlike
 * http://xaviesteve.com/
 * 
 * Version: 1.0.3
 */

/**
 * Default Settings
 * Set your default settings here
 */
$settings = array(
	'rate' => 35,
	'currency' => '£',
	'filename' => 'timetracker',
	'tasksno' => 10, // TODO: Number of tasks to show
	'saveinterval' => 10 // save every N seconds
);

/////////////// Save file ///////////////
if (isset($_GET['action'])) {
	if ($_GET['action']=='save' && (strlen($_POST['json'])>3)) {
		$filename = $settings['filename'].".json";
		$filehandle = fopen($filename, 'w') or die("Error: Can't create or save files");
		$fwrite = fwrite($filehandle, stripslashes($_POST['json']));
		fclose($filehandle);
	}
	echo $fwrite;
	die();
}

$data = '';
if (file_exists($settings['filename'].".json")) {
	$data = file_get_contents($settings['filename'].".json");
}
if (strlen($data)<3) {
	$data = '{"0":{"date":"","client":"","task":"","rate":'.$settings['rate'].',"total":0,"desc":"","timed":""},"1":{"date":"","client":"","task":"","rate":'.number_format($settings['rate'], 2).',"total":0,"desc":"","timed":""},"2":{"date":"","client":"","task":"","rate":'.number_format($settings['rate'], 2).',"total":0,"desc":"","timed":""},"3":{"date":"","client":"","task":"","rate":'.number_format($settings['rate'], 2).',"total":0,"desc":"","timed":""},"4":{"date":"","client":"","task":"","rate":'.number_format($settings['rate'], 2).',"total":0,"desc":"","timed":""},"5":{"date":"","client":"","task":"","rate":'.number_format($settings['rate'], 2).',"total":0,"desc":"","timed":""},"6":{"date":"","client":"","task":"","rate":'.number_format($settings['rate'], 2).',"total":0,"desc":"","timed":""},"7":{"date":"","client":"","task":"","rate":'.number_format($settings['rate'], 2).',"total":0,"desc":"","timed":""},"8":{"date":"","client":"","task":"","rate":'.number_format($settings['rate'], 2).',"total":0,"desc":"","timed":""},"9":{"date":"","client":"","task":"","rate":'.number_format($settings['rate'], 2).',"total":0,"desc":"","timed":""}}';
}

?><!doctype html>
<head>
	<title>Timetracker</title>
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

				<p><small>&copy; 2011-<?=date('Y')?> <a href="http://xaviesteve.com/">Xavi Esteve</a></small></p>

			</div><!--span12-->
		</div><!--row-->
	</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		
		var t = {
			config: {
				currency: '<?=$settings['currency']?>',
				rate: '<?=number_format($settings['rate'], 2)?>',
				savenext: <?=$settings['saveinterval']*1000?>,
				savedefault: <?=$settings['saveinterval']*1000?>
			},



			run: function() {
				$( "#table input[type=checkbox]" ).each(function(index){
					var tr = $(this).closest('tr');

					if ($(this).is(":checked")) {	
						var total = tr.find('.total');
						var rate = tr.find('.rate');
						var timed = tr.find('.timed');
						total.data('value', total.data('value') + (rate.val()/3600) );
						total.val( t.config.currency+total.data('value').toFixed(2) );
						timed.data('value', timed.data('value') + 1 );
						timed.val( t.niceTime(timed.data('value')) );

						tr.addClass('success');
					}else{
						tr.removeClass('success');
					}
				});

				if ($('.timer:checked').length>0) {
					document.title = '('+$('.timer:checked').length+') Timetracker';
					t.config.savenext = t.config.savenext - 1000;
				}else{
					document.title = 'Timetracker';
				}
				if (t.config.savenext <= 0) {
					t.config.savenext = t.config.savedefault;
					t.save();
				}

			}, // run

			save: function() {

				var data = {};

				$( "#table tr.taskrow" ).each(function(index){
					var tr = $(this);
					var row = {
						timer: tr.find('.timer').checked,
						date: tr.find('.date').val(),
						client: tr.find('.client').val(),
						task: tr.find('.task').val(),
						rate: tr.find('.rate').val(),
						total: tr.find('.total').data('value'),
						desc: tr.find('.desc').val(),
						timed: tr.find('.timed').data('value')
					};

					data[tr.data('id')] = row;
				});

			
				var jsondata = JSON.stringify(data);

				$.ajax({
					type: "POST",
					url: "?action=save",
					data: { json: jsondata }
				})
				.done(function( msg ) {
					console.log(msg);
				});

			}, // save

			load: function() {
				jsondata = '<?=$data?>';
				data = JSON.parse(jsondata);

				$('#table tbody').html('');
				var buffer = '<!--start-->';
				//console.log(data);
				for (var key in data) {
					var obj = data[key];
					buffer += 
						'<tr id="task-'+key+'" data-id="'+key+'" class="taskrow">' +
							'<td><input class="timer" type="checkbox"></td>' +
							'<td><input class="date input-mini" type="text" placeholder="Date" value="'+obj.date+'"></td>' +
							'<td><input class="client input-mini" type="text" placeholder="Client" value="'+obj.client+'" style="color:'+t.generateColor( obj.client )+'"></td>' +
							'<td><input class="task input-large" type="text" placeholder="Task" value="'+obj.task+'"></td>' +
							'<td><input class="rate input-mini" type="text" placeholder="'+obj.rate+'" value="'+obj.rate+'"></td>' +
							'<td><input class="total input-mini" data-value="'+obj.total+'" type="text" placeholder="<?=$settings['currency']?>'+obj.total.toFixed(2)+'" value="<?=$settings['currency']?>'+obj.total.toFixed(2)+'"></td>' +
							'<td><input class="desc" type="text" placeholder="Notes" value="'+obj.desc+'"></td>' +
							'<td><input class="timed input-mini" type="text" placeholder="0:00:00" data-value="'+obj.timed+'" value="'+t.niceTime(obj.timed)+'"></td>' +
							'<td><button class="delete btn btn-danger btn-small" title="Clear task"><i class="icon-remove icon-white"></i></button></td>' +
						'</tr>';
				};
				//console.log(buffer);
				$('#table tbody').html(buffer);

			},

			niceTime: function(s) {
				if (!s) {s=0;}
				hours = parseInt( s / 3600 ) % 24;
				minutes = parseInt( s / 60 ) % 60;
				seconds = s % 60;

				return (hours < 10 ? "0" + hours : hours) + ":" + (minutes < 10 ? "0" + minutes : minutes) + ":" + (seconds  < 10 ? "0" + seconds.toFixed(0) : seconds.toFixed(0));
			},

			generateColor: function(string) {
				string += 'makeitlongerjustincase';
				var hash = '',
					curchar;
				for (var i = 0; i < string.length; i++) {
					curchar = (string.toLowerCase().charCodeAt(i)-97) /25*255;
					curchar = parseInt(curchar.toFixed(0), 10).toString(16);
					hash += curchar;
				}
				return '#'+hash.substr(0,6);
			}

		}; // t

		$('#save').on('click', function() {
			t.config.savenext = 0;
		});

		$('button.delete').live('click', function() {
			if (confirm('Are you sure you want to delete this task?')) {
				var tr = $(this).closest('tr');
				tr.find('.task').val('');
				tr.find('.client').val('');
				tr.find('.total').data('value', 0);
				tr.find('.total').val('<?=$settings['currency']?>0.00');
				tr.find('.rate').val('<?=number_format($settings['rate'], 2)?>');
				tr.find('.desc').val('');
				tr.find('.timed').data('value', 0);
				tr.find('.timed').val('00:00:00');
			}
			t.config.savenext = 0;
		});

		// Update time manually
		$(document.body).on('keyup', 'input.timed', function() {
			var secs = $(this).val().split(':');
			$(this).data('value', parseInt(secs[0])*3600+parseInt(secs[1])*60+parseInt(secs[2]));
			t.config.savenext = 0;
		});

		// Update total manually
		$(document.body).on('keyup', 'input.total', function() {
			$(this).data('value', parseFloat($(this).val().replace(/[^0-9\.]/g, '')) );
			t.config.savenext = 0;
		});

		// Change color of Client
		$(document.body).on('input paste', 'input.client', function() {
			$(this).css('color', t.generateColor( $(this).val() ) );
			t.config.savenext = 0;
		});

		// Trigger saving when editing other fields too
		$(document.body).on('keyup', 'input.date,input.task,input.rate,input.desc', function() {
			t.config.savenext = 0;
		});

		setInterval(function(){t.run();}, 1000);

		t.load();

	});
</script>




</body>
</html>