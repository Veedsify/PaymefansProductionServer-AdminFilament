{{-- <x-filament-panels::page> --}}
<div class="lg:grid grid-cols-12 h-full py-6">
    @livewire('chat.conversations')
    <livewire:chat.messages />
    <script <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script
        src="{{ $backendServer ? $backendServer . '/socket.io/socket.io.js' : 'http://localhost:3001/socket.io/socket.io.js' }}">
    </script>
    <script>
        const server = "{{ $backendServer }}";
        const userId = "{{ auth()->user()->user_id }}"
        const dingSound = "{{ asset('sounds/ding.mp3') }}"
    </script>
    <script src="{{ asset('js/socket/socket.js') }}"></script>
</div>
{{-- </x-filament-panels::page> --}}
