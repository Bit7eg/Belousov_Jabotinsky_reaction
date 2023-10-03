<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8"/>
        <title>Реакция Белоусова-Жаботинского</title>
        <link rel="stylesheet" href="css/styles.css"/>
        <?php
        $libs_list = [
            "sylvester",
            "gl_utils",
            "camera",
            "canvas"
        ];
        foreach ($libs_list as $lib) {
            echo '<script type="text/javascript" src="js/' . $lib . '.js"></script>';
        }
        ?>
        <script type="text/javascript" src="js/script.js"></script>
    </head>
    <body>
        <header>
            <button id="home-button">Главная</button>
            <button id="description-button">Описание</button>
            <button id="report-button">Отчёт</button>
        </header>
        <?php include "pages/app.php" ?>
    </body>
</html>