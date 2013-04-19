  <style>
    .slick-cell {
      background: white !important;
      border-color: transparent !important;
      line-height: 19px !important;
    }

    .contact-card-cell {
      border-color: transparent !important;
    }

    .cell-inner {
      height: 80px;
      padding: 10px;
      background: #fafafa;
      border-bottom: 1px dotted #dddddd;
      -moz-border-radius: 10px;
      -webkit-border-radius: 10px;
      -moz-box-shadow: 1px 1px 5px silver;
      -webkit-box-shadow: 1px 1px 5px silver;
      -webkit-transition: all 0.5s;
    }

    .cell-inner:hover {
      background: #f0f0f0;
    }

    .cell-main {
      margin-right: 50px;
      width: 200px;
      float: left;
    }
    
    .cell-right {
      width: 60px;
      height: 100%;
      float: right;
      background: url("css/images/stack.png") no-repeat top center;
    }
    
    #myGridL1 .slick-header-columns {
      display: none;
    }
  </style>

  <div id="myGridL1" style="width:100%;"></div>
    

<!-- cell template -->
<script type="text/html" id="cell_template">
  <div class="cell-inner">    
    <div class="cell-main">
      <b><%=name%></b><br/>
      <%=title%><br/>
      <%=email%><br/>
      <%=phone%>
    </div>
    <div class="cell-right"></div>
  </div>
</script>

<script>
  $(document).ready(function() {
    $('#myGridL1').height(($(document).height() - $('#encabezado').height() - 30)+'px');
    console.log($('#myGridL1').height()+'px');
  });
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
    {id: "contact-card", name: "Contacts", formatter: renderCell, width: 350, cssClass: "contact-card-cell"}
  ];

  var options = {
    headerHeight: 0,
    rowHeight: 105,
    editable: false,
    enableAddRow: false,
    enableCellNavigation: false,
    enableColumnReorder: false
  };

  var compiled_template = tmpl("cell_template");

  function renderCell(row, cell, value, columnDef, dataContext) {
    return compiled_template(dataContext);
  }

  $(function () {
    for (var i = 0; i < 100; i++) {
      var d = (data[i] = {});

      d["name"] = "User " + i;
      d["email"] = "test.user@nospam.org";
      d["title"] = "Regional sales manager";
      d["phone"] = "206-000-0000";
    }

    grid = new Slick.Grid("#myGridL1", data, columns, options);
  })
</script>
