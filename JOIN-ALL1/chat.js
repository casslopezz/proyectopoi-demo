function cargarMensajes() {
    fetch('chat_fetch.php')
        .then(res => res.json())
        .then(data => {
            const chat = document.getElementById('chatBox');
            chat.innerHTML = ''; // Limpiar
            data.forEach(m => {
                const div = document.createElement('div');
                div.innerHTML = `<strong>${m.nombre}</strong>: ${m.contenido} <small>${m.fecha}</small>`;
                chat.appendChild(div);
            });
            chat.scrollTop = chat.scrollHeight; // Auto-scroll
        });
}

document.getElementById('formChat').addEventListener('submit', function(e) {
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

setInterval(cargarMensajes, 2000);
window.onload = cargarMensajes;

function iniciarLlamada(usuarioId, esVideollamada) {
    const tipo = esVideollamada ? "video" : "voz";
    const sala = `POI_llamada_${tipo}_${usuarioId}_${Date.now()}`;
    window.open(`https://meet.jit.si/${sala}`, '_blank');
}
