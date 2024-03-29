<!-- NavBar -->
    <div class="navbar">
      <div class="navbar-inner">
        <div class="container"> 
            <ul class="nav" id="myNav">
              <li class="active"><a href="#" data-toggle="tab" rel="1,2">Pendientes</a></li>
              <li><a href="#" data-toggle="tab" rel="3" >Atendidos</a></li>
              <li><a href="#" data-toggle="tab" rel="4,5">Enviados</a></li>             
            </ul>
            
            <!-- Ordenamiento -->
            <div class="btn-group">
              <a class="btn btn-small dropdown-toggle" data-toggle="dropdown" href="#" title="Ordenar los documentos">
                <i class="icon-arrow-down"></i> 
                <i class="icon-arrow-up"></i>  
                <span class="caret"></span>
              </a>
              <ul id="sorting" class="dropdown-menu pull-right">
                <li><a href="javascript:void(0)" rel="desc" id="sort_numero_documento" onclick="sortList(this)"><i></i> Número de documento</a></li>
                <li><a href="javascript:void(0)" rel="desc" id="sort_asunto" onclick="sortList(this)"><i></i> Asunto</a></li>
                <li><a href="javascript:void(0)" rel="desc" id="sort_remitente" onclick="sortList(this)"><i></i> Remitente</a></li>
                <li><a href="javascript:void(0)" rel="desc" id="sort_destinatario" onclick="sortList(this)"><i></i> Destinatario</a></li>
                <li><a href="javascript:void(0)" rel="desc" id="sort_dias_restantes" onclick="sortList(this)"><i></i> Días restantes para atención</a></li>
                <li><a href="javascript:void(0)" rel="desc" id="fecha_emision" onclick="sortList(this)"><i></i> Fecha de emisión</a></li>
                <li class="selected"><a href="javascript:void(0)" rel="asc" id="fecha_recepcion" onclick="sortList(this)"><i class="icon-chevron-down icon-white"></i> Fecha de recepción</a></li>
              </ul>
            </div>
            
            <!-- Búsqueda general -->
            <form class="navbar-search pull-right" action="javascript:search()" id="searchGroup1">
                <div class="input-append">
                    <label class="span3 label-search-style">Búsqueda general</label>
                    <input class="span2" id="searchInput" type="text" placeholder="Buscar...">
                        <button type="button" class="close" id="searchClear">&times;</button>
                    </input>
                    <div class="btn-group">                        
                        <button class="btn" type="submit" title="Buscar..." id="searchButton"><i class="icon-search"></i></button>
                        <button class="btn dropdown-toggle" title="Más métodos de búsqueda..." data-toggle="dropdown">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="javascript:setSearchGroup('general')">Búsqueda general</a></li>
                            <li><a href="javascript:setSearchGroup('recepcion')">Buscar por rango de fecha (recepción)</a></li>
                            <li><a href="javascript:setSearchGroup('emision')">Buscar por rango de fecha (emisión)</a></li>
                        </ul>
                        <!--<button class="btn" type="button" title="Limpiar búsqueda..." id="searchClear"><i class="icon-remove"></i></button>-->
                    </div>                    
                </div>
            </form>
            <!-- Búsqueda por fecha -->
            <form class="navbar-search pull-right" action="javascript:searchDateRange()" id="searchGroup2" style="display: none">
                <div class="input-append">
                    <label class="span3 label-search-style"></label>
                    <input class="span2" type="text" id="searchDateFrom" name="searchDateFrom" placeholder="Desde:" />                   
                    <input class="span2" type="text" id="searchDateTo" name="searchDateTo" placeholder="Hasta:">
                        <button type="button" class="close" id="searchClear2">&times;</button>
                    </input>
                    <div class="btn-group">
                        <!--<button class="btn" type="button" title="Limpiar búsqueda..." id="searchClear2"><i class="icon-remove"></i></button>-->
                        <button class="btn" type="submit" title="Buscar..." id="searchButton2"><i class="icon-search"></i></button>
                        <button class="btn dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="javascript:setSearchGroup('general')">Búsqueda general</a></li>
                            <li><a href="javascript:setSearchGroup('recepcion')">Buscar por rango de fecha (recepción)</a></li>
                            <li><a href="javascript:setSearchGroup('emision')">Buscar por rango de fecha (emisión)</a></li>
                        </ul>                            
                    </div>                    
                </div>
            </form>
            <!-- Herramientas del itemDeatil -->
                <div id="itemDetailTools" class="input-append pull-right">
                    <label class="span4 label-search-style">Herramientas del documento seleccionado</label>                    
                    <div class="btn-group">
                        <button class="btn" id="btn-edit-document"><i class="icon-edit"></i> Editar</button>
                        <button class="btn" id="btn-follow-document"><i class="icon-share"></i> Seguimiento</button>
                        <button class="btn" id="btn-delete-document"><i class="icon-trash"></i> Eliminar</button>
                    </div>
                </div>
        </div>
      </div><!-- /navbar-inner -->
    </div><!-- /navbar -->
        