<!DOCTYPE html>
  <head>
    <title>Yandex PDD</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet" media="screen">
	<style type="text/css">
            .scrollup
            {
                width:40px;
                height:40px;
                opacity:0.3;
                position:fixed;
                bottom:50px;
                left:50px;
                display:none;
                text-indent:-9999px;
                background: url('/img/icon_top.png') no-repeat;
            }
            body {
                padding-top: 55px;
                padding-bottom: 40px;
		padding-left: 20px;
		padding-right: 20px;
                background-color: #f5f5f5;
            }
            td.bold {
		text-align: center;
		font-weight: bold;
            }
            td.center {
		text-align: center;
            }
	 </style>
	 <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
  <body>
	<?php
		include ('header_top.php');
                echo "<h2>Управление почтовыми ящиками</h2><br>";
		$headers = array("Content-Type: application/json", "PddToken: ");
		// создание нового ресурса cURL
		$ch = curl_init();
		// установка URL и других необходимых параметров
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, "https://pddimp.yandex.ru/api2/admin/email/list?domain=test.biz&page=1&on_page=200");
		// загрузка страницы и выдача её браузеру
		$response = curl_exec($ch);
		// завершение сеанса и освобождение ресурсов
		curl_close($ch);
		$obj=json_decode($response);
		echo "<p>Домен: $obj->domain";
		print '<div class="table-responsive">
                                <table class="table table-striped table-bordered tablesorter" id="myTable">
				<tr>
				<td class="bold">UID</td>
				<td class="bold">ФИО</td>
				<td class="bold">email</td>
				<td class="bold">Удалить ящик</td>
				<td class="bold">Изменение пароля</td>
				</tr>';
		foreach($obj->accounts as $val):
			print
				'<tr>
				<td class="center">' .number_format($val->uid, '0', ',', ''). '</td>
				<td class="center">' .$val->fio. '</td>
				<td class="center">' .$val->login. '</td>
				<td class="center"><button type="button" class="btn btn-danger" name="delete" data-toggle="modal" data-target="#myModalDel">Удалить</button></td>
				<td class="center"><button type="button" class="btn btn-primary" name="modify_pass" data-toggle="modal" data-target="#myModalPass">Изменить пароль</button></td>
				</tr>';
		endforeach;
		print '</table></div>';
                if ((($obj->error) === "bad_token") or (($obj->error) === "bad_login") or (($obj->error) === "bad_pwd")) {
                    print "<div class='alert alert-danger alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                    <strong>Ошибка!</strong> Передан неверный ПДД-токен (логин, пароль).</div>";}
	?>
    
	<!-- Modal for delete -->
  <div class="modal fade" id="myModalDel" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Удаление почтового ящика</h4>
        </div>
        <div class="modal-body">
		
            <form class="form-horizontal" name="delete_form" id="delete_form" method="post">
                    <div class="form-group">
			<label class="control-label col-sm-2" for="loginDel">User login:</label>
			<div class="col-sm-4">
                            <input type="text" class="form-control" id="loginDel" placeholder="login" name="loginDel" required="">
			</div>
                    </div>
                    <script>
			function Go(Type) {
			document.getElementById('buttonDel').disabled=!Type;
			}
                    </script>
                    <div class="checkbox">
                        <label><input type="checkbox" value="" onclick="Go(this.checked)">Я адекватен</label>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-default" id="buttonDel" disabled>Удалить</button>
                    </div>
            </form>
        </div>
      </div>
    </div>
  </div>   
        <?php
                if (isset($_POST['loginDel']))
                {
		$post_url_del = 'https://pddimp.yandex.ru/api2/admin/email/del?domain=test.biz&' .'login='. $_POST['loginDel'];
		$headers = array("Content-Type: application/json", "PddToken: ");
		// создание нового ресурса cURL
		$ch = curl_init();
		// установка URL и других необходимых параметров
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, $post_url_del);
		// загрузка страницы и выдача её браузеру/исполнение
		$response = \curl_exec($ch);
		// завершение сеанса и освобождение ресурсов
		curl_close($ch);
                $obj=json_decode($response);
//                echo $response;
                if ((($obj->error) === "bad_token") or (($obj->error) === "bad_login") or (($obj->error) === "bad_pwd")) {
                    print "<div class='alert alert-danger alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                    <strong>Ошибка!</strong> Передан неверный ПДД-токен (логин, пароль).</div>";}
                if (($obj->success) === "ok"){
                    print "<div class='alert alert-success alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                    <strong>Well done!</strong> Ящик успешно удален.</div>";}
                unset($_POST['loginDel']);
                }
        ?>
        
  <!-- Modal for change password -->
  <div class="modal fade" id="myModalPass" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Смена пароля от почтового ящика</h4>
        </div>
        <div class="modal-body">
		
		<script>
		function checkPass() {
		if (document.getElementById('pass1').value !== document.getElementById('pass2').value) {
		alert('Пароли не совпадают!');
		return false;
		} else return true; }
		</script>
		
		<form class="form-horizontal" name="change_form" id="change_form" method="post">
                    <div class="form-group">
			<label class="control-label col-sm-2" for="loginCh">User login:</label>
			<div class="col-sm-4">
                            <input type="text" class="form-control" id="loginCh" placeholder="login" name="loginCh" required="">
			</div>
                    </div>
			<div class="form-group">
			<label class="control-label col-sm-2" for="pass1">Password:</label>
			<div class="col-sm-4">
                            <input type="password" class="form-control" id="pass1" placeholder="password" name="password1" required="">
			</div>
			</div>
			<div class="form-group">
                            <label class="control-label col-sm-2" for="pass2">Confirm password:</label>
                            <div class="col-sm-4">
                                <input type="password" class="form-control" id="pass2" placeholder="password" name="password2" required="">
                            </div>
			</div>
                        <div class="form-group modal-footer">
                            <div class="col-sm-offset-10">
                                <button type="submit" class="btn btn-default" id="buttonPass" onclick="return checkPass()">Сменить</button>
                            </div>
                        </div>
		</form>
        </div>
      </div>
    </div>
  </div>
  <!-- End modal for change password -->
        <?php
                if (isset($_POST['loginCh']))
                {
		$post_url_edit = 'https://pddimp.yandex.ru/api2/admin/email/edit?domain=test.biz&' .'login='. $_POST['loginCh'] .'&password='. $_POST['password1'];
		$headers = array("Content-Type: application/json", "PddToken: ");
		// создание нового ресурса cURL
		$ch = curl_init();
		// установка URL и других необходимых параметров
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, $post_url_edit);
		// загрузка страницы и выдача её браузеру/исполнение
		$response = \curl_exec($ch);
		// завершение сеанса и освобождение ресурсов
		curl_close($ch);
                $obj=json_decode($response);
//                echo $response;
                if ((($obj->error) === "bad_token") or (($obj->error) === "bad_login") or (($obj->error) === "bad_pwd")) {
                    print "<div class='alert alert-danger alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                    <strong>Ошибка!</strong> Передан неверный ПДД-токен (логин, пароль).</div>";}
                if (($obj->success) === "ok"){
                    print "<div class='alert alert-success alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                    <strong>Well done!</strong> Пароль успешно изменен.</div>";}
                unset($_POST['loginCh']);
                unset($_POST['password1']);
                unset($_POST['password2']);
                }
        ?>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="/js/bootstrap.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
        $(window).scroll(function(){
        if ($(this).scrollTop() > 100) {
        $('.scrollup').fadeIn();
        } else {
        $('.scrollup').fadeOut();
        }
        });
        $('.scrollup').click(function(){
        $("html, body").animate({ scrollTop: 0 }, 600);
        return false;
        });
        });
    </script>
    <a href="#" class="scrollup">Наверх</a>
  </body>
</html>