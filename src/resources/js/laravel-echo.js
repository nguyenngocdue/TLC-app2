import Echo from 'laravel-echo'

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':' + window.location.port,
    transports: ['websocket', 'polling', 'flashsocket'],
})
