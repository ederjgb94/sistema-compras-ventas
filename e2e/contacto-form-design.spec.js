import { test, expect } from '@playwright/test';

test.describe('Formulario Crear Contacto - Diseño UI/UX', () => {
    test('debe mostrar un diseño consistente con el formulario de transacciones', async ({ page }) => {
        // Ir a la página de login
        await page.goto('/login');

        // Hacer login
        await page.fill('input[name="email"]', 'test@example.com');
        await page.fill('input[name="password"]', 'password');
        await page.click('button[type="submit"]');

        // Esperar a ser redirigido al dashboard
        await page.waitForURL('/dashboard');

        // Navegar a crear contacto
        await page.goto('/contactos/create');

        // Verificar elementos del header
        await expect(page.locator('h1')).toContainText('Crear Nuevo Contacto');
        await expect(page.locator('p')).toContainText('Completa la información para agregar un nuevo contacto');

        // Verificar que existe el icono indicativo
        await expect(page.locator('[data-lucide="user-plus"]')).toBeVisible();

        // Verificar que el icono tiene las clases de estilo correctas
        const iconContainer = page.locator('[data-lucide="user-plus"]').locator('..');
        await expect(iconContainer).toHaveClass(/bg-blue-100.*text-blue-600/);

        // Verificar estructura de secciones
        await expect(page.locator('text=Información básica')).toBeVisible();
        await expect(page.locator('text=Información de contacto')).toBeVisible();
        await expect(page.locator('text=Información adicional')).toBeVisible();

        // Verificar botones de acción
        await expect(page.locator('button', { hasText: 'Cancelar' })).toBeVisible();
        await expect(page.locator('button', { hasText: 'Crear Contacto' })).toBeVisible();

        // Tomar screenshot para documentación
        await page.screenshot({ path: 'test-results/contacto-form-design.png', fullPage: true });
    });

    test('debe tener un diseño responsive', async ({ page }) => {
        // Login
        await page.goto('/login');
        await page.fill('input[name="email"]', 'test@example.com');
        await page.fill('input[name="password"]', 'password');
        await page.click('button[type="submit"]');
        await page.waitForURL('/dashboard');

        // Ir a crear contacto
        await page.goto('/contactos/create');

        // Verificar en dispositivo móvil
        await page.setViewportSize({ width: 375, height: 667 });
        await expect(page.locator('h1')).toBeVisible();
        await expect(page.locator('[data-lucide="user-plus"]')).toBeVisible();

        // Verificar en tablet
        await page.setViewportSize({ width: 768, height: 1024 });
        await expect(page.locator('h1')).toBeVisible();
        await expect(page.locator('[data-lucide="user-plus"]')).toBeVisible();

        // Volver a desktop
        await page.setViewportSize({ width: 1280, height: 720 });
    });
});
