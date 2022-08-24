<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User UTM</title>
    <style>
        #log {
            display: flex;
            flex-direction: column-reverse;
        }

        p {
            white-space: pre
        }
    </style>
</head>
<body>
    <section id="main">

        <? if (!isset($_POST['file_name']) && !isset($_POST['b24_source'])) { ?>

            <form action="/" method="post">
                <p>
                    <label for="">Введите ссылку на Bitrix24</label>
                    <input type="text" name="b24_source">
                </p>
                <p>
                    <label for="">Введите имя файла xlsx</label>
                    <input type="text" name="file_name">
                </p>
                <p>
                    <input type="submit" value="Отправить">
                </p>
            </form>

        <?php } else { ?>

            <div id="log"></div>

        <?php } ?>

        <pre>
            <?
            ini_set('max_execution_time', 3000);

            if (isset($_POST['file_name'])) {
                require_once (__DIR__ . '/FileControl.php');
            }

            ?>
        </pre>

    </section>

    <footer>
        <? if (isset($_POST['file_name']) && isset($_POST['b24_source'])) { ?>
            <script>
                var b24_source = "<?= $_POST['b24_source']; ?>";
            </script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
            <script type="text/javascript" src="/assets/js/index.js"></script>
        <? } ?>
    </footer>
</body>
</html>