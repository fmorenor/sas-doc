// BOF Datepicker
	$(document).ready(function() {
		
		// El container tiene una altura fija para convertirse en scroll
		$('#bitacoraGrid').height($(window).height() - 250);
		$('#bitacoraGrid').width($('#bitacoraWindow').width());
		
		//$('#bitacoraTable').load('model/components/bitacoraWindow.php?id_usuario='+userData.id_usuario+'&id_privilegios='+userData.id_privilegios);
		
		$.get('model/components/bitacoraWindow.php',{'id_usuario':userData.id_usuario,'id_privilegios':userData.id_privilegios}, function(data) {
			loadGridBitacora(data);
		});
		
		// Cerrar ventana		
		$('#closeBitacoraButton').bind("click", function(){
			$('#bitacoraWindow').slideUp(function(){
					if ($('#newDocument').length == 0) { // Ocultar el modal si no existe newDocument
						toggleModal();
					}
					$('#bitacoraWindow').remove();
					userData.bitacoraGridData = null;
			}); 
        });
		
	});
	
	/// BOF Slickgrid	
	var dataViewBitacora;
	var gridBitacora;
	var columnsBitacora = [
	  {id: 'id_usuario', name: 'ID Usuario', field : 'id_usuario',width : 2, selectable : false, sortable : true},
	  {id: 'usuario', name: 'Usuario que realizó la acción', field : 'usuario', selectable : false, sortable : true},
	  {id: 'id_documento', name: 'ID Documento afectado', field : 'id_documento', selectable : false, sortable : true},
	  {id: 'numero_documento', name: 'Documento afectado', field : 'numero_documento', selectable : false, sortable : true},
	  {id: 'evento', name: 'Evento', field : 'evento', selectable : false, sortable : true},
	  {id: 'objeto', name: 'Elementos', field : 'objeto', selectable : false, sortable : true},
	  {id: 'fecha_evento', name: 'Fecha', field : 'fecha_evento', selectable : false, sortable : true}
	];
	
	var options = {
		enableCellNavigation: false,
		enableColumnReorder: true,
		multiColumnSort: true,
		editable: false,
		rowHeight: 30,
	};
	
	function loadGridBitacora(data){
		userData.bitacoraGridData = data;
		
		/// BOF Slickgrid						
		var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();
		dataViewBitacora = new Slick.Data.DataView({
		  groupItemMetadataProvider: groupItemMetadataProvider,
		  inlineFilters: true
		});
		gridBitacora = new Slick.Grid("#bitacoraGrid", dataViewBitacora, columnsBitacora, options);
	  
		// register the group item metadata provider to add expand/collapse group handlers
		gridBitacora.registerPlugin(groupItemMetadataProvider);    
		gridBitacora.registerPlugin(new Slick.AutoTooltips());
		
		// wire up model events to drive the grid
		dataViewBitacora.onRowCountChanged.subscribe(function (e, args) {
		  gridBitacora.updateRowCount();
		  gridBitacora.autosizeColumns();
		  gridBitacora.render();
		});
	  
		dataViewBitacora.onRowsChanged.subscribe(function (e, args) {
		  gridBitacora.invalidateRows(args.rows);
		  gridBitacora.autosizeColumns();
		  gridBitacora.render();
		});
		
		gridBitacora.onSort.subscribe(function (e, args) {
			var cols = args.sortCols;
	  
			dataViewBitacora.sort(function (dataRow1, dataRow2) {
			 for (var i = 0, l = cols.length; i < l; i++) {
				var field = cols[i].sortCol.field;
				var sign = cols[i].sortAsc ? 1 : -1;
				var value1 = dataRow1[field].toString().toUpperCase();
				var value2 = dataRow2[field].toString().toUpperCase();
				
				var letter = /[A-E]/gi;
				var result1 = value1.match(letter);
				var result2 = value2.match(letter);						
				if (!result1) {
					value1 = (value1.replace(/[^0-9.]/g,'') * 100);
				}
				if (!result2) {
					value2 = (value2.replace(/[^0-9.]/g,'') * 100);
				}
				
				var result = (value1 == value2 ? 0 : (value1 > value2 ? 1 : -1)) * sign;
				if (result != 0) {
				  return result;
				}
			  }
			  return 0;
			});
			gridBitacora.invalidate();
			gridBitacora.render();
		});
	  
		// initialize the model after all the events have been hooked up
		dataViewBitacora.beginUpdate();
		dataViewBitacora.setItems(data.rows);
		dataViewBitacora.endUpdate();
	}
	
	function exportExcel() {
		$('#bitacoraGrid').table2CSV();
	}
	