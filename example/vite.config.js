import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin2";

export default defineConfig({
  plugins: [
    laravel({
      input: ["resources/js/app.js"],
      refresh: true,
    }),
  ],
});
