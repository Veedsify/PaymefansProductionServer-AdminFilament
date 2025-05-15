// vite.config.js
import { defineConfig } from "file:///home/dike/Documents/Files%20Kali/Documents/Projects/paymefans/admin2/node_modules/vite/dist/node/index.js";
import laravel from "file:///home/dike/Documents/Files%20Kali/Documents/Projects/paymefans/admin2/node_modules/laravel-vite-plugin/dist/index.js";
import "file:///home/dike/Documents/Files%20Kali/Documents/Projects/paymefans/admin2/node_modules/dotenv/config.js";
var vite_config_default = defineConfig({
  root: ".",
  // Ensure the root is correctly pointing to the Laravel root directory
  server: {
    watch: {
      usePolling: true,
      interval: 500
    }
  },
  plugins: [
    laravel({
      input: [
        "resources/css/app.css",
        "resources/css/filament/admin/theme.css",
        "resources/js/app.js",
        // Main JS entry (if needed)
        "resources/js/chat.jsx"
        // Chat entry specifically
      ],
      refresh: true
      // This will allow Vite to refresh automatically on changes
    })
  ]
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCIvaG9tZS9kaWtlL0RvY3VtZW50cy9GaWxlcyBLYWxpL0RvY3VtZW50cy9Qcm9qZWN0cy9wYXltZWZhbnMvYWRtaW4yXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ZpbGVuYW1lID0gXCIvaG9tZS9kaWtlL0RvY3VtZW50cy9GaWxlcyBLYWxpL0RvY3VtZW50cy9Qcm9qZWN0cy9wYXltZWZhbnMvYWRtaW4yL3ZpdGUuY29uZmlnLmpzXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ltcG9ydF9tZXRhX3VybCA9IFwiZmlsZTovLy9ob21lL2Rpa2UvRG9jdW1lbnRzL0ZpbGVzJTIwS2FsaS9Eb2N1bWVudHMvUHJvamVjdHMvcGF5bWVmYW5zL2FkbWluMi92aXRlLmNvbmZpZy5qc1wiO2ltcG9ydCB7ZGVmaW5lQ29uZmlnfSBmcm9tIFwidml0ZVwiO1xuaW1wb3J0IGxhcmF2ZWwgZnJvbSBcImxhcmF2ZWwtdml0ZS1wbHVnaW5cIjtcbmltcG9ydCBcImRvdGVudi9jb25maWdcIjtcblxuLy8gVml0ZSBjb25maWd1cmF0aW9uXG5leHBvcnQgZGVmYXVsdCBkZWZpbmVDb25maWcoe1xuICAgIHJvb3Q6ICcuJywgIC8vIEVuc3VyZSB0aGUgcm9vdCBpcyBjb3JyZWN0bHkgcG9pbnRpbmcgdG8gdGhlIExhcmF2ZWwgcm9vdCBkaXJlY3RvcnlcbiAgICBzZXJ2ZXI6IHtcbiAgICAgICAgd2F0Y2g6IHtcbiAgICAgICAgICAgIHVzZVBvbGxpbmc6IHRydWUsXG4gICAgICAgICAgICBpbnRlcnZhbDogNTAwLFxuICAgICAgICB9XG4gICAgfSwgcGx1Z2luczogW1xuICAgICAgICBsYXJhdmVsKHtcbiAgICAgICAgICAgIGlucHV0OiBbXG4gICAgICAgICAgICAgICAgXCJyZXNvdXJjZXMvY3NzL2FwcC5jc3NcIixcbiAgICAgICAgICAgICAgICBcInJlc291cmNlcy9jc3MvZmlsYW1lbnQvYWRtaW4vdGhlbWUuY3NzXCIsXG4gICAgICAgICAgICAgICAgXCJyZXNvdXJjZXMvanMvYXBwLmpzXCIsIC8vIE1haW4gSlMgZW50cnkgKGlmIG5lZWRlZClcbiAgICAgICAgICAgICAgICBcInJlc291cmNlcy9qcy9jaGF0LmpzeFwiLCAvLyBDaGF0IGVudHJ5IHNwZWNpZmljYWxseVxuICAgICAgICAgICAgXSxcbiAgICAgICAgICAgIHJlZnJlc2g6IHRydWUsIC8vIFRoaXMgd2lsbCBhbGxvdyBWaXRlIHRvIHJlZnJlc2ggYXV0b21hdGljYWxseSBvbiBjaGFuZ2VzXG4gICAgICAgIH0pLFxuICAgIF0sXG59KTtcbiJdLAogICJtYXBwaW5ncyI6ICI7QUFBNlgsU0FBUSxvQkFBbUI7QUFDeFosT0FBTyxhQUFhO0FBQ3BCLE9BQU87QUFHUCxJQUFPLHNCQUFRLGFBQWE7QUFBQSxFQUN4QixNQUFNO0FBQUE7QUFBQSxFQUNOLFFBQVE7QUFBQSxJQUNKLE9BQU87QUFBQSxNQUNILFlBQVk7QUFBQSxNQUNaLFVBQVU7QUFBQSxJQUNkO0FBQUEsRUFDSjtBQUFBLEVBQUcsU0FBUztBQUFBLElBQ1IsUUFBUTtBQUFBLE1BQ0osT0FBTztBQUFBLFFBQ0g7QUFBQSxRQUNBO0FBQUEsUUFDQTtBQUFBO0FBQUEsUUFDQTtBQUFBO0FBQUEsTUFDSjtBQUFBLE1BQ0EsU0FBUztBQUFBO0FBQUEsSUFDYixDQUFDO0FBQUEsRUFDTDtBQUNKLENBQUM7IiwKICAibmFtZXMiOiBbXQp9Cg==
