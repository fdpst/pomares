// vite.config.js
import VueI18nPlugin from "file:///C:/Users/Usuario/Documents/GitHub/fidifactu/node_modules/@intlify/unplugin-vue-i18n/lib/vite.mjs";
import vue from "file:///C:/Users/Usuario/Documents/GitHub/fidifactu/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import vueJsx from "file:///C:/Users/Usuario/Documents/GitHub/fidifactu/node_modules/@vitejs/plugin-vue-jsx/dist/index.mjs";
import laravel from "file:///C:/Users/Usuario/Documents/GitHub/fidifactu/node_modules/laravel-vite-plugin/dist/index.js";
import { fileURLToPath } from "node:url";
import AutoImport from "file:///C:/Users/Usuario/Documents/GitHub/fidifactu/node_modules/unplugin-auto-import/dist/vite.js";
import Components from "file:///C:/Users/Usuario/Documents/GitHub/fidifactu/node_modules/unplugin-vue-components/dist/vite.js";
import {
  VueRouterAutoImports,
  getPascalCaseRouteName
} from "file:///C:/Users/Usuario/Documents/GitHub/fidifactu/node_modules/unplugin-vue-router/dist/index.mjs";
import VueRouter from "file:///C:/Users/Usuario/Documents/GitHub/fidifactu/node_modules/unplugin-vue-router/dist/vite.mjs";
import { defineConfig } from "file:///C:/Users/Usuario/Documents/GitHub/fidifactu/node_modules/vite/dist/node/index.js";
import VueDevTools from "file:///C:/Users/Usuario/Documents/GitHub/fidifactu/node_modules/vite-plugin-vue-devtools/dist/vite.mjs";
import Layouts from "file:///C:/Users/Usuario/Documents/GitHub/fidifactu/node_modules/vite-plugin-vue-layouts/dist/index.mjs";
import vuetify from "file:///C:/Users/Usuario/Documents/GitHub/fidifactu/node_modules/vite-plugin-vuetify/dist/index.mjs";
import svgLoader from "file:///C:/Users/Usuario/Documents/GitHub/fidifactu/node_modules/vite-svg-loader/index.js";
var __vite_injected_original_import_meta_url = "file:///C:/Users/Usuario/Documents/GitHub/fidifactu/vite.config.js";
var vite_config_default = defineConfig({
  plugins: [
    laravel({
      input: ["src/main.js"],
      refresh: true
    }),
    // Docs: https://github.com/posva/unplugin-vue-router
    // ℹ️ This plugin should be placed before vue plugin
    VueRouter({
      getRouteName: (routeNode) => {
        return getPascalCaseRouteName(routeNode).replace(/([a-z\d])([A-Z])/g, "$1-$2").toLowerCase();
      },
      beforeWriteFiles: (root) => {
      }
    }),
    vue({
      template: {
        compilerOptions: {
          isCustomElement: (tag) => tag === "swiper-container" || tag === "swiper-slide"
        }
      }
    }),
    VueDevTools(),
    vueJsx(),
    // Docs: https://github.com/vuetifyjs/vuetify-loader/tree/master/packages/vite-plugin
    vuetify({
      styles: {
        configFile: "src/assets/styles/variables/_vuetify.scss"
      },
      autoImport: true
    }),
    // Docs: https://github.com/johncampionjr/vite-plugin-vue-layouts#vite-plugin-vue-layouts
    Layouts({
      layoutsDirs: "./src/layouts/"
    }),
    // Docs: https://github.com/antfu/unplugin-vue-components#unplugin-vue-components
    Components({
      dirs: ["src/@core/components", "src/views/demos", "src/components"],
      dts: true,
      resolvers: [
        (componentName) => {
          if (componentName === "VueApexCharts")
            return {
              name: "default",
              from: "vue3-apexcharts",
              as: "VueApexCharts"
            };
        }
      ]
    }),
    // Docs: https://github.com/antfu/unplugin-auto-import#unplugin-auto-import
    AutoImport({
      imports: [
        "vue",
        VueRouterAutoImports,
        "@vueuse/core",
        "@vueuse/math",
        "vue-i18n",
        "pinia"
      ],
      dirs: [
        "./src/@core/utils",
        "./src/@core/composable/",
        "./src/composables/",
        "./src/utils/",
        "./src/plugins/*/composables/*"
      ],
      vueTemplate: true,
      // ℹ️ Disabled to avoid confusion & accidental usage
      ignore: ["useCookies", "useStorage"],
      eslintrc: {
        enabled: true,
        filepath: "./.eslintrc-auto-import.json"
      }
    }),
    // Docs: https://github.com/intlify/bundle-tools/tree/main/packages/unplugin-vue-i18n#intlifyunplugin-vue-i18n
    VueI18nPlugin({
      runtimeOnly: true,
      compositionOnly: true,
      include: [
        fileURLToPath(
          new URL("./src/plugins/i18n/locales/**", __vite_injected_original_import_meta_url)
        )
      ]
    }),
    svgLoader()
  ],
  define: { "process.env": {} },
  resolve: {
    alias: {
      "@": fileURLToPath(new URL("./src", __vite_injected_original_import_meta_url)),
      "@themeConfig": fileURLToPath(
        new URL("./themeConfig.js", __vite_injected_original_import_meta_url)
      ),
      "@core": fileURLToPath(new URL("./src/@core", __vite_injected_original_import_meta_url)),
      "@layouts": fileURLToPath(
        new URL("./src/@layouts", __vite_injected_original_import_meta_url)
      ),
      "@images": fileURLToPath(
        new URL("./src/assets/images/", __vite_injected_original_import_meta_url)
      ),
      "@styles": fileURLToPath(
        new URL("./src/assets/styles/", __vite_injected_original_import_meta_url)
      ),
      "@configured-variables": fileURLToPath(
        new URL(
          "./src/assets/styles/variables/_template.scss",
          __vite_injected_original_import_meta_url
        )
      ),
      "@db": fileURLToPath(
        new URL("./src/plugins/fake-api/handlers/", __vite_injected_original_import_meta_url)
      ),
      "@api-utils": fileURLToPath(
        new URL("./src/plugins/fake-api/utils/", __vite_injected_original_import_meta_url)
      )
    }
  },
  build: {
    chunkSizeWarningLimit: 5e3
  },
  optimizeDeps: {
    exclude: ["vuetify"],
    entries: ["./src/**/*.vue"]
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJDOlxcXFxVc2Vyc1xcXFxVc3VhcmlvXFxcXERvY3VtZW50c1xcXFxHaXRIdWJcXFxcZmlkaWZhY3R1XCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ZpbGVuYW1lID0gXCJDOlxcXFxVc2Vyc1xcXFxVc3VhcmlvXFxcXERvY3VtZW50c1xcXFxHaXRIdWJcXFxcZmlkaWZhY3R1XFxcXHZpdGUuY29uZmlnLmpzXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ltcG9ydF9tZXRhX3VybCA9IFwiZmlsZTovLy9DOi9Vc2Vycy9Vc3VhcmlvL0RvY3VtZW50cy9HaXRIdWIvZmlkaWZhY3R1L3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IFZ1ZUkxOG5QbHVnaW4gZnJvbSBcIkBpbnRsaWZ5L3VucGx1Z2luLXZ1ZS1pMThuL3ZpdGVcIjtcclxuaW1wb3J0IHZ1ZSBmcm9tIFwiQHZpdGVqcy9wbHVnaW4tdnVlXCI7XHJcbmltcG9ydCB2dWVKc3ggZnJvbSBcIkB2aXRlanMvcGx1Z2luLXZ1ZS1qc3hcIjtcclxuaW1wb3J0IGxhcmF2ZWwgZnJvbSBcImxhcmF2ZWwtdml0ZS1wbHVnaW5cIjtcclxuaW1wb3J0IHsgZmlsZVVSTFRvUGF0aCB9IGZyb20gXCJub2RlOnVybFwiO1xyXG5pbXBvcnQgQXV0b0ltcG9ydCBmcm9tIFwidW5wbHVnaW4tYXV0by1pbXBvcnQvdml0ZVwiO1xyXG5pbXBvcnQgQ29tcG9uZW50cyBmcm9tIFwidW5wbHVnaW4tdnVlLWNvbXBvbmVudHMvdml0ZVwiO1xyXG5pbXBvcnQge1xyXG4gICAgVnVlUm91dGVyQXV0b0ltcG9ydHMsXHJcbiAgICBnZXRQYXNjYWxDYXNlUm91dGVOYW1lLFxyXG59IGZyb20gXCJ1bnBsdWdpbi12dWUtcm91dGVyXCI7XHJcbmltcG9ydCBWdWVSb3V0ZXIgZnJvbSBcInVucGx1Z2luLXZ1ZS1yb3V0ZXIvdml0ZVwiO1xyXG5pbXBvcnQgeyBkZWZpbmVDb25maWcgfSBmcm9tIFwidml0ZVwiO1xyXG5pbXBvcnQgVnVlRGV2VG9vbHMgZnJvbSBcInZpdGUtcGx1Z2luLXZ1ZS1kZXZ0b29sc1wiO1xyXG5pbXBvcnQgTGF5b3V0cyBmcm9tIFwidml0ZS1wbHVnaW4tdnVlLWxheW91dHNcIjtcclxuaW1wb3J0IHZ1ZXRpZnkgZnJvbSBcInZpdGUtcGx1Z2luLXZ1ZXRpZnlcIjtcclxuaW1wb3J0IHN2Z0xvYWRlciBmcm9tIFwidml0ZS1zdmctbG9hZGVyXCI7XHJcblxyXG4vLyBodHRwczovL3ZpdGVqcy5kZXYvY29uZmlnL1xyXG5leHBvcnQgZGVmYXVsdCBkZWZpbmVDb25maWcoe1xyXG4gICAgcGx1Z2luczogW1xyXG4gICAgICAgIGxhcmF2ZWwoe1xyXG4gICAgICAgICAgICBpbnB1dDogW1wic3JjL21haW4uanNcIl0sXHJcbiAgICAgICAgICAgIHJlZnJlc2g6IHRydWUsXHJcbiAgICAgICAgfSksXHJcbiAgICAgICAgLy8gRG9jczogaHR0cHM6Ly9naXRodWIuY29tL3Bvc3ZhL3VucGx1Z2luLXZ1ZS1yb3V0ZXJcclxuICAgICAgICAvLyBcdTIxMzlcdUZFMEYgVGhpcyBwbHVnaW4gc2hvdWxkIGJlIHBsYWNlZCBiZWZvcmUgdnVlIHBsdWdpblxyXG4gICAgICAgIFZ1ZVJvdXRlcih7XHJcbiAgICAgICAgICAgIGdldFJvdXRlTmFtZTogKHJvdXRlTm9kZSkgPT4ge1xyXG4gICAgICAgICAgICAgICAgLy8gQ29udmVydCBwYXNjYWwgY2FzZSB0byBrZWJhYiBjYXNlXHJcbiAgICAgICAgICAgICAgICByZXR1cm4gZ2V0UGFzY2FsQ2FzZVJvdXRlTmFtZShyb3V0ZU5vZGUpXHJcbiAgICAgICAgICAgICAgICAgICAgLnJlcGxhY2UoLyhbYS16XFxkXSkoW0EtWl0pL2csIFwiJDEtJDJcIilcclxuICAgICAgICAgICAgICAgICAgICAudG9Mb3dlckNhc2UoKTtcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgYmVmb3JlV3JpdGVGaWxlczogKHJvb3QpID0+IHtcclxuICAgICAgICAgICAgICAgIC8vIHJvb3QuaW5zZXJ0KCcvYXBwcy9lbWFpbC86ZmlsdGVyJywgJy9zcmMvcGFnZXMvYXBwcy9lbWFpbC9pbmRleC52dWUnKVxyXG4gICAgICAgICAgICAgICAgLy8gcm9vdC5pbnNlcnQoJy9hcHBzL2VtYWlsLzpsYWJlbCcsICcvc3JjL3BhZ2VzL2FwcHMvZW1haWwvaW5kZXgudnVlJylcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICB9KSxcclxuICAgICAgICB2dWUoe1xyXG4gICAgICAgICAgICB0ZW1wbGF0ZToge1xyXG4gICAgICAgICAgICAgICAgY29tcGlsZXJPcHRpb25zOiB7XHJcbiAgICAgICAgICAgICAgICAgICAgaXNDdXN0b21FbGVtZW50OiAodGFnKSA9PlxyXG4gICAgICAgICAgICAgICAgICAgICAgICB0YWcgPT09IFwic3dpcGVyLWNvbnRhaW5lclwiIHx8IHRhZyA9PT0gXCJzd2lwZXItc2xpZGVcIixcclxuICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIH0sXHJcbiAgICAgICAgfSksXHJcbiAgICAgICAgVnVlRGV2VG9vbHMoKSxcclxuICAgICAgICB2dWVKc3goKSxcclxuXHJcbiAgICAgICAgLy8gRG9jczogaHR0cHM6Ly9naXRodWIuY29tL3Z1ZXRpZnlqcy92dWV0aWZ5LWxvYWRlci90cmVlL21hc3Rlci9wYWNrYWdlcy92aXRlLXBsdWdpblxyXG4gICAgICAgIHZ1ZXRpZnkoe1xyXG4gICAgICAgICAgICBzdHlsZXM6IHtcclxuICAgICAgICAgICAgICAgIGNvbmZpZ0ZpbGU6IFwic3JjL2Fzc2V0cy9zdHlsZXMvdmFyaWFibGVzL192dWV0aWZ5LnNjc3NcIixcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgYXV0b0ltcG9ydDogdHJ1ZSxcclxuICAgICAgICB9KSxcclxuXHJcbiAgICAgICAgLy8gRG9jczogaHR0cHM6Ly9naXRodWIuY29tL2pvaG5jYW1waW9uanIvdml0ZS1wbHVnaW4tdnVlLWxheW91dHMjdml0ZS1wbHVnaW4tdnVlLWxheW91dHNcclxuICAgICAgICBMYXlvdXRzKHtcclxuICAgICAgICAgICAgbGF5b3V0c0RpcnM6IFwiLi9zcmMvbGF5b3V0cy9cIixcclxuICAgICAgICB9KSxcclxuXHJcbiAgICAgICAgLy8gRG9jczogaHR0cHM6Ly9naXRodWIuY29tL2FudGZ1L3VucGx1Z2luLXZ1ZS1jb21wb25lbnRzI3VucGx1Z2luLXZ1ZS1jb21wb25lbnRzXHJcbiAgICAgICAgQ29tcG9uZW50cyh7XHJcbiAgICAgICAgICAgIGRpcnM6IFtcInNyYy9AY29yZS9jb21wb25lbnRzXCIsIFwic3JjL3ZpZXdzL2RlbW9zXCIsIFwic3JjL2NvbXBvbmVudHNcIl0sXHJcbiAgICAgICAgICAgIGR0czogdHJ1ZSxcclxuICAgICAgICAgICAgcmVzb2x2ZXJzOiBbXHJcbiAgICAgICAgICAgICAgICAoY29tcG9uZW50TmFtZSkgPT4ge1xyXG4gICAgICAgICAgICAgICAgICAgIC8vIEF1dG8gaW1wb3J0IGBWdWVBcGV4Q2hhcnRzYFxyXG4gICAgICAgICAgICAgICAgICAgIGlmIChjb21wb25lbnROYW1lID09PSBcIlZ1ZUFwZXhDaGFydHNcIilcclxuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG5hbWU6IFwiZGVmYXVsdFwiLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZnJvbTogXCJ2dWUzLWFwZXhjaGFydHNcIixcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGFzOiBcIlZ1ZUFwZXhDaGFydHNcIixcclxuICAgICAgICAgICAgICAgICAgICAgICAgfTtcclxuICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIF0sXHJcbiAgICAgICAgfSksXHJcblxyXG4gICAgICAgIC8vIERvY3M6IGh0dHBzOi8vZ2l0aHViLmNvbS9hbnRmdS91bnBsdWdpbi1hdXRvLWltcG9ydCN1bnBsdWdpbi1hdXRvLWltcG9ydFxyXG4gICAgICAgIEF1dG9JbXBvcnQoe1xyXG4gICAgICAgICAgICBpbXBvcnRzOiBbXHJcbiAgICAgICAgICAgICAgICBcInZ1ZVwiLFxyXG4gICAgICAgICAgICAgICAgVnVlUm91dGVyQXV0b0ltcG9ydHMsXHJcbiAgICAgICAgICAgICAgICBcIkB2dWV1c2UvY29yZVwiLFxyXG4gICAgICAgICAgICAgICAgXCJAdnVldXNlL21hdGhcIixcclxuICAgICAgICAgICAgICAgIFwidnVlLWkxOG5cIixcclxuICAgICAgICAgICAgICAgIFwicGluaWFcIixcclxuICAgICAgICAgICAgXSxcclxuICAgICAgICAgICAgZGlyczogW1xyXG4gICAgICAgICAgICAgICAgXCIuL3NyYy9AY29yZS91dGlsc1wiLFxyXG4gICAgICAgICAgICAgICAgXCIuL3NyYy9AY29yZS9jb21wb3NhYmxlL1wiLFxyXG4gICAgICAgICAgICAgICAgXCIuL3NyYy9jb21wb3NhYmxlcy9cIixcclxuICAgICAgICAgICAgICAgIFwiLi9zcmMvdXRpbHMvXCIsXHJcbiAgICAgICAgICAgICAgICBcIi4vc3JjL3BsdWdpbnMvKi9jb21wb3NhYmxlcy8qXCIsXHJcbiAgICAgICAgICAgIF0sXHJcbiAgICAgICAgICAgIHZ1ZVRlbXBsYXRlOiB0cnVlLFxyXG5cclxuICAgICAgICAgICAgLy8gXHUyMTM5XHVGRTBGIERpc2FibGVkIHRvIGF2b2lkIGNvbmZ1c2lvbiAmIGFjY2lkZW50YWwgdXNhZ2VcclxuICAgICAgICAgICAgaWdub3JlOiBbXCJ1c2VDb29raWVzXCIsIFwidXNlU3RvcmFnZVwiXSxcclxuICAgICAgICAgICAgZXNsaW50cmM6IHtcclxuICAgICAgICAgICAgICAgIGVuYWJsZWQ6IHRydWUsXHJcbiAgICAgICAgICAgICAgICBmaWxlcGF0aDogXCIuLy5lc2xpbnRyYy1hdXRvLWltcG9ydC5qc29uXCIsXHJcbiAgICAgICAgICAgIH0sXHJcbiAgICAgICAgfSksXHJcblxyXG4gICAgICAgIC8vIERvY3M6IGh0dHBzOi8vZ2l0aHViLmNvbS9pbnRsaWZ5L2J1bmRsZS10b29scy90cmVlL21haW4vcGFja2FnZXMvdW5wbHVnaW4tdnVlLWkxOG4jaW50bGlmeXVucGx1Z2luLXZ1ZS1pMThuXHJcbiAgICAgICAgVnVlSTE4blBsdWdpbih7XHJcbiAgICAgICAgICAgIHJ1bnRpbWVPbmx5OiB0cnVlLFxyXG4gICAgICAgICAgICBjb21wb3NpdGlvbk9ubHk6IHRydWUsXHJcbiAgICAgICAgICAgIGluY2x1ZGU6IFtcclxuICAgICAgICAgICAgICAgIGZpbGVVUkxUb1BhdGgoXHJcbiAgICAgICAgICAgICAgICAgICAgbmV3IFVSTChcIi4vc3JjL3BsdWdpbnMvaTE4bi9sb2NhbGVzLyoqXCIsIGltcG9ydC5tZXRhLnVybClcclxuICAgICAgICAgICAgICAgICksXHJcbiAgICAgICAgICAgIF0sXHJcbiAgICAgICAgfSksXHJcbiAgICAgICAgc3ZnTG9hZGVyKCksXHJcbiAgICBdLFxyXG4gICAgZGVmaW5lOiB7IFwicHJvY2Vzcy5lbnZcIjoge30gfSxcclxuICAgIHJlc29sdmU6IHtcclxuICAgICAgICBhbGlhczoge1xyXG4gICAgICAgICAgICBcIkBcIjogZmlsZVVSTFRvUGF0aChuZXcgVVJMKFwiLi9zcmNcIiwgaW1wb3J0Lm1ldGEudXJsKSksXHJcbiAgICAgICAgICAgIFwiQHRoZW1lQ29uZmlnXCI6IGZpbGVVUkxUb1BhdGgoXHJcbiAgICAgICAgICAgICAgICBuZXcgVVJMKFwiLi90aGVtZUNvbmZpZy5qc1wiLCBpbXBvcnQubWV0YS51cmwpXHJcbiAgICAgICAgICAgICksXHJcbiAgICAgICAgICAgIFwiQGNvcmVcIjogZmlsZVVSTFRvUGF0aChuZXcgVVJMKFwiLi9zcmMvQGNvcmVcIiwgaW1wb3J0Lm1ldGEudXJsKSksXHJcbiAgICAgICAgICAgIFwiQGxheW91dHNcIjogZmlsZVVSTFRvUGF0aChcclxuICAgICAgICAgICAgICAgIG5ldyBVUkwoXCIuL3NyYy9AbGF5b3V0c1wiLCBpbXBvcnQubWV0YS51cmwpXHJcbiAgICAgICAgICAgICksXHJcbiAgICAgICAgICAgIFwiQGltYWdlc1wiOiBmaWxlVVJMVG9QYXRoKFxyXG4gICAgICAgICAgICAgICAgbmV3IFVSTChcIi4vc3JjL2Fzc2V0cy9pbWFnZXMvXCIsIGltcG9ydC5tZXRhLnVybClcclxuICAgICAgICAgICAgKSxcclxuICAgICAgICAgICAgXCJAc3R5bGVzXCI6IGZpbGVVUkxUb1BhdGgoXHJcbiAgICAgICAgICAgICAgICBuZXcgVVJMKFwiLi9zcmMvYXNzZXRzL3N0eWxlcy9cIiwgaW1wb3J0Lm1ldGEudXJsKVxyXG4gICAgICAgICAgICApLFxyXG4gICAgICAgICAgICBcIkBjb25maWd1cmVkLXZhcmlhYmxlc1wiOiBmaWxlVVJMVG9QYXRoKFxyXG4gICAgICAgICAgICAgICAgbmV3IFVSTChcclxuICAgICAgICAgICAgICAgICAgICBcIi4vc3JjL2Fzc2V0cy9zdHlsZXMvdmFyaWFibGVzL190ZW1wbGF0ZS5zY3NzXCIsXHJcbiAgICAgICAgICAgICAgICAgICAgaW1wb3J0Lm1ldGEudXJsXHJcbiAgICAgICAgICAgICAgICApXHJcbiAgICAgICAgICAgICksXHJcbiAgICAgICAgICAgIFwiQGRiXCI6IGZpbGVVUkxUb1BhdGgoXHJcbiAgICAgICAgICAgICAgICBuZXcgVVJMKFwiLi9zcmMvcGx1Z2lucy9mYWtlLWFwaS9oYW5kbGVycy9cIiwgaW1wb3J0Lm1ldGEudXJsKVxyXG4gICAgICAgICAgICApLFxyXG4gICAgICAgICAgICBcIkBhcGktdXRpbHNcIjogZmlsZVVSTFRvUGF0aChcclxuICAgICAgICAgICAgICAgIG5ldyBVUkwoXCIuL3NyYy9wbHVnaW5zL2Zha2UtYXBpL3V0aWxzL1wiLCBpbXBvcnQubWV0YS51cmwpXHJcbiAgICAgICAgICAgICksXHJcbiAgICAgICAgfSxcclxuICAgIH0sXHJcbiAgICBidWlsZDoge1xyXG4gICAgICAgIGNodW5rU2l6ZVdhcm5pbmdMaW1pdDogNTAwMCxcclxuICAgIH0sXHJcbiAgICBvcHRpbWl6ZURlcHM6IHtcclxuICAgICAgICBleGNsdWRlOiBbXCJ2dWV0aWZ5XCJdLFxyXG4gICAgICAgIGVudHJpZXM6IFtcIi4vc3JjLyoqLyoudnVlXCJdLFxyXG4gICAgfSxcclxufSk7XHJcbiJdLAogICJtYXBwaW5ncyI6ICI7QUFBK1QsT0FBTyxtQkFBbUI7QUFDelYsT0FBTyxTQUFTO0FBQ2hCLE9BQU8sWUFBWTtBQUNuQixPQUFPLGFBQWE7QUFDcEIsU0FBUyxxQkFBcUI7QUFDOUIsT0FBTyxnQkFBZ0I7QUFDdkIsT0FBTyxnQkFBZ0I7QUFDdkI7QUFBQSxFQUNJO0FBQUEsRUFDQTtBQUFBLE9BQ0c7QUFDUCxPQUFPLGVBQWU7QUFDdEIsU0FBUyxvQkFBb0I7QUFDN0IsT0FBTyxpQkFBaUI7QUFDeEIsT0FBTyxhQUFhO0FBQ3BCLE9BQU8sYUFBYTtBQUNwQixPQUFPLGVBQWU7QUFoQm1MLElBQU0sMkNBQTJDO0FBbUIxUCxJQUFPLHNCQUFRLGFBQWE7QUFBQSxFQUN4QixTQUFTO0FBQUEsSUFDTCxRQUFRO0FBQUEsTUFDSixPQUFPLENBQUMsYUFBYTtBQUFBLE1BQ3JCLFNBQVM7QUFBQSxJQUNiLENBQUM7QUFBQTtBQUFBO0FBQUEsSUFHRCxVQUFVO0FBQUEsTUFDTixjQUFjLENBQUMsY0FBYztBQUV6QixlQUFPLHVCQUF1QixTQUFTLEVBQ2xDLFFBQVEscUJBQXFCLE9BQU8sRUFDcEMsWUFBWTtBQUFBLE1BQ3JCO0FBQUEsTUFDQSxrQkFBa0IsQ0FBQyxTQUFTO0FBQUEsTUFHNUI7QUFBQSxJQUNKLENBQUM7QUFBQSxJQUNELElBQUk7QUFBQSxNQUNBLFVBQVU7QUFBQSxRQUNOLGlCQUFpQjtBQUFBLFVBQ2IsaUJBQWlCLENBQUMsUUFDZCxRQUFRLHNCQUFzQixRQUFRO0FBQUEsUUFDOUM7QUFBQSxNQUNKO0FBQUEsSUFDSixD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
