<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "poi");

// Verificar sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}
$id_usuario = $_SESSION['id_usuario'];
// Obtener los grupos del usuario
$sql_grupos = "SELECT g.id_grupo, g.nombre_grupo FROM grupo g
                JOIN usuario_grupo ug ON g.id_grupo = ug.id_grupo
                WHERE ug.id_usuario = ?";
$stmt_grupos = $conexion->prepare($sql_grupos);
$stmt_grupos->bind_param("i", $id_usuario);
$stmt_grupos->execute();
$result_grupos = $stmt_grupos->get_result();
$grupos = $result_grupos->fetch_all(MYSQLI_ASSOC);
$stmt_grupos->close();

// Procesar la creación de un nuevo grupo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nombre_grupo_nuevo"])) {
    $nombre_grupo = trim($_POST["nombre_grupo_nuevo"]);
    $miembros_json = $_POST["miembros"] ?? '[]';
    $miembros_ids = json_decode($miembros_json, true);

    if (empty($nombre_grupo)) {
        echo "<script>alert('El nombre del grupo es obligatorio.');</script>";
    } elseif (count($miembros_ids) < 1 || count($miembros_ids) > 3) {
        echo "<script>alert('Selecciona entre 1 y 3 miembros para el grupo.');</script>";
    } else {
        // Crear el grupo
        $stmt_crear_grupo = $conexion->prepare("INSERT INTO grupo (nombre_grupo, id_usuario_creado, fecha_creacion) VALUES (?, ?, NOW())");
        $stmt_crear_grupo->bind_param("si", $nombre_grupo, $id_usuario); 
        $stmt_crear_grupo->execute();
        $id_nuevo_grupo = $stmt_crear_grupo->insert_id;
        $stmt_crear_grupo->close();

        if ($id_nuevo_grupo) {
            // Insertar creador y miembros en usuario_grupo
            $todos_los_miembros = array_merge([$id_usuario], $miembros_ids);
            foreach ($todos_los_miembros as $id_miembro) {
                $stmt_usuario_grupo = $conexion->prepare("INSERT INTO usuario_grupo (id_usuario, id_grupo) VALUES (?, ?)");
                $stmt_usuario_grupo->bind_param("ii", $id_miembro, $id_nuevo_grupo);
                $stmt_usuario_grupo->execute();
                $stmt_usuario_grupo->close();
            }
            $grupo_creado = true;
            exit();
        } else {
            echo "<script>alert('Error al crear el grupo.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chats</title>
        <link rel="stylesheet" href="estilos.css">
    </head>
    
<?php if (isset($grupo_creado) && $grupo_creado): ?>
<div id="notificacion" style="background-color: #d4edda; color: #155724; padding: 10px; margin: 10px 0; border: 1px solid #c3e6cb; border-radius: 5px;">
    Grupo creado correctamente.
</div>
<script>
    setTimeout(() => {
        document.getElementById("notificacion").style.display = "none";
    }, 3000);
</script>
<?php endif; ?>
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
            <input type="text" id="busqueda" placeholder=" Busca o selecciona a la persona con quien quieres conversar..." autocomplete="off">
            <ul id="sugerencias"></ul>
        </div>

        <div style="margin-bottom: 20px; text-align: center;">
            <button onclick="window.location.href='llamadas.php'" class="boton">LLAMADAS</button>
        </div>

        <div class="chats-container">
            <div class="chat-box">
                <?php
                if (isset($_GET['usuario'])) {
                    $id_usuario_chat = (int)$_GET['usuario'];

                    $stmt = $conexion->prepare("SELECT nombre FROM Usuario WHERE id_usuario = ?");
                    $stmt->bind_param("i", $id_usuario_chat);
                    $stmt->execute();
                    $stmt->bind_result($nombre_destinatario);
                    $stmt->fetch();
                    $stmt->close();

                    echo "<h2>Conversación con " . htmlspecialchars($nombre_destinatario) . "</h2>";

                    echo "
                    <div style='text-align: center; margin-bottom: 10px;'>
                        <button class='boton' onclick='iniciarLlamada($id_usuario_chat, false)' style='margin-right: 10px;'>LLAMADA</button>
                        <button class='boton' onclick='iniciarLlamada($id_usuario_chat, true)'>VIDEOLLAMADA</button>
                    </div>";

                    echo "<div class='chat-mensajes' id='chatBox'></div>";

                    echo "<form id='formChat'>
                        <input type='hidden' name='id_receptor' value='$id_usuario_chat'>
                        <input type='text' name='contenido' placeholder='Escribe tu mensaje...' required>
                        <button type='submit'>Enviar</button>
                    </form>";

                } elseif (isset($_GET['grupo'])) {
                    $id_grupo_chat = (int)$_GET['grupo'];

             
                    echo "ID del usuario creador: " . $id_usuario . "<br>"; 
                    $stmt_crear_grupo = $conexion->prepare("INSERT INTO grupo (nombre_grupo, id_usuario_creado, fecha_creacion) VALUES (?, ?, NOW())");
                    $stmt_crear_grupo->bind_param("si", $nombre_grupo, $id_usuario);
                    $stmt_crear_grupo->execute();
                    $stmt_grupo->bind_result($nombre_grupo);
                    $stmt_grupo->fetch();
                    $stmt_grupo->close();

                    echo "<h2>Chat del Grupo: " . htmlspecialchars($nombre_grupo) . "</h2>";
                    echo "<div class='chat-mensajes' id='chatBox'></div>";

                    echo "<form id='formChat'>
                        <input type='hidden' name='id_grupo' value='$id_grupo_chat'>
                        <input type='text' name='contenido' placeholder='Escribe tu mensaje...' required>
                        <button type='submit'>Enviar</button>
                    </form>";
                } else {

                    echo "<p style='text-align:center;'>Busca o selecciona al grupo con quien quieres conversar.</p>";

                    // BUSCAR GRUPO
                    echo "<div style='text-align:center; margin-bottom: 20px;'>
                            <input type='text' id='busquedaGrupo' placeholder='Buscar grupo...' style='padding: 8px; width: 60%; max-width: 400px;'>
                            <button onclick=\"abrirCrearGrupo()\" class=\"boton\">CREAR GRUPO</button>
                            <br><br>
                        </div>";
                }
                ?>
            </div>
        </div>

        <div class="recent-chats">
            <h3>Chats Recientes</h3>
            <?php
                // Chats individuales recientes
                $query_chats = "SELECT DISTINCT
                                    u.id_usuario, u.nombre, u.foto
                                FROM mensaje m
                                JOIN Usuario u ON (u.id_usuario = m.id_emisor AND m.id_emisor != ?)
                                                OR (u.id_usuario = m.id_receptor AND m.id_receptor != ?)
                                WHERE m.id_emisor = ? OR m.id_receptor = ?
                                ORDER BY m.fecha_envio DESC";
                $stmt_chats = $conexion->prepare($query_chats);
                $stmt_chats->bind_param("iiii", $id_usuario, $id_usuario, $id_usuario, $id_usuario);
                $stmt_chats->execute();
                $result_chats = $stmt_chats->get_result();
                $chats_recientes = [];
                while ($row = $result_chats->fetch_assoc()) {
                    if (!isset($chats_recientes[$row['id_usuario']])) {
                        $chats_recientes[$row['id_usuario']] = $row;
                        echo "<div class='chat-item' onclick=\"window.location.href='chats.php?usuario={$row['id_usuario']}'\">";
                        echo "<img src='uploads/{$row['foto']}' alt='Foto' class='chat-foto'>";
                        echo "<span>{$row['nombre']}</span>";
                        echo "</div>";
                    }
                }
                $stmt_chats->close();

                // Grupos recientes
                echo "<h3>Grupos</h3>";
                foreach ($grupos as $grupo) {
                    echo "<div class='chat-item' onclick=\"window.location.href='chats.php?grupo={$grupo['id_grupo']}'\">";
                    echo "<span>{$grupo['nombre_grupo']}</span>";
                    echo "</div>";
                }
            ?>
        </div>

        <script>
            const input = document.getElementById('busqueda');
            const sugerencias = document.getElementById('sugerencias');

            input.addEventListener('input', () => {
                const query = input.value.trim();
                if (query.length === 0) {
                    sugerencias.style.display = 'none';
                    sugerencias.innerHTML = '';
                    return;
                }

                fetch(`buscar_usuarios.php?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        sugerencias.innerHTML = '';
                        data.forEach(usuario => {
                            const li = document.createElement('li');
                            li.textContent = `${usuario.nombre} (${usuario.correo})`;
                            li.addEventListener('click', () => {
                                window.location.href = `chats.php?usuario=${usuario.id_usuario}`;
                            });
                            sugerencias.appendChild(li);
                        });
                        sugerencias.style.display = 'block';
                    });
            });

            function cargarMensajes() {
                const urlParams = new URLSearchParams(window.location.search);
                const usuarioId = urlParams.get('usuario');
                const grupoId = urlParams.get('grupo');

                let fetchURL = "";
                if (usuarioId) fetchURL = `chat_fetch.php?usuario=${usuarioId}`;
                else if (grupoId) fetchURL = `chat_fetch.php?grupo=${grupoId}`;
                else return;

                fetch(fetchURL)
                    .then(res => res.json())
                    .then(data => {
                        const chatBox = document.getElementById('chatBox');
                        chatBox.innerHTML = '';
                        data.forEach(m => {
                            const clase = m.id_emisor == <?= $_SESSION['id_usuario'] ?> ? 'mensaje propio' : 'mensaje';
                            const div = document.createElement('div');
                            div.className = clase;
                            div.innerHTML = `${m.contenido}<br><small>${m.fecha_envio}</small>`;
                            chatBox.appendChild(div);
                        });
                        chatBox.scrollTop = chatBox.scrollHeight;
                    });
            }

            document.addEventListener("DOMContentLoaded", () => {
                const formChat = document.getElementById('formChat');
                if (formChat) {
                    formChat.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const formData = new FormData(this);

                        fetch('chat_backend.php', {
                            method: 'POST',
                            body: formData
                        }).then(() => {
                            this.reset();
                            cargarMensajes();
                        });
                    });
                }

                setInterval(cargarMensajes, 2000);
                cargarMensajes();
            });

            function abrirCrearGrupo() {
                document.getElementById('popupCrearGrupo').style.display = 'block';
            }

            function cerrarCrearGrupo() {
                document.getElementById('popupCrearGrupo').style.display = 'none';
                // Limpiar la selección al cerrar
                miembrosSeleccionados = [];
                actualizarListaMiembros();
                document.getElementById('formCrearGrupo').reset();
            }

            document.addEventListener('DOMContentLoaded', function() {
                const formCrearGrupoPopup = document.getElementById('formCrearGrupo');
                if (formCrearGrupoPopup) {
                    formCrearGrupoPopup.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const nombreGrupo = document.getElementById('nombre_grupo').value;
                        const miembrosInputPopup = document.getElementById('miembrosInput').value;

                        fetch('chats.php', { // Enviar los datos al mismo archivo
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `nombre_grupo_nuevo=${encodeURIComponent(nombreGrupo)}&miembros=${encodeURIComponent(miembrosInputPopup)}`
                        })
                        .then(response => response.text())
                        .then(data => {
                            alert(data);
                            if (data.includes('exitosamente')) {
                                cerrarCrearGrupo();
                                window.location.reload(); // Recargar para mostrar el nuevo grupo
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    });
                }

                const buscarMiembro = document.getElementById('buscarMiembro');
                const sugerenciasMiembros = document.getElementById('sugerenciasMiembros');
                const listaMiembros = document.getElementById('listaMiembros');
                const miembrosInput = document.getElementById('miembrosInput');
                let miembrosSeleccionados = [];

                buscarMiembro.addEventListener('input', () => {
                    const query = buscarMiembro.value.trim();
                    if (query.length === 0) {
                        sugerenciasMiembros.innerHTML = '';
                        return;
                    }

                    fetch(`buscar_usuarios.php?q=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(data => {
                            sugerenciasMiembros.innerHTML = '';
                            data.forEach(usuario => {
                                if (!miembrosSeleccionados.some(m => m.id === usuario.id_usuario)) {
                                    const li = document.createElement('li');
                                    li.style.cursor = 'pointer';
                                    li.textContent = `${usuario.nombre} (${usuario.correo})`;
                                    li.addEventListener('click', () => {
                                        miembrosSeleccionados.push({ id: usuario.id_usuario, nombre: usuario.nombre });
                                        actualizarListaMiembros();
                                        buscarMiembro.value = '';
                                        sugerenciasMiembros.innerHTML = '';
                                    });
                                    sugerenciasMiembros.appendChild(li);
                                }
                            });
                        });
                });

                function actualizarListaMiembros() {
                    listaMiembros.innerHTML = '';
                    miembrosSeleccionados.forEach(miembro => {
                        const li = document.createElement('li');
                        li.textContent = miembro.nombre;

                        const btnEliminar = document.createElement('button');
                        btnEliminar.textContent = 'X';
                        btnEliminar.style.marginLeft = '10px';
                        btnEliminar.onclick = () => {
                            miembrosSeleccionados = miembrosSeleccionados.filter(m => m.id !== miembro.id);
                            actualizarListaMiembros();
                        };

                        li.appendChild(btnEliminar);
                        listaMiembros.appendChild(li);
                    });
                    miembrosInput.value = JSON.stringify(miembrosSeleccionados.map(m => m.id));
                }
            });
        </script>

        <div id="popupCrearGrupo" style="display:none; position:fixed; top:10%; left:30%; width:40%; background:#fff; padding:20px; box-shadow:0 0 10px #000; z-index:100;">
            <h2 style="text-align: center;">CREAR GRUPO</h2>

            <form id="formCrearGrupo" style="display: flex; flex-direction: column; gap: 15px;">

                <div>
                    <label for="nombre_grupo"><strong>Nombre del grupo:</strong></label><br>
                    <input type="text" name="nombre_grupo" id="nombre_grupo" placeholder="Nombre del grupo..." required style="width: 100%; padding: 8px;">
                </div>
                
                <div>
                    <label for="buscarMiembro"><strong>Integrantes del grupo:</strong></label><br>
                    <input type="text" id="buscarMiembro" placeholder="Buscar usuarios..." style="width: 100%; padding: 8px;">
                    <ul id="sugerenciasMiembros" style="list-style: none; padding: 0; margin-top: 5px;"></ul>
                </div>
                
                <div>
                    <strong>Miembros del grupo:</strong>
                    <ul id="listaMiembros" style="list-style: none; padding: 0; margin-top: 5px;"></ul>
                </div>
                
                <input type="hidden" name="miembros" id="miembrosInput">
                
                <div style="display: flex; justify-content: space-between;">
                    <button type="submit" class="boton">Crear Grupo</button>
                    <button type="button" class="boton" onclick="cerrarCrearGrupo()">Cancelar</button>
                </div>
            </form>
        </div>


        <script>
            const buscarMiembro = document.getElementById('buscarMiembro');
            const sugerenciasMiembros = document.getElementById('sugerenciasMiembros');
            const listaMiembros = document.getElementById('listaMiembros');
            const miembrosInput = document.getElementById('miembrosInput');

            let miembrosSeleccionados = [];

            buscarMiembro.addEventListener('input', () => {
                const query = buscarMiembro.value.trim();
                if (query.length === 0) {
                    sugerenciasMiembros.innerHTML = '';
                    return;
                }

                fetch(`buscar_usuarios.php?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        sugerenciasMiembros.innerHTML = '';
                        data.forEach(usuario => {
                            if (!miembrosSeleccionados.some(m => m.id === usuario.id_usuario)) {
                                const li = document.createElement('li');
                                li.style.cursor = 'pointer';
                                li.textContent = `${usuario.nombre} (${usuario.correo})`;
                                li.addEventListener('click', () => {
                                    miembrosSeleccionados.push({ id: usuario.id_usuario, nombre: usuario.nombre });
                                    actualizarListaMiembros();
                                    buscarMiembro.value = '';
                                    sugerenciasMiembros.innerHTML = '';
                                });
                                sugerenciasMiembros.appendChild(li);
                            }
                        });
                    });
            });

            function actualizarListaMiembros() {
                listaMiembros.innerHTML = '';
                miembrosSeleccionados.forEach(miembro => {
                    const li = document.createElement('li');
                    li.textContent = miembro.nombre;

                    const btnEliminar = document.createElement('button');
                    btnEliminar.textContent = 'X';
                    btnEliminar.style.marginLeft = '10px';
                    btnEliminar.onclick = () => {
                        miembrosSeleccionados = miembrosSeleccionados.filter(m => m.id !== miembro.id);
                        actualizarListaMiembros();
                    };

                    li.appendChild(btnEliminar);
                    listaMiembros.appendChild(li);
                });

                // Enviamos solo los IDs al backend
                miembrosInput.value = JSON.stringify(miembrosSeleccionados.map(m => m.id));
            }

        </script>



    </body>
</html>
