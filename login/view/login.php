<!DOCTYPE html>

<html>
<head>
    <script src="login/controller/login.js"></script>
</head>

<body>
<div class="row-fluid" style="padding-top: 30px;">    
    <div class="span6 offset3">
        <p class="anlt_content" style="background: none !important; margin: 0 0 20px 30px; ">Proporciona tus datos para ingresar al sistema</p>
        
        <form class="form-horizontal login-form" action="login/model/verify-user.php" id="loginForm">
            <div class="control-group">
                <label class="control-label" for="username">Usuario</label>
                <div class="controls">
                    <input type="text" id="username" name="username" placeholder="Usuario">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="password">Password</label>
                <div class="controls">
                    <input type="password" id="password" name="password" placeholder="Password">
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <label class="checkbox" style="float:left; padding-right:20px">
                        <input type="checkbox"> Recordar
                    </label>
                    <button type="submit" class="btn"><i class="icon-ok-sign"></i> Ingresar</button>
                </div>
            </div>
        </form>
        
    </div>    
</div>
<div id="dialog-modal" title="No se pudo ingresar al sistema" style="display: none">
	<p>El nombre de usuario o contrase&ntilde;a no son correctos. Por favor int&eacute;ntalo nuevamente.</p>
</div>

</body>
</html>
