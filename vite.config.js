import {defineConfig} from "vite";
import laravel from "laravel-vite-plugin";
import "dotenv/config";

// Vite configuration
export default defineConfig({
    root: '.',  // Ensure the root is correctly pointing to the Laravel root directory
    server: {
        watch: {
            usePolling: true,
            interval: 500,
        }
    }, plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js", // Main JS entry (if needed)
                "resources/js/react/app.jsx", // Main React entry
                "resources/js/chat.jsx", // Chat entry specifically
                "resources/js/comps/*.jsx", // Chat entry specifically
            ],
            refresh: true, // This will allow Vite to refresh automatically on changes
        }),
    ],
});
