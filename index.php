<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join All</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('fondos/background.png') no-repeat center center/cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .container {
            color: #623e2a;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 325px;
        }
        .btn {
            text-decoration: none;
            background: white;
            color: black;
            padding: 12px 25px;
            font-size: 1.3rem;
            border-radius: 5px;
            transition: 0.3s;
            font-weight: bold;
        }
        .btn:hover {
            background: #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="buttons">
            <a href="login.php" class="btn">INICIO SESIÓN</a>
            <a href="registro.php" class="btn">REGISTRARSE</a>
        </div>
    </div>
</body>
</html>
