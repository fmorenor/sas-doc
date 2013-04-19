function gridComplete(level){
	$('#content'+level+' .grid-header').css('display', 'block');
	userData.level = level;
	$.throbber.hide();
	$('.modalLayer').remove();	
			
	userDataWindow.gridHeight[level] = $('#content'+level+' #myGrid'+level+' .slick-viewport').height();
	userDataWindow.windowResize[userData.level] = false;	
	
	// BOF Cargar Chart
	if(level <= 2){								   
	   $('#content'+level+'  #chart').load('view/chart'+level+'.php', function(responseText, textStatus, XMLHttpRequest){
			chart.redraw();
	   });
	}
	// EOF Cargar Chart
	
	// CARGAR TOOLBOX
	toolBoxLoad(level);	
}

function exportExcel(level) {
	userData.excelLevel = level;
	$('#content'+level+' #myGrid'+level).table2CSV();
}

function formatNumber(num) {
	num = num.toString().replace(/\$|\,/g,'');
	if(isNaN(num))
	num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10)
	cents = "0" + cents;
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
	num = num.substring(0,num.length-(4*i+3))+','+
	num.substring(num.length-(4*i+3));
	return (((sign)?'':'-') + num + '.' + cents);
}

function formatCurrency(num) {
	num = num.toString().replace(/\$|\,/g,'');
	if(isNaN(num))
	num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10)
	cents = "0" + cents;
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
	num = num.substring(0,num.length-(4*i+3))+','+
	num.substring(num.length-(4*i+3));
	return (((sign)?'':'-') + '$' + num + '.' + cents);
}