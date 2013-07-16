<?php include_once("../../model/functions.php"); ?>

<script src="controller/components/newDocumentEditarSeguimiento.js"></script>
    
    <div id="closeNewDocumentButton" class="closer"></div>
    <form class="form-horizontal" id="form-nuevo-documento" action="model/components/newDocument-edit.php">
        <fieldset>
            <legend>Editar el documento <strong><span id="selectedDocument"></span></strong></legend>
            <!--Primer fila-->
            <div id="newDocumentScroll">
                <div class="row-fluid" >
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label" for="numero_documento"><strong>No. de documento *</strong></label>
                            <div class="controls">
                                <input type="text" id="numero_documento" class="input-fullwidth" placeholder="No. de documento" required>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label" for="tipo_documento"><strong>Tipo de documento *</strong></label>
                            <div class="controls">
                                <div class="ui-widget input-append" id="tipo_documento_container"></div>
                            </div>
                        </div>                        
                    </div>
                </div>
                <!--Segunda fila-->
                <div class="row-fluid">
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label" for="asunto"><strong>Asunto *</strong></label>
                            <div class="controls">
                                <textarea rows="2" id="asunto" placeholder="Asunto" class="fullsize-textarea" required />
                            </div>
                        </div>
                    </div>
                </div>
                <!--Tercera fila-->
                <div class="row-fluid">
                    <div class="span10">
                        <div class="control-group">
                            <label class="control-label" for="nota"><em>Nueva nota</em></label>
                            <div class="controls">
                                <textarea rows="2" id="nota" placeholder="Nota" class="fullsize-textarea" />
                            </div>
                        </div>
                    </div>
                    <div class="span2">   
                        <a id="notas_anteriores" class="btn popover-link" href="javascript:void(0)">Ver las notas asociadas a este documento</a>                        
                    </div>
                </div>
                <!--Cuarta fila-->
                <div class="row-fluid">
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label" for="remitente"><strong>Remitente *</strong></label>
                            <div class="controls">
                                <div class="ui-widget input-append">
                                    <input value=" " type="text" id="remitente" class="input-fullwidth select2-required-input" required >
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="detinatario"><strong>Destinatario *</strong></label>
                            <div class="controls">
                                <div class="ui-widget input-append">
                                    <input value=" " type='text' id="destinatario" class="input-fullwidth select2-required-input" required >
                                </div>                                      
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="fecha_emision"><strong>Fecha emisión *</strong></label>
                            <div class="controls">
                                <div class="input-prepend" style="float: left; width:50%">
                                    <span class="add-on"><i class="icon-calendar"></i></span>
                                    <input type="text" id="fecha_emision" class="input-small" placeholder="<?php echo getToday(); ?>" required>                                    
                                </div>                                
                                <div class="input-prepend bootstrap-timepicker" style="width:50%">                                    
                                    <span class="add-on"><i class="icon-time"></i></span>
                                    <input id="hora_emision" type="text" class="input-small">
                                </div>
                            </div>
                        </div>                               
                    </div>
                
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label" for="expediente">Expediente</label>
                            <div class="controls">
                                <input type="text" id="expediente" class="input-fullwidth" placeholder="Expediente">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="anexos">Anexos</label>
                            <div class="controls">
                                <input type="text" id="anexos" class="input-fullwidth" placeholder="Anexos">
                            </div>
                        </div>                       
                        <div class="control-group">
                            <label class="control-label" for="turnado">Turnado / cc</label>
                            <div class="controls">
                                <!--<input type="text" id="turnado" class="input-fullwidth" placeholder="Turnado">-->
                                <div class="ui-widget input-append">
                                    <input value=" " type='hidden' id="turnado_a" class="input-fullwidth" />
                                </div> 
                            </div>
                        </div>                          
                    </div>
                </div>
                <!-- Quinta fila -->
                <div class="row-fluid">
                    <div class="span12">
                        <div class="detail-adjuntos">
                            <div id="detail-adjuntos-list">
                                <!--Carga de thumbs de adjuntos-->
                            </div>
                        </div>
                        <br />
                    </div>
                </div>                
                <!-- Sexta fila -->
                <div class="row-fluid">
                    <div class="span12">
                        <br />
                        <div><strong>Adjunta los archivos escaneados del documento</strong></div>
                        <div>Los archivos escaneados deben estar en formato PDF, cada archivo puede tener varias páginas y puedes subir varios archivos si lo necesitas.</div>                        
                        <div id="uploader"></div>
                    </div>
                </div>
                <!-- Septima fila -->
                <div class="row-fluid">
                    <div class="span11">
                        <br /><br />
                        <button type="submit" class="btn btn-inverse pull-right"><i class="icon-ok icon-white"></i> Guardar</button>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>