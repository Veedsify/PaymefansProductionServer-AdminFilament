import './bootstrap';
import Echo from 'laravel-echo';
import io from 'socket.io-client';

const EXPRESS_SERVER = 'http://localhost:3002'; 

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: EXPRESS_SERVER,
});

console.log('Echo:', window.Echo);

Echo.channel('messages')
    .listen('conversations', (event) => {
        console.log('New message:', event.message);
        Livewire.emit('messageReceived', event.message); // Emit Livewire event to update UI
    });
