<!DOCTYPE html>

<html>
    <head>        
    </head>    
    <body>
        <div id="myGridL1" style="width:100%;"></div>
           
        <!-- cell template -->
        <script type="text/html" id="cell_template">
          <div class="cell-inner" onclick="javascript:selectDocument(<%=id_documento%>)">    
            <div class="cell-main">
                <b><%=numero_documento%></b><br/>
                <div class="cell-main-subtitle" title='<%=asunto%>'>
                    <p><%=asunto%></p>
                </div>        
                <div class="cell-main-content">
                    <ul>
                    
                        <% if(id_estatus == '1' || id_estatus == '2' || id_estatus == '3'){ %>  
                            <li>Emisión: <%=fecha_emision%></li>
                            <li>Recepción: <%=fecha_recepcion%></li>
                        <% } else if(id_estatus == '5'){ %>                            
                            <li>Recep. docto. origen: <%=fecha_recepcion%></li>
                            <li>Seguimiento: <%=fecha_emision%></li>
                        <% } else { %>
                            <li>Emisión: <%=fecha_emision%></li>
                        <% } %>
                        
                        <li class="textBreak listWrap" title="<%=nombre_remitente%>">Remitente: <%=nombre_remitente%></li>
                        
                        <% if(id_estatus == '1' || id_estatus == '2' || id_estatus == '3'){ %>                
                            <% if(nombre_destinatario){ %>
                            <li class="textBreak listWrap" title="<%=nombre_destinatario%>">Destinatario: <%=nombre_destinatario%></li>
                            <% } %>                    
                        <% } else if(destinatario_documento_enviado)  { %>
                            <li class="textBreak listWrap" title="<%=destinatario_documento_enviado%>">Destinatario: <%=destinatario_documento_enviado%></li>
                        <% } %>
                        
                        <% if(nombre_asignado_a){ %>
                        <li class="textBreak listWrap" title="<%=nombre_asignado_a%>">Asignado a: <%=nombre_asignado_a%></li>
                        <% } %>
                        
                    </ul>
                </div>
            </div>
            <div class="cell-right">
                <% if(dias_restantes && (id_estatus == '1' || id_estatus == '2')){ %>
                    <span class="badge badge-important badge_vigencia" title="Vigencia: <%=vigencia%> días\nDías restantes para atender el documento: <%=dias_restantes%>"><%=dias_restantes%></span>
                <% } %>
                <span class="badge badge_adjuntos" title="Documentos adjuntos: <%=conteo_adjuntos%>"><%=conteo_adjuntos%></span>
                <span class="label label-<%=label_estatus%> label_estatus_class"><%=estatus%></span>   
                
                <img src="<%=thumb%>"/>
               
            </div>
          </div>
        </script>
        
        <script>
            // Simple JavaScript Templating
            // John Resig - http://ejohn.org/ - MIT Licensed
            (function () {    
              var cache = {};
          
              this.tmpl = function tmpl(str, data) {
                // Figure out if we're getting a template, or if we need to
                // load the template - and be sure to cache the result.
                var fn = !/\W/.test(str) ?
                    cache[str] = cache[str] ||
                    tmpl(document.getElementById(str).innerHTML) :
          
                  // Generate a reusable function that will serve as a template
                  // generator (and which will be cached).
                  new Function("obj",
                      "var p=[],print=function(){p.push.apply(p,arguments);};" +
          
                      // Introduce the data as local variables using with(){}
                      "with(obj){p.push('" +
          
                      // Convert the template into pure JavaScript
                        str
                            .replace(/[\r\t\n]/g, " ")
                            .split("<%").join("\t")
                            .replace(/((^|%>)[^\t]*)'/g, "$1\r")
                            .replace(/\t=(.*?)%>/g, "',$1,'")
                            .split("\t").join("');")
                            .split("%>").join("p.push('")
                            .split("\r").join("\\'") + "');}return p.join('');");
          
                // Provide some basic currying to the user
                return data ? fn(data) : fn;
              };
            })();
          
            var grid;
            var data = [];
            var columns = [
              {id: "element", name: "Documentos", formatter: renderCell, width: getListWidth(), cssClass: "element-cell"}
            ];
          
            var options = {
              headerHeight: 0,
              rowHeight: 185,
              editable: false,
              enableAddRow: false,
              enableCellNavigation: true,
              enableColumnReorder: false
            };
          
            var compiled_template = tmpl("cell_template");
          
            function renderCell(row, cell, value, columnDef, dataContext) {
              return compiled_template(dataContext);
            }
            
            $(document).ready(function() {
                $('#myGridL1').height(($(document).height() - $('#encabezado').height() - 68)+'px');    
                $.throbber.show();
                $('.ui-throbber').css('left', ($('#myGridL1').width() / 2)-20+'px');
                
                //$.post("model/components/itemList.php", {id_usuario: userData.id_usuario, id_privilegios: userData.id_privilegios, estatus: userData.estatus, searchType: userData.searchType, searchInput: userData.searchInput}, function(data){                
                $.ajax({
                    type: '<?php echo (($_GET['method']) ? $_GET['method'] : "POST"); ?>',
                    url: "model/components/itemList.php",
                    data: {
                        id_usuario: userData.id_usuario,
                        id_privilegios: userData.id_privilegios,
                        estatus: userData.estatus,
                        searchType: userData.searchType,
                        searchInput: userData.searchInput
                       }
                })
                .done(function(data){                    
                     $.throbber.hide();
                 
                     if (data.length > 0) {
                         grid = new Slick.Grid("#myGridL1", data, columns, options);
                         
                         $(".cell-main-subtitle").dotdotdot();
                         grid.onViewportChanged.subscribe(function(e,args){
                             $(".cell-main-subtitle").dotdotdot();
                         });
                         
                         // Resaltar las palabras buscadas en la lista de documentos
                         if (userData.searchInput) {
                             var searchInputText = userData.searchInput;                
                             $("#itemListL1").highlight(searchInputText);
                             grid.onViewportChanged.subscribe(function(e,args){
                                 $("#itemListL1").highlight(searchInputText);
                             });
                         }
                         
                     } else {
                         $('#myGridL1').css('background-color', '#FFFFFF');
                         $('#myGridL1').html("<div class='itemListMessage'>No hay documentos disponibles que coincidan con el criterio de búsqueda...</div>");
                     }
                 });
            });         
            
            function getListWidth() {
                return $('#itemListL1').width() - 20;
            }    
        </script>
    </body>
