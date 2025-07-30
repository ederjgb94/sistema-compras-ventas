import { test, expect } from '@playwright/test';

test.describe('Validación Visual del Formulario de Contacto', () => {
    test.beforeEach(async ({ page }) => {
        // Login
        await page.goto('/login');
        await page.fill('input[type="email"]', 'test@example.com');
        await page.fill('input[type="password"]', 'password');
        await page.click('button[type="submit"]');
        await page.waitForURL('**/dashboard');
    });

    test('el formulario de crear contacto debe tener el diseño mejorado', async ({ page }) => {
        // Navegar a crear contacto
        await page.goto('/contactos/create');

        // Esperar a que la página se cargue completamente
        await page.waitForLoadState('domcontentloaded');

        // Verificar título y descripción
        await expect(page.locator('h1')).toContainText('Crear Nuevo Contacto');
        await expect(page.locator('p').first()).toContainText('Completa la información');

        // Verificar que el contenedor del icono existe (buscar por clase w-12 h-12 que es única del icono)
        const iconContainer = page.locator('.w-12.h-12.rounded-lg');
        await expect(iconContainer).toBeVisible();

        // Verificar que el elemento del icono existe
        await expect(page.locator('[data-lucide="user-plus"]')).toBeAttached();

        // Verificar breadcrumb
        await expect(page.locator('nav[aria-label="Breadcrumb"]')).toBeVisible();
        await expect(page.locator('a', { hasText: 'Contactos' })).toBeVisible();

        // Verificar secciones organizadas
        await expect(page.getByText('Información básica')).toBeVisible();
        await expect(page.getByText('Información de contacto')).toBeVisible();
        await expect(page.getByText('Información adicional')).toBeVisible();

        // Verificar campos principales
        await expect(page.locator('select[wire\\:model="tipo"]')).toBeVisible();
        await expect(page.locator('input[wire\\:model="nombre"]')).toBeVisible();
        await expect(page.locator('input[wire\\:model="email"]')).toBeVisible();

        // Verificar botones con estilo correcto
        const cancelButton = page.locator('button', { hasText: 'Cancelar' });
        const submitButton = page.locator('button', { hasText: 'Crear Contacto' });

        await expect(cancelButton).toBeVisible();
        await expect(submitButton).toBeVisible();
        await expect(submitButton).toHaveClass(/bg-blue-600/);

        console.log('✅ Formulario de crear contacto tiene el diseño mejorado correctamente');
    });
});
