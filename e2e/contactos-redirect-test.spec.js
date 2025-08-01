import { test, expect } from '@playwright/test';

test.describe('Contactos - Redirección después de editar', () => {
    test.beforeEach(async ({ page }) => {
        // Login
        await page.goto('/login');
        await page.fill('input[type="email"]', 'test@example.com');
        await page.fill('input[type="password"]', 'password');
        await page.click('button[type="submit"]');
        await page.waitForURL('**/dashboard');
    });

    test('debe redirigir a la lista de contactos después de guardar cambios', async ({ page }) => {
        // Ir a contactos
        await page.goto('/contactos');

        // Verificar que hay contactos
        const firstEditButton = page.locator('[data-lucide="edit"]').first();
        const hasContacts = await firstEditButton.count() > 0;

        if (!hasContacts) {
            console.log('⚠️  No hay contactos para probar la edición');
            return;
        }

        // Hacer clic en editar
        await firstEditButton.click();

        // Verificar que estamos en la página de edición
        await expect(page).toHaveURL(/\/contactos\/\d+\/edit$/);
        await expect(page.locator('h1').filter({ hasText: 'Editar Contacto' })).toBeVisible();

        // Hacer un cambio menor (agregar algo al nombre)
        const nombreInput = page.locator('input[wire\\:model="nombre"]');
        const nombreOriginal = await nombreInput.inputValue();
        await nombreInput.fill(nombreOriginal + ' - Editado');

        // Guardar los cambios
        await page.locator('button', { hasText: 'Guardar Cambios' }).click();

        // Verificar que redirige a la lista de contactos
        await expect(page).toHaveURL('/contactos');

        // Verificar que muestra el mensaje de éxito
        await expect(page.locator('text=Contacto actualizado exitosamente')).toBeVisible();

        console.log('✅ Redirección después de guardar funciona correctamente');
    });

    test('debe redirigir a la lista de contactos al cancelar', async ({ page }) => {
        await page.goto('/contactos');

        const firstEditButton = page.locator('[data-lucide="edit"]').first();
        const hasContacts = await firstEditButton.count() > 0;

        if (!hasContacts) {
            console.log('⚠️  No hay contactos para probar la cancelación');
            return;
        }

        // Ir a editar
        await firstEditButton.click();
        await expect(page).toHaveURL(/\/contactos\/\d+\/edit$/);

        // Hacer clic en cancelar
        await page.locator('button', { hasText: 'Cancelar' }).click();

        // Verificar que redirige a la lista de contactos
        await expect(page).toHaveURL('/contactos');

        console.log('✅ Redirección al cancelar funciona correctamente');
    });
});
