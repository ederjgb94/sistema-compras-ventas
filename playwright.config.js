import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
    // Directorio donde están los tests
    testDir: './e2e',

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
});
