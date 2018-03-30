<?php
/**
 * Created by PhpStorm.
 * User: yuv
 * Date: 25.03.2018
 * Time: 2:06
 */
require_once "../config.php";
require_once "../function.php";

if(isset($_POST)){
    setSettings('app_id', $_POST['app_id']);
    setSettings('app_secret', $_POST['app_secret']);
    setSettings('app_token', $_POST['app_token']);
}

if(getSettings('app_id')){
    $app_id = getSettings('app_id');
}else{
    $app_id = '';
}

if(getSettings('app_secret')){
    $app_secret = getSettings('app_secret');
}else{
    $app_secret = '';
}

if(getSettings('app_token')){
    $app_token = getSettings('app_token');
}else{
    $app_token = '';
}

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Админ - панель телеграмм бота</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Morris -->
    <link href="css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>

<body>
<div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <span>
                            <img alt="image" class="img-circle" src="img/profile_small.jpg" />
                        </span>
                        <span class="block m-t-xs" style="color: #fff;">
                            <strong class="font-bold">Админ сайта</strong>
                        </span>
                    </div>
                </li>
                <li>
                    <a href="index.php"><i class="fa fa-arrow-right"></i> <span class="nav-label">Массовая рассылка</span></a>
                </li>
            </ul>

        </div>
    </nav>

    <div id="page-wrapper" class="gray-bg">
        <div class="wrapper wrapper-content">
            <div class="row">

                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <form method="post" class="form-horizontal">
                                <div class="form-group"><label class="col-sm-2 control-label">Идентификатор приложения:</label>
                                    <div class="col-sm-10"><input type="text" class="form-control" name="app_id" value="<?=$app_id;?>"></div>
                                </div>
                                <div class="form-group"><label class="col-sm-2 control-label">Секрет приложения:</label>
                                    <div class="col-sm-10"><input type="text" class="form-control" name="app_secret" value="<?=$app_secret;?>"></div>
                                </div>
                                <div class="form-group"><label class="col-sm-2 control-label">Маркер доступа:</label>
                                    <div class="col-sm-10"><input type="text" class="form-control" name="app_token" value="<?=$app_token;?>"></div>
                                </div>

                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-2">
                                        <button class="btn btn-primary" type="submit">Сохранить</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="footer">
            <div>
                <strong>Copyright</strong> yuv.com.ua &copy; 2018
            </div>
        </div>

    </div>
</div>

<!-- Mainly scripts -->
<script src="js/jquery-2.1.1.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

</body>
</html>

