<!DOCTYPE html>
<html>
  <head>
    <title>Yandex PDD</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet" media="screen">
	<!-- Styles -->
    <style type="text/css">
        input:invalid
        {
        border: 2px solid #ff0000;
        }
        body
        {
            padding-top: 55px;
            padding-bottom: 40px;
            padding-left: 20px;
            padding-right: 20px;
            background-color: #f5f5f5;
        }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
  <body>
    <!-- Проверка валидности полей при заполнении -->
		<script>
		function checkPass()
                {
		if (document.getElementById('pass1').value != document.getElementById('pass2').value) {
		alert('Пароли не совпадают!')
		return false;
                }
                else if (document.getElementById('pass1').value == document.getElementById('login').value)
                {
		alert('Пароль совпадает с логином!')
		return false;
		}
                else return true;
                }
		</script>
    <!-- Конец проверки валидности полей при заполнении -->
  <?php include ("header_top.php"); ?>
	<h2>Добавление почтового ящика</h2><br>
	<form class="form-horizontal" name="contact_form" id="contact_form" method="post" onsubmit="return checkPass()">
	<div class="form-group">
		<label class="control-label col-sm-1" for="login">* User login:</label>
		<div class="col-sm-2">
			<input type="text" class="form-control" id="login" placeholder="example: test@dinal.biz" name="login" required="">
		</div>
	</div>
	<div class="form-group">
	<label class="control-label col-sm-1" for="pass1">* Password:</label>
		<div class="col-sm-2">
                    <input type="password" class="form-control" id="pass1" placeholder="password" name="password" required="" pattern="[A-Za-z0-9!@#$^*()_-+:;,.]{6,20}">
		</div>
	</div>
	<div class="form-group">
	<label class="control-label col-sm-1" for="pass2">* Confirm password:</label>
		<div class="col-sm-2">
                    <input type="password" class="form-control" id="pass2" placeholder="password" name="password1" required="" pattern="[A-Za-z0-9!@#$^*()_-+:;,.]{6,20}">
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-1 col-sm-2">
			<button type="submit" class="btn btn-success">Add e-mail</button>
		</div>
	</div>	
	</form>
	
	<div class="alert alert-info"><strong>Login</strong> : Email-адрес почтового ящика в формате «login@domain.ru» или «login».</div>
	<div class="alert alert-info"><strong>Password</strong> : Пароль пользователя. Пароль: должен содержать от 6 до 20 символов — латинские буквы, цифры и символы: «!», «@», «#», «$», «%», «^», «&», «*», 
	«(», «)», «_», «-», «+», «:», «;», «,», «.»; не должен совпадать с логином.</div>
    <?php
	if (isset($_POST['login']))
	{
		$post_url = 'https://pddimp.yandex.ru/api2/admin/email/add?domain=test.biz' .'&login='. $_POST['login'] .'&password='. $_POST['password'];
		$headers = array("Content-Type: application/json", "PddToken: ");
		// создание нового ресурса cURL
		$ch = curl_init();
		// установка URL и других необходимых параметров
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, $post_url);
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
                    <strong>Well done!</strong> Ящик успешно создан.</div>";}
	unset($_POST['login']);
	unset($_POST['password']);
	unset($_POST['password1']);
        }
    ?>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
        <script src="js/bootstrap.js"></script>
  </body>
</html>