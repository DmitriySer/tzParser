<?php
error_reporting(0);
require 'vendor/foo.php';

session_start();
?>

<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Parser</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
<?php

?>
<div class="container">
    <div class="row">
    <form action="vendor/foo.php" method="post">
        <div class="input-group-lg mt-5">
            <div class="input-group mb-3">
                <input type="text" name="link" class="form-control" placeholder='Укажите ссылку'
                       aria-describedby="button-addon1">
                <button class="btn btn-success" type="submit" name="linkBut"
                ">Перейти</button>
            </div>
            <div class="input-group">
                <input type="text" placeholder="Выбор" name="select" class="form-control">
                <input type="text" placeholder="Замена" name="assignment" class="form-control">
                <button class="btn btn-warning" name="selection" type="submit">Изменить</button>
            </div>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
            <button class="btn btn-primary me-md-2 " name="save" type="submit">Сохранить в html</button>
            <button type="submit" name="reset" class="btn btn-danger">Сбросить</button>
        </div>
    </form>
    </div>
</div>
            <?php
            try {
                if ($_SESSION['site']) {
                    echo $_SESSION['site'];
                }
            }catch (Exception $e)
            {
                echo $e->getMessage();
            }
            ?>
</body>
</html>