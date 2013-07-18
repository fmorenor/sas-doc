// BOF Datepicker
	$(document).ready(function() {
		
		// El container tiene una altura fija para convertirse en scroll
		$('#bitacoraGrid').height($(window).height() - 250);
		$('#bitacoraGrid').width($('#bitacoraWindow').width());
		
		//$('#bitacoraTable').load('model/components/bitacoraWindow.php?id_usuario='+userData.id_usuario+'&id_privilegios='+userData.id_privilegios);
		
		$.get('model/components/bitacoraWindow.php',{'id_usuario':userData.id_usuario,'id_privilegios':userData.id_privilegios}, function(data) {
			loadGrid(data);
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
	var dataView;
	var grid;
	var columns = [
	  {id: 'id_usuario', name: 'ID Usuario', field : 'id_usuario',width : 2, selectable : false},
	  {id: 'usuario', name: 'Usuario que realizó la acción', field : 'usuario', selectable : false},
	  {id: 'id_documento', name: 'ID Documento afectado', field : 'id_documento', selectable : false},
	  {id: 'numero_documento', name: 'Documento afectado', field : 'numero_documento', selectable : false},
	  {id: 'evento', name: 'Evento', field : 'evento', selectable : false},
	  {id: 'objeto', name: 'Elementos', field : 'objeto', selectable : false},
	  {id: 'fecha_evento', name: 'Fecha', field : 'fecha_evento', selectable : false}
	];
	
	var options = {
	  enableCellNavigation: false,
	  enableColumnReorder: true,
	  editable: false,
	  rowHeight: 30,
	};
	
	function loadGrid(data){
		userData.bitacoraGridData = data;
		
		/// BOF Slickgrid						
		var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();
		dataView = new Slick.Data.DataView({
		  groupItemMetadataProvider: groupItemMetadataProvider,
		  inlineFilters: true
		});
		grid = new Slick.Grid("#bitacoraGrid", dataView, columns, options);
	  
		// register the group item metadata provider to add expand/collapse group handlers
		grid.registerPlugin(groupItemMetadataProvider);    
		grid.registerPlugin(new Slick.AutoTooltips());
		
		// wire up model events to drive the grid
		dataView.onRowCountChanged.subscribe(function (e, args) {
		  grid.updateRowCount();
		  grid.autosizeColumns();
		  grid.render();
		});
	  
		dataView.onRowsChanged.subscribe(function (e, args) {
		  grid.invalidateRows(args.rows);
		  grid.autosizeColumns();
		  grid.render();
		});			
	  
		// initialize the model after all the events have been hooked up
		dataView.beginUpdate();
		dataView.setItems(data.rows);
		dataView.endUpdate();
	}
	
	function exportExcel() {
		$('#bitacoraGrid').table2CSV();
	}
	