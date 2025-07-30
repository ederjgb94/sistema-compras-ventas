// @ts-check
import { test, expect } from '@playwright/test';

test.describe('Login Redirect Bug Fix - Validación Principal', () => {
    test('PRINCIPAL: usuario debe ser redirigido automáticamente al dashboard después del login sin refresh', async ({ page }) => {
        // Ir a la página de login
        await page.goto('/login');

        // Verificar que estamos en la página de login
        await expect(page).toHaveURL(/.*\/login/);

        // Rellenar credenciales correctas
        await page.fill('input[type="email"]', 'admin@admin.com');
        await page.fill('input[type="password"]', 'admin');

        // Hacer clic en el botón de login
        await page.click('button[type="submit"]');

        // ESTA ES LA VALIDACIÓN PRINCIPAL DEL BUG FIX:
        // El usuario debe ser redirigido automáticamente al dashboard SIN refrescar manualmente
        await expect(page).toHaveURL('/dashboard', { timeout: 10000 });

        // Verificar que estamos en el dashboard y no en el login
        await expect(page).not.toHaveURL(/.*\/login/);

        console.log('✅ BUG CORREGIDO: Login redirige automáticamente al dashboard');
    });

    test('CONFIRMACIÓN: usuario autenticado no puede volver a /login', async ({ page }) => {
        // Hacer login primero
        await page.goto('/login');
        await page.fill('input[type="email"]', 'admin@admin.com');
        await page.fill('input[type="password"]', 'admin');
        await page.click('button[type="submit"]');

        // Esperar redirección exitosa al dashboard
        await expect(page).toHaveURL('/dashboard');

        // Intentar ir a /login nuevamente
        await page.goto('/login');

        // Debe ser redirigido al dashboard porque ya está autenticado
        await expect(page).toHaveURL('/dashboard');

        console.log('✅ CONFIRMACIÓN: Protección de rutas funcionando correctamente');
    });
});
