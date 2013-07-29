<!--Desktop-->
    <div class="row hidden-phone">              
		<div class="span2">
			<!--<button class="btn btn-new-document"><i class="icon-plus"></i> Nuevo</button>-->			
			<div class="btn-group">
				<button id="btn-new-document" class="btn btn-new-document"><i class="icon-plus"></i> Nuevo documento</button>
				<button class="btn btn-new-document dropdown-toggle" data-toggle="dropdown" title="Más opciones para nuevos documentos...">
				<span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					<li><a href="javascript:void(0)" onclick="createNewDocumentWindow('Recibido')">Nuevo documento recibido</a></li>
					<li><a href="javascript:void(0)" onclick="createNewDocumentWindow('Generado')">Nuevo documento generado en la dependencia</a></li>
				</ul>
			</div>
			
		</div>
		 <div class="span8">
			<!--<div class="anlt_header">SAS-DOC .: Sistema de administración y seguimiento de documentos</div>-->
			<div class="anlt_header">SAS-DOC</div>
			<div class="anlt_subheader">Sistema de administración y seguimiento de documentos</div>
		</div>
		<div class="span2 welcome-message">            
		</div>
	</div>

<!--Mobile-->
    <div class="row hidden-desktop hidden-tablet">
		 <div class="offset3 floatLeft">
			<div class="anlt_header">SAS-DOC</div>
		</div>
		<div class="floatLeft welcome-message">            
		</div>
	</div>