// @ts-check
import { test, expect } from '@playwright/test';

test.describe('Login Redirect Bug Fix', () => {
    test('usuario debe ser redirigido automáticamente al dashboard después del login', async ({ page }) => {
        // Ir a la página de login
        await page.goto('/login');

        // Verificar que estamos en la página de login
        await expect(page).toHaveURL(/.*\/login/);
        // Verificar que vemos el formulario de login buscando el campo de email
        await expect(page.locator('input[type="email"]')).toBeVisible();

        // Limpiar el campo de email y rellenar con credenciales válidas
        await page.fill('input[type="email"]', 'admin@admin.com');
        await page.fill('input[type="password"]', 'admin');

        // Hacer clic en el botón de login
        await page.click('button[type="submit"]');

        // Verificar que somos redirigidos automáticamente al dashboard SIN refrescar
        await expect(page).toHaveURL('/dashboard', { timeout: 10000 });

        // Verificar que el dashboard se carga correctamente
        await expect(page.locator('h1')).toContainText('Tablero de Control');

        // Verificar que vemos el contenido del dashboard (transacciones recientes)
        await expect(page.locator('text=Transacciones Recientes')).toBeVisible();

        console.log('✅ Login redirect funciona correctamente');
    });

    test('usuario autenticado no debe poder acceder a /login', async ({ page }) => {
        // Primero hacer login
        await page.goto('/login');
        await page.fill('input[type="email"]', 'admin@admin.com');
        await page.fill('input[type="password"]', 'admin');
        await page.click('button[type="submit"]');

        // Esperar redirección al dashboard
        await expect(page).toHaveURL('/dashboard');

        // Intentar ir directamente a /login
        await page.goto('/login');

        // Debe redirigir al dashboard porque ya estamos autenticados
        await expect(page).toHaveURL('/dashboard');

        console.log('✅ Usuario autenticado no puede acceder a /login');
    });

    test('verificar que el estado de autenticación persiste al navegar', async ({ page }) => {
        // Hacer login
        await page.goto('/login');
        await page.fill('input[type="email"]', 'admin@admin.com');
        await page.fill('input[type="password"]', 'admin');
        await page.click('button[type="submit"]');

        // Esperar redirección al dashboard
        await expect(page).toHaveURL('/dashboard');

        // Navegar a otra página que requiere autenticación
        await page.click('text=Transacciones');
        await page.waitForTimeout(500); // Esperar que se despliegue el menú
        await page.click('text=Ver Todos');
        await expect(page).toHaveURL('/transacciones');

        // Verificar que no somos redirigidos al login
        await expect(page.locator('h1')).toContainText('Transacciones');

        // Volver al dashboard
        await page.click('a[href="/dashboard"]');
        await expect(page).toHaveURL('/dashboard');

        console.log('✅ Estado de autenticación persiste correctamente');
    });
});
