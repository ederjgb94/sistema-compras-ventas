const { defineConfig, devices } = require('@playwright/test');

module.exports = defineConfig({
    // Configuración específica para este proyecto
    use: {
        // URL base del proyecto
        baseURL: 'http://sistema-compras-ventas.test',

        // Configuración del viewport
        viewport: { width: 1280, height: 720 },

        // Ignorar errores HTTPS para desarrollo local
        ignoreHTTPSErrors: true,

        // Configuración para evitar pop-ups y pestañas en blanco
        launchOptions: {
            args: [
                '--disable-web-security',
                '--disable-popup-blocking',
                '--no-sandbox'
            ]
        },

        // Configuración de navegación
        navigationTimeout: 30000,
        actionTimeout: 10000,
    },

    // Proyectos de testing (opcional)
    projects: [
        {
            name: 'chromium',
            use: { ...devices['Desktop Chrome'] },
        },
    ],

    // Configuración del servidor web (si quieres que Playwright inicie el servidor)
    webServer: {
        command: 'php artisan serve --host=0.0.0.0 --port=8000',
        port: 8000,
        reuseExistingServer: !process.env.CI,
    },
});
