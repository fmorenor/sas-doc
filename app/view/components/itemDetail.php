<script src="controller/components/itemDetail.js"></script>

<div id="closeDocumentButton" class="closer"></div>
<div id="detail-content">
    <div class="detail-title">
        <h4>
            <span id="numero_documento"></span>          
            <span class="label label_estatus_detail_class pull-right"></span>
        </h4>
    </div>
    
    <div class="detail-scroll">
        <div id="detail-asunto" class="detail-table-container"></div>        
        
        <div class="detail-table-container">            
            <table id="detail-table" class="table table-bordered table-striped"></table>
        </div>
        
        <div class="detail-adjuntos">
            <div id="detail-adjuntos-list">
                <!--Carga de thumbs de adjuntos-->
            </div>
        </div>
        <div class="clearfix"></div>
        
        <div class="detail-notas"></div>
        <div class="detail-documento-padre"></div>
        <div class="detail-documento-hijo"></div>
    </div>
</div>