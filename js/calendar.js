var calendar;
var calendartbl;
var month;
var year;

var monthnames = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];

$(document).keyup(function(e){
    if (e.keyCode == 37) { 
       prevmonth();
       return false;
    }
    if (e.keyCode == 39) { 
       nextmonth();
       return false;
    }
});

function nextmonth() {
	if(month >= 11) {
		month = 1;
		year++;
	} else {
		month++;
	}
	refresh();
}

function prevmonth() {
	if(month <= 1) {
		month = 11;
		year--;
	} else {
		month--;
	}
	refresh();
}

function generatecalendar() {
	calendartbl = $('<table>').attr({
		'class' : 'calendar',
		'border' : '1'
	});
	calendar = new Array();
	for(var i = 0; i < 5; i++) {
		var tmpa = new Array();
		var tmp = $('<tr>').appendTo(calendartbl);
		for(var j = 0; j < 7; j++) {
			tmpa.push($('<td>').appendTo(tmp));
		};
		calendar.push(tmpa);
	};
}

function initcal() {
	var today = new Date();
	generatecalendar();
	month = today.getMonth();
	year = today.getFullYear();
	preencher_cal(month, year);
	var cs = $('<div>').attr({
		'id' : 'calendar_space2'
	}).appendTo('#page');
	refresh();
}

function refresh() {
	preencher_cal(month, year);
	var cs = $('#calendar_space2');
	cs.empty();
	var move = $('<div>').attr({
		'id' : 'calendar_bar'
	}).appendTo(cs);
	var prev = $('<span>').html('<').click(prevmonth).appendTo(move);
	var info = $('<span>').html(' ' + monthnames[month] + ' de ' + year + ' ').appendTo(move);
	var next = $('<span>').html('>').click(nextmonth).appendTo(move);
	var info_g = $('<span>').attr("id", "ocfg").css("background-color", "yellow").html('      A obter dados do google... ').click(nextmonth).appendTo(move);
	calendartbl.appendTo(cs);
	$.ajax({
		url : 'API/utilizadores/get_google_events.php',
		dataType : 'json',
		data : {
			'month' : month + 1,
			'year' : year
		},
		type : 'POST'

	}).done(function(msg) {

		if($.isArray(msg)) {
			$('#ocfg').remove();
			var date = new Date(year, month, 1);
			var semana = date.getDay();
			var dia = 1;
			daysinmonth = 32 - new Date(year, month, 32).getDate()
			for(var i = 0; i < 5; i++) {
				for(var j = 0; j < 7; j++) {
					if(semana <= 0 && dia <= daysinmonth) {

						if(msg[dia].length > 0) {
							var lodo="";
							for(var u=0,v=msg[dia].length; u<v; u++){
							  lodo+=msg[dia][u]['name'];
							};
							$(calendar[i][j]).attr("title",lodo);
							$(calendar[i][j]).css("background-color", "red");
						} else {
							$(calendar[i][j]).removeAttr("title");
							$(calendar[i][j]).removeAttr("style");
						}
						dia++;
					} else {
						$(calendar[i][j]).removeAttr("title");
						$(calendar[i][j]).removeAttr("style");
					}
					semana--;
				};

			};
		}else{
			$('#ocfg').html(msg);
		}
	});
}

function preencher_cal(month, year) {
	var date = new Date(year, month, 1);
	var semana = date.getDay();
	var dia = 1;
	daysinmonth = 32 - new Date(year, month, 32).getDate()
	for(var i = 0; i < 5; i++) {
		for(var j = 0; j < 7; j++) {
			if(semana <= 0 && dia <= daysinmonth) {
				$(calendar[i][j]).html(dia).removeAttr("style");
				$(calendar[i][j]).removeAttr("title");
				dia++;
			} else {
				$(calendar[i][j]).html('').removeAttr("style");
				$(calendar[i][j]).removeAttr("title");
			}
			semana--;
		};

	};
}