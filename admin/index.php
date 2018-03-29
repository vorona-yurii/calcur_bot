<?php
/**
 * Created by PhpStorm.
 * User: yuv
 * Date: 25.03.2018
 * Time: 2:06
 */
require '../vendor/autoload.php';

require '../config.php';
require '../function.php';

use Telegram\Bot\Api;

if(isset($_POST)){

    $path_photo = realpath('uploads_img')."/".$_FILES['img']['name'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        if (!@copy($_FILES['img']['tmp_name'], $path_photo))
            echo 'Что-то пошло не так';
        else
            echo 'Загрузка удачна';
    }

    $user['user_id'] = '384607648';

    $telegram = new Api(BOT_API_KEY);

    $response = $telegram->sendPhoto([
        'chat_id' => $user['user_id'],
        'photo' => $path_photo,
        'caption' => $_POST['bulk']
    ]);

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
                    <a href="facebook.php"><i class="fa fa-cog"></i> <span class="nav-label">Настройки</span></a>
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
                            <form enctype="multipart/form-data" method="post" class="form-horizontal">
                                <div class="form-group"><label class="col-sm-2 control-label">Массовая рассылка</label>
                                    <div class="col-sm-10"><textarea name="bulk" id="bulk" cols="100" rows="5"></textarea></div>
                                </div>
                                <div class="form-group"><label class="col-sm-2 control-label">Изображение</label>
                                    <div class="col-sm-10"><input type="file" name="img"></div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-2">
                                        <button class="btn btn-primary" type="submit">Разослать</button>
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


