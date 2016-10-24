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
	echo "<p align='left'><h3>Управление рассылками</h3></p>";
        print '<button type="button" class="btn btn-danger disabled" name="delete" data-toggle="modal" data-target="#myModalDel">Удалить рассылку</button>';
        print '<button type="button" class="btn btn-success" name="podras" data-toggle="modal" data-target="#myModalPod">Подписать на рассылку</button>';
        print '<button type="button" class="btn btn-danger" name="delpod" data-toggle="modal" data-target="#myModalDelPod">Удалить из рассылку</button>';
        echo '<br><br>';
        $headers = array("Content-Type: application/json", "PddToken: ");
		// создание нового ресурса cURL
		$ch = curl_init();
		// установка URL и других необходимых параметров
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, "https://pddimp.yandex.ru/api2/admin/email/ml/list?domain=test.biz");
		// загрузка страницы и выдача её браузеру
		$response = curl_exec($ch);
		// завершение сеанса и освобождение ресурсов
		curl_close($ch);
		$obj=json_decode($response);
		echo "<p>Домен: $obj->domain";
		print '<div class="table-responsive">
                                <table class="table table-striped table-bordered tablesorter table-hover" id="myTable">
				<tr>
				<td class="bold">UID</td>
				<td class="bold">email-адрес рассылки</td>
				<td class="bold">Количество подписчиков</td>
				</tr>';
		foreach($obj->maillists as $val):
                    print
				'<tr>
				<td class="center">' .number_format($val->uid, '0', ',', ''). '</td>
				<td class="center">' .$val->maillist. '</td>
				<td class="center">' .$val->cnt. '</td>
				</tr>';
                endforeach;
                print '</table></div>';
?>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.js"></script>
  </body>
</html>