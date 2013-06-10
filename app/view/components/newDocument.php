<?php include_once("../../model/functions.php"); ?>

<script src="controller/components/newDocument.js"></script>
    
    <div id="closeNewDocumentButton" class="closer"></div>
    <form class="form-horizontal">
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
                                <textarea rows="2" id="asunto" placeholder="Asunto" class="fullsize-textarea" required />
                            </div>
                        </div>
                    </div>
                </div>
                <!--Tercera fila-->
                <div class="row-fluid">
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label" for="nota">Nota</label>
                            <div class="controls">
                                <textarea rows="2" id="nota" placeholder="Nota" class="fullsize-textarea" />
                            </div>
                        </div>
                    </div>
                </div>
                <!--Cuarta fila-->
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
                            <label class="control-label" for="turnado">Turnado / cc</label>
                            <div class="controls">
                                <!--<input type="text" id="turnado" class="input-fullwidth" placeholder="Turnado">-->
                                <div class="ui-widget input-append">
                                    <input type='hidden' id="turnado_a_combobox" class="input-fullwidth" />
                                </div> 
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="asignado_a">Asignado a</label>
                            <div class="controls">
                                <!--<input type="text" id="asignado_a" class="input-fullwidth" placeholder="Asignado">-->
                                <div class="ui-widget input-append">
                                    <input type='hidden' id="asignado_a_combobox" class="input-fullwidth" />
                                </div> 
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="vigencia">Días de vigencia</label>
                            <div class="controls">
                                <input id="vigencia" name="vigencia" min="0" max="365" value="0">
                            </div>
                        </div>      
                    </div>
                </div>
                <!-- Quinta fila -->
                <div class="row-fluid">
                    <div class="span12">
                        <br />
                        <div><strong>Adjunta los archivos escaneados del documento</strong></div>
                        <div>Los archivos escaneados deben estar en formato PDF, cada archivo puede tener varias páginas y puedes subir varios archivos si lo necesitas.</div>                        
                        <div id="uploader"></div>
                    </div>
                </div>
                <!-- Sexta fila -->
                <div class="row-fluid">
                    <div class="span11">
                        <br /><br />
                        <button type="submit" class="btn btn-inverse pull-right"><i class="icon-ok icon-white"></i> Guardar</button>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>