<script>
	var chart;	
	$(function () {   
		// Build the chart		        
		var options = {
			chart: {
				renderTo: 'chart-container',
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				height: 310,
				//backgroundColor: null,
			},
			title: {
				text: 'Porcentaje de los documentos según su estatus',
				style: {
					fontFamily: 'Raleway',
					color: '#000000'
				}
			},
			tooltip: {
				//pointFormat: '{series.name}: <b>{point.y}</b><br />Representa el <b>{point.percentage}%</b>',
				//percentageDecimals: 1
				formatter: function() {
							return this.point.name +'<b>: '+ this.point.y + "</b><br />Representa el <b>" + (this.percentage).toFixed(2) +' %</b>';
						 }		
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						color: '#333333',
						connectorColor: '#000000',
						formatter: function() {
							//return '<b>'+ this.point.name +'</b>: '+ (this.percentage).toFixed(2) +' %';
							var ret = (this.percentage).toFixed(2);
							ret = (ret > 10) ? ret + '%' : null;
							return ret;
						},
						distance: -28,
						style: {
							fontFamily: 'Raleway',
							fontSize: '14px',
						}
					},
					showInLegend: true,
					innerSize: '60%'
				}
			},
			legend: {
				align: 'right',
				verticalAlign: 'middle',
				layout: 'vertical',
				itemStyle: {
					fontFamily: 'Raleway',
					color: '#000000',
					fontSize: '14px',
					lineHeight: 50,
					padding: 10
				},
				itemMarginTop: 10,
				itemMarginBottom: 10,
				borderWidth: 0,
				symbolWidth: 30
			},
			credits:false,
			exporting:false,
			series: []
		};		
		options.series = [userDataL1.chart];
		options.colors = userDataL1.colors;
		
		// Radialize the colors		
		//options.colors = Highcharts.map(options.colors, function(color) {
		//    return {
		//        radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
		//        stops: [
		//            [0, color],
		//            [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
		//        ]
		//    };
		//});
		
		chart = new Highcharts.Chart(options);
    });
</script>
<div id="chart-container"></div>