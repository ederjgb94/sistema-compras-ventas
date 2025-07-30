// @ts-check
import { test, expect } from '@playwright/test';

test.describe('Login Debug', () => {
    test('debug login process', async ({ page }) => {
        // Ir a la página de login
        await page.goto('/login');

        console.log('URL después de ir a /login:', await page.url());

        // Verificar que el campo de email está visible
        const emailField = page.locator('input[type="email"]');
        await expect(emailField).toBeVisible();

        // Verificar que el campo de password está visible
        const passwordField = page.locator('input[type="password"]');
        await expect(passwordField).toBeVisible();

        // Verificar que el botón submit está visible
        const submitButton = page.locator('button[type="submit"]');
        await expect(submitButton).toBeVisible();

        console.log('Todos los campos están visibles');

        // Rellenar las credenciales
        await emailField.fill('admin@admin.com');
        await passwordField.fill('password');

        console.log('Credenciales llenadas');

        // Escuchar las requests de red
        page.on('response', response => {
            console.log('Response:', response.url(), response.status());
        });

        // Hacer clic en el botón de submit
        await submitButton.click();

        console.log('Click en submit realizado');

        // Esperar un poco para ver qué sucede
        await page.waitForTimeout(3000);

        console.log('URL después del submit:', await page.url());

        // Verificar si hay mensajes de error
        const errorMessages = await page.locator('[role="alert"], .error, .text-red-500, .text-red-600').allTextContents();
        if (errorMessages.length > 0) {
            console.log('Mensajes de error encontrados:', errorMessages);
        }

        // Verificar el contenido de la página
        const pageContent = await page.textContent('body');
        console.log('Contenido de la página (primeros 500 caracteres):', pageContent?.substring(0, 500));
    });
});
