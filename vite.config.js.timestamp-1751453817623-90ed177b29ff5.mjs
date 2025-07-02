// vite.config.js
import { defineConfig } from "file:///E:/laragon/www/LeadXFormsNewPortal/node_modules/vite/dist/node/index.js";
import laravel from "file:///E:/laragon/www/LeadXFormsNewPortal/node_modules/laravel-vite-plugin/dist/index.mjs";
import vue from "file:///E:/laragon/www/LeadXFormsNewPortal/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import inject from "file:///E:/laragon/www/LeadXFormsNewPortal/node_modules/@rollup/plugin-inject/dist/es/index.js";
var vite_config_default = defineConfig({
  plugins: [
    inject({
      $: "jquery",
      jQuery: "jquery"
    }),
    laravel([
      "resources/js/admin/app.js",
      "resources/js/frontend/app.js"
    ]),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false
        }
      }
    })
  ],
  resolve: {
    "@": "resources/js"
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJFOlxcXFxsYXJhZ29uXFxcXHd3d1xcXFxMZWFkWEZvcm1zTmV3UG9ydGFsXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ZpbGVuYW1lID0gXCJFOlxcXFxsYXJhZ29uXFxcXHd3d1xcXFxMZWFkWEZvcm1zTmV3UG9ydGFsXFxcXHZpdGUuY29uZmlnLmpzXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ltcG9ydF9tZXRhX3VybCA9IFwiZmlsZTovLy9FOi9sYXJhZ29uL3d3dy9MZWFkWEZvcm1zTmV3UG9ydGFsL3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHsgZGVmaW5lQ29uZmlnIH0gZnJvbSAndml0ZSc7XG5pbXBvcnQgbGFyYXZlbCBmcm9tICdsYXJhdmVsLXZpdGUtcGx1Z2luJztcbmltcG9ydCB2dWUgZnJvbSAnQHZpdGVqcy9wbHVnaW4tdnVlJztcbmltcG9ydCB7IHJlc29sdmUgfSBmcm9tICdwYXRoJztcbmltcG9ydCBpbmplY3QgZnJvbSBcIkByb2xsdXAvcGx1Z2luLWluamVjdFwiO1xuXG5leHBvcnQgZGVmYXVsdCBkZWZpbmVDb25maWcoe1xuICAgIHBsdWdpbnM6IFtcbiAgICAgICAgaW5qZWN0KHtcbiAgICAgICAgICAgICQ6ICdqcXVlcnknLFxuICAgICAgICAgICAgalF1ZXJ5OiAnanF1ZXJ5JyxcbiAgICAgICAgfSksXG4gICAgICAgIGxhcmF2ZWwoW1xuICAgICAgICAgICAgJ3Jlc291cmNlcy9qcy9hZG1pbi9hcHAuanMnLFxuICAgICAgICAgICAgJ3Jlc291cmNlcy9qcy9mcm9udGVuZC9hcHAuanMnLFxuICAgICAgIF0pLFxuICAgICAgICB2dWUoe1xuICAgICAgICAgICAgdGVtcGxhdGU6IHtcbiAgICAgICAgICAgICAgICB0cmFuc2Zvcm1Bc3NldFVybHM6IHtcbiAgICAgICAgICAgICAgICAgICAgYmFzZTogbnVsbCxcbiAgICAgICAgICAgICAgICAgICAgaW5jbHVkZUFic29sdXRlOiBmYWxzZSxcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgfSxcbiAgICAgICAgfSksXG4gICAgXSxcbiAgICByZXNvbHZlOiB7XG4gICAgICAgICdAJzogJ3Jlc291cmNlcy9qcydcbiAgICB9XG59KTtcbiJdLAogICJtYXBwaW5ncyI6ICI7QUFBZ1MsU0FBUyxvQkFBb0I7QUFDN1QsT0FBTyxhQUFhO0FBQ3BCLE9BQU8sU0FBUztBQUVoQixPQUFPLFlBQVk7QUFFbkIsSUFBTyxzQkFBUSxhQUFhO0FBQUEsRUFDeEIsU0FBUztBQUFBLElBQ0wsT0FBTztBQUFBLE1BQ0gsR0FBRztBQUFBLE1BQ0gsUUFBUTtBQUFBLElBQ1osQ0FBQztBQUFBLElBQ0QsUUFBUTtBQUFBLE1BQ0o7QUFBQSxNQUNBO0FBQUEsSUFDTCxDQUFDO0FBQUEsSUFDQSxJQUFJO0FBQUEsTUFDQSxVQUFVO0FBQUEsUUFDTixvQkFBb0I7QUFBQSxVQUNoQixNQUFNO0FBQUEsVUFDTixpQkFBaUI7QUFBQSxRQUNyQjtBQUFBLE1BQ0o7QUFBQSxJQUNKLENBQUM7QUFBQSxFQUNMO0FBQUEsRUFDQSxTQUFTO0FBQUEsSUFDTCxLQUFLO0FBQUEsRUFDVDtBQUNKLENBQUM7IiwKICAibmFtZXMiOiBbXQp9Cg==
