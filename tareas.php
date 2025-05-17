<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tareas</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('fondos/paginas.png') no-repeat center center fixed;
            background-size: cover;
        }

        /* Estilo del menú lateral */
        .sidebar {
            width: 200px;
            height: 100vh;
            background-color: #3b2413;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            text-align: left;
        }

        .sidebar a:hover {
            background-color: #5a3821;
        }

        /* Contenedor principal */
        .content {
            margin-left: 200px;
            padding: 20px;
        }

        /* Estilo del buscador */
        .search-container {
            background-color: #3b2413;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            top: 0;
            left: 200px;
            width: calc(100% - 200px);
            z-index: 100;
        }

        .search-container input {
            width: 60%;
            padding: 8px;
            border-radius: 20px;
            border: none;
            font-size: 16px;
        }

        .search-container button {
            background: none;
            border: none;
            cursor: pointer;
            margin-left: 10px;
        }

        .search-container img {
            width: 30px;
            height: 30px;
        }

        /* Ajuste del contenido para que no se cubra por el buscador */
        .main-content {
            margin-top: 60px;
        }

        .tareas-container{
        flex-direction: column; /* Para que el texto esté en columna */
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            top: 50px;
            left: 200px;
            width: calc(100% - 200px);
            z-index: 100;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <a href="notificacion.php">Notificaciones</a>
        <a href="equipos.php">Equipos</a>
        <a href="tareas.php">Tareas</a>
        <a href="llamadas.php">Llamadas</a>
        <a href="chats.php">Chat</a>
        <a href="correo.php">Correo</a>
        <a href="perfil.php">Perfil</a>
    </div>

    <div class="search-container">
        <button><img src="fondos/buscar.png" alt="Buscar"></button>
        <input type="text" placeholder=" Buscar...">
    </div>

    <div class="tareas-container">
        <h1>Tareas</h1>
        <p>Aquí aparecerán todas tus tareas</p>
    </div>
</body>
</html>
