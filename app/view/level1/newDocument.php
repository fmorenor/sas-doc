<?php include_once("../../model/functions.php"); ?>

<link href="controller/plugins/select2/select2.css" rel="stylesheet"/>
<script src="controller/plugins/select2/select2.js"></script>
<script src="controller/plugins/select2/select2_locale_es.js"></script>
<script src="controller/level1/newDocument.js"></script>
    
    <div id="closeNewDocumentButton" class="closer"></div>
    <div class="form-horizontal">
        <fieldset>
            <legend>Agregar un nuevo documento</legend>
            <!--Primer fila-->
            <div id="newDocumentScroll">
                <div class="row-fluid" >
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label" for="numero_documento">No. de documento</label>
                            <div class="controls">
                                <input type="text" id="numero_documento" class="input-fullwidth" placeholder="No. de documento" required>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label" for="tipo_documento">Tipo de documento</label>
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
                            <label class="control-label" for="asunto">Asunto</label>
                            <div class="controls">
                                <textarea rows="1" id="asunto" placeholder="Asunto" class="fullsize-textarea" required />
                            </div>
                        </div>
                    </div>
                </div>
                <!--Tercera fila-->
                <div class="row-fluid">
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label" for="remitente">Remitente</label>
                            <div class="controls">
                                <div class="ui-widget input-append">
                                    <input type="hidden" id="remitente_combobox" class="input-fullwidth" />
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="detinatario">Destinatario</label>
                            <div class="controls">
                                <div class="ui-widget input-append">
                                    <input type='hidden' id="destinatario_combobox" class="input-fullwidth" />
                                </div>                                      
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="fecha_emision">Fecha emisión</label>
                            <div class="controls">
                                <input type="text" id="fecha_emision" class="input-fullwidth" placeholder="<?php echo getToday(); ?>" required>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="fecha_recepcion">Fecha recepción</label>
                            <div class="controls">
                                <input type="text" id="fecha_recepcion" class="input-fullwidth" placeholder="<?php echo getToday(); ?>" required>
                            </div>
                        </div>
                         <div class="control-group">
                            <label class="control-label" for="fecha_recepcion2">Recep. oficialia partes</label>
                            <div class="controls">
                                <input type="text" id="fecha_recepcion2" class="input-fullwidth" placeholder="<?php echo getToday(); ?>">
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
                            <label class="control-label" for="turnado">Turnado</label>
                            <div class="controls">
                                <input type="text" id="turnado" class="input-fullwidth" placeholder="Turnado">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="asignado_a">Asignado a</label>
                            <div class="controls">
                                <input type="text" id="asignado_a" class="input-fullwidth" placeholder="Asignado">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="vigencia">Vigencia</label>
                            <div class="controls">
                                <input id="vigencia" name="vigencia" min="0" max="365" value="0">
                            </div>
                        </div>      
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span11">
                        <button type="submit" class="btn btn-inverse pull-right"><i class="icon-ok icon-white"></i> Guardar</button>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>