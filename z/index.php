<?php

$subdomains_dir = '/var/www/meme.mrbaco.ru/subdomains/';
$href = 'https://%s.meme.mrbaco.ru/';

if (isset($_POST['name'])) {
    $uploaded = false;

    if ($_FILES && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $name = strtolower(htmlspecialchars($_POST['name']));

        setcookie('name', $name);
        $_COOKIE['name'] = $name;

        if (preg_match('/[a-z0-9]{,9}/', $name)) {
            if (!file_exists($subdomains_dir . $name)) {
                mkdir($subdomains_dir . $name);
            }

            if ($_FILES['file']['size'] < 1024000) {
                if (move_uploaded_file($_FILES['file']['tmp_name'], $subdomains_dir . $name . '/index.php')) {
                    $uploaded = true;
                }
            }
        }
    }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Загрузчик</title>
        <meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="/task.css" />
    </head>
    <body>
        <?php
        
        if (isset($uploaded)) {
            if ($uploaded) {
                $url = sprintf($href, $name);
                echo '<p style="color: #00cc00;">Файл загружен! Для просмотра перейти сюда: <a href="' . $url . '" target="blank">' . $url . '</a></p>';
            } else {
                echo '<p style="color: #cc0000;">Произошла ошибка при загрузке файла!</p>';
            }
        }

        ?>
        <form action="" method="multipart/form-data">
            <p>
                Название (только символы a-z, 0-9):<br />
                <input type="text" value="<?php isset($_COOKIE['name']) ? $_COOKIE['name'] : ''; ?>" name="name" />
            </p>
            <p>
                <input type="file" name="file" />
            </p>
            <input type="submit" value="Загрузить" />
        </form>
    </body>
</html>