const io = require('socket.io')(3000, {
    cors: {
        origin: "*"
    }
});

io.on('connection', socket => {
    socket.on('offer', data => {
        io.to(data.to.toString()).emit('offer', data);
    });
    socket.on('answer', data => {
        io.to(data.to.toString()).emit('answer', data);
    });
    socket.on('ice-candidate', data => {
        io.to(data.to.toString()).emit('ice-candidate', data);
    });
    socket.on('join', userId => {
        socket.join(userId.toString());
    });
});
