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
      body {
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
<?php
	include ("header_top.php");
	echo "<p align='left'><h3>Монитор состояния почтового домена</h3></p>";
        $headers = array("Content-Type: application/json", "PddToken: ");
		// создание нового ресурса cURL
		$ch = curl_init();
		// установка URL и других необходимых параметров
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, "https://pddimp.yandex.ru/api2/admin/domain/registration_status?domain=dinal.biz");
		// загрузка страницы и выдача её браузеру
		$response = curl_exec($ch);
		// завершение сеанса и освобождение ресурсов
		curl_close($ch);
		$obj=json_decode($response);
		echo "<p>Домен: $obj->domain";
                if (($obj->status) === "added"){
                    print "<p>Домен подтвержден, MX-запись настроена.";}
                if (($obj->check_results) === "ok"){
                    print "<p>Домен подтвержден, MX-запись настроена (почта работает).";}
                echo "<p>Дата и время последней проверки: $obj->last_check";
                echo "<p>Дата и время следующей проверки: $obj->next_check";
                if ((($obj->error) === "bad_token") or (($obj->error) === "bad_login") or (($obj->error) === "bad_pwd")) {
                    print "<div class='alert alert-danger alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                    <strong>Ошибка!</strong> Передан неверный ПДД-токен (логин, пароль).</div>";}
?>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.js"></script>
  </body>
</html>