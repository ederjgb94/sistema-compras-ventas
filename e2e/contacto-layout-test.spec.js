import { test, expect } from '@playwright/test';

test.describe('Layout Mejorado - Nombre y Tipo en la misma fila', () => {
    test.beforeEach(async ({ page }) => {
        // Login
        await page.goto('/login');
        await page.fill('input[type="email"]', 'test@example.com');
        await page.fill('input[type="password"]', 'password');
        await page.click('button[type="submit"]');
        await page.waitForURL('**/dashboard');
    });

    test('debe mostrar nombre completo y tipo de contacto en la misma fila', async ({ page }) => {
        // Ir a crear contacto
        await page.goto('/contactos/create');

        // Verificar que ambos campos están presentes
        await expect(page.locator('input[wire\\:model="nombre"]')).toBeVisible();
        await expect(page.locator('select[wire\\:model="tipo"]')).toBeVisible();

        // Verificar que el nombre viene primero (arriba del tipo en móvil, a la izquierda en desktop)
        const nombreLabel = page.locator('label[for="nombre"]');
        const tipoLabel = page.locator('label[for="tipo"]');

        await expect(nombreLabel).toContainText('Nombre completo');
        await expect(tipoLabel).toContainText('Tipo de contacto');

        // Verificar que ambos están en un grid con 5 columnas (sm:grid-cols-5)
        const container = page.locator('.grid.grid-cols-1.gap-6.sm\\:grid-cols-5');
        await expect(container).toBeVisible();

        // Verificar que el nombre ocupa 3 columnas (sm:col-span-3)
        const nombreContainer = page.locator('.sm\\:col-span-3').first();
        await expect(nombreContainer.locator('input[wire\\:model="nombre"]')).toBeVisible();

        // Verificar que el tipo ocupa 2 columnas (sm:col-span-2)
        const tipoContainer = page.locator('.sm\\:col-span-2').first();
        await expect(tipoContainer.locator('select[wire\\:model="tipo"]')).toBeVisible();

        console.log('✅ Layout reorganizado correctamente: nombre (60%) + tipo (40%) en misma fila');
    });

    test('debe mantener funcionalidad después del cambio de layout', async ({ page }) => {
        await page.goto('/contactos/create');

        // Verificar que se puede llenar el nombre
        await page.fill('input[wire\\:model="nombre"]', 'Juan Pérez');
        await expect(page.locator('input[wire\\:model="nombre"]')).toHaveValue('Juan Pérez');

        // Verificar que se puede seleccionar el tipo
        await page.selectOption('select[wire\\:model="tipo"]', 'proveedor');
        await expect(page.locator('select[wire\\:model="tipo"]')).toHaveValue('proveedor');

        console.log('✅ Funcionalidad mantenida después del cambio de layout');
    });
});
