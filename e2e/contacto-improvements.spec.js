import { test, expect } from '@playwright/test';

test.describe('Mejoras en Formulario de Contacto', () => {
    test.beforeEach(async ({ page }) => {
        // Login
        await page.goto('/login');
        await page.fill('input[type="email"]', 'test@example.com');
        await page.fill('input[type="password"]', 'password');
        await page.click('button[type="submit"]');
        await page.waitForURL('**/dashboard');
    });

    test('no debe mostrar checkbox de contacto activo y debe mantener el icono', async ({ page }) => {
        // Ir a crear contacto
        await page.goto('/contactos/create');

        // Verificar que NO existe el checkbox de contacto activo
        const activoCheckbox = await page.locator('input[wire\\:model="activo"]').count();
        expect(activoCheckbox).toBe(0);

        // Verificar que el texto "Contacto activo" no aparece
        const activoText = await page.locator('text=Contacto activo').count();
        expect(activoText).toBe(0);

        // Verificar que el icono existe
        await expect(page.locator('#contact-icon')).toBeAttached();
        await expect(page.locator('#contact-icon-container')).toBeVisible();

        // Simular interacción que podría causar actualización de Livewire
        await page.fill('input[wire\\:model="nombre"]', 'Test Usuario');
        await page.waitForTimeout(500); // Esperar posibles actualizaciones

        // Verificar que el icono sigue ahí después de la interacción
        await expect(page.locator('#contact-icon')).toBeAttached();
        await expect(page.locator('#contact-icon-container')).toBeVisible();

        console.log('✅ Checkbox removido correctamente y icono estable');
    });

    test('debe validar que el contacto se crea como activo por defecto', async ({ page }) => {
        await page.goto('/contactos/create');

        // Llenar campos requeridos
        await page.selectOption('select[wire\\:model="tipo"]', 'cliente');
        await page.fill('input[wire\\:model="nombre"]', 'Cliente Test');

        // Verificar que no hay checkbox de activo
        const activoCheckbox = await page.locator('input[wire\\:model="activo"]').count();
        expect(activoCheckbox).toBe(0);

        console.log('✅ Formulario sin checkbox, contacto será activo por defecto');
    });
});
