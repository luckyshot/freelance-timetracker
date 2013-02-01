$(document).ready(function(){
	
	t.run = function() {
		$( "#table input[type=checkbox]" ).each(function(index){
			var tr = $(this).closest('tr');

			if ($(this).is(":checked")) {	
				var total = tr.find('.total'),
					rate = tr.find('.rate'),
					timed = tr.find('.timed');

				total.data('value', total.data('value') + (rate.val()/3600) );
				total.val( t.config.currency+total.data('value').toFixed(2) );
				timed.data('value', parseInt(timed.data('value')) + 1 );
				timed.val( t.niceTime(timed.data('value')) );

				tr.addClass('success');
			}else{
				tr.removeClass('success');
			}
		});

		// Update title and save countdown
		if ($('.timer:checked').length>0) {
			document.title = '('+$('.timer:checked').length+') Timetracker';
			t.config.savenext = t.config.savenext - 1000;
		}else{
			document.title = 'Timetracker';
		}
		// Saving time?
		if (t.config.savenext <= 0) {
			t.config.savenext = t.config.savedefault;
			t.save();
		}

	};

	t.save = function() {

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

	};

	t.load = function() {
		jsondata = t.config.initialdata;
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
					'<td><input class="total input-mini" data-value="'+obj.total+'" type="text" placeholder="'+t.config.currency+obj.total.toFixed(2)+'" value="'+t.config.currency+obj.total.toFixed(2)+'"></td>' +
					'<td><input class="desc" type="text" placeholder="Notes" value="'+obj.desc+'"></td>' +
					'<td><input class="timed input-mini" type="text" placeholder="0:00:00" data-value="'+obj.timed+'" value="'+t.niceTime(obj.timed)+'"></td>' +
					'<td><button class="delete btn btn-danger btn-small" title="Clear task"><i class="icon-remove icon-white"></i></button></td>' +
				'</tr>';
		};
		//console.log(buffer);
		$('#table tbody').html(buffer);

	};

	t.niceTime = function(s) {
		if (!s) {s=0;}
		hours = parseInt( s / 3600 ) % 24;
		minutes = parseInt( s / 60 ) % 60;
		seconds = s % 60;

		return (hours < 10 ? "0" + hours : hours) + ":" + (minutes < 10 ? "0" + minutes : minutes) + ":" + (seconds  < 10 ? "0" + seconds.toFixed(0) : seconds.toFixed(0));
	};

	t.generateColor = function(string) {
		string += 'makeitlongerjustincase';
		var hash = '',
			curchar;
		for (var i = 0; i < string.length; i++) {
			curchar = (string.toLowerCase().charCodeAt(i)-97) /25*255;
			curchar = parseInt(curchar.toFixed(0), 10).toString(16);
			hash += curchar;
		}
		return '#'+hash.substr(0,6);
	};


	/**
	 * Events
	 */

	$('#save').on('click', function() {
		t.config.savenext = 0;
	});

	$('button.delete').live('click', function() {
		if (confirm('Are you sure you want to delete this task?')) {
			var tr = $(this).closest('tr');
			tr.find('.task').val('');
			tr.find('.client').val('');
			tr.find('.total').data('value', 0);
			tr.find('.total').val(t.config.currency+'0.00');
			tr.find('.rate').val(t.config.rate);
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