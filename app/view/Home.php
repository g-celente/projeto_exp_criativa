<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>
        <?php 

            $resultados = selectUser();

            foreach ($resultados as $resultado) {
                echo "NAME: " . $resultado["name"] . " - Email: " . $resultado["email"] . "<br>";
            }
        ?>
    </p>
</body>
</html>