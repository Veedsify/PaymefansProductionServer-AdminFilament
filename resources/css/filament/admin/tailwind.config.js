import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Clusters/Chats/**/*.php',
        './resources/views/filament/clusters/chats/**/*.blade.php',
        './resources/views/filament/pages/**/*.blade.php',
        './resources/views/livewire/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
