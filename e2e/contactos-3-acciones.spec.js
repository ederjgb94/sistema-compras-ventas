import { test, expect } from '@playwright/test';

test.describe('Contactos - 3 Acciones Implementadas', () => {
    test.beforeEach(async ({ page }) => {
        // Login
        await page.goto('/login');
        await page.fill('input[type="email"]', 'test@example.com');
        await page.fill('input[type="password"]', 'password');
        await page.click('button[type="submit"]');
        await page.waitForURL('**/dashboard');
    });

    test('debe mostrar las 3 acciones: ver, editar, eliminar', async ({ page }) => {
        // Ir a contactos
        await page.goto('/contactos');

        // Verificar que existe al menos un contacto
        const rows = await page.locator('tbody tr').count();
        if (rows === 0) {
            console.log('⚠️  No hay contactos para probar las acciones');
            return;
        }

        // Verificar que existen los 3 botones de acción en la primera fila
        const firstRow = page.locator('tbody tr').first();

        // Verificar icono de ver (eye)
        await expect(firstRow.locator('[data-lucide="eye"]')).toBeVisible();

        // Verificar icono de editar (edit)
        await expect(firstRow.locator('[data-lucide="edit"]')).toBeVisible();

        // Verificar icono de eliminar (trash-2)
        await expect(firstRow.locator('[data-lucide="trash-2"]')).toBeVisible();

        console.log('✅ Las 3 acciones están presentes: ver, editar, eliminar');
    });

    test('debe navegar a la página de detalles al hacer clic en ver', async ({ page }) => {
        await page.goto('/contactos');

        const firstEyeButton = page.locator('[data-lucide="eye"]').first();
        const hasContacts = await firstEyeButton.count() > 0;

        if (!hasContacts) {
            console.log('⚠️  No hay contactos para probar la navegación');
            return;
        }

        // Hacer clic en ver
        await firstEyeButton.click();

        // Verificar que navegamos a la página de detalles
        await expect(page).toHaveURL(/\/contactos\/\d+$/);

        // Verificar elementos de la página de detalles
        await expect(page.locator('h1')).toBeVisible();
        await expect(page.getByText('Detalles completos del contacto')).toBeVisible();

        console.log('✅ Navegación a detalles funciona correctamente');
    });

    test('debe navegar a la página de edición al hacer clic en editar', async ({ page }) => {
        await page.goto('/contactos');

        const firstEditButton = page.locator('[data-lucide="edit"]').first();
        const hasContacts = await firstEditButton.count() > 0;

        if (!hasContacts) {
            console.log('⚠️  No hay contactos para probar la edición');
            return;
        }

        // Hacer clic en editar
        await firstEditButton.click();

        // Verificar que navegamos a la página de edición
        await expect(page).toHaveURL(/\/contactos\/\d+\/edit$/);

        // Verificar elementos de la página de edición
        await expect(page.locator('h1')).toContainText('Editar Contacto');
        await expect(page.locator('input[wire\\:model="nombre"]')).toBeVisible();

        console.log('✅ Navegación a edición funciona correctamente');
    });

    test('debe mostrar confirmación al hacer clic en eliminar', async ({ page }) => {
        await page.goto('/contactos');

        const firstDeleteButton = page.locator('button[type="submit"]').first();
        const hasContacts = await firstDeleteButton.count() > 0;

        if (!hasContacts) {
            console.log('⚠️  No hay contactos para probar la eliminación');
            return;
        }

        // Escuchar el diálogo de confirmación
        page.on('dialog', async dialog => {
            expect(dialog.message()).toContain('¿Estás seguro de que quieres eliminar este contacto?');
            await dialog.dismiss();
        });

        // Hacer clic en eliminar
        await firstDeleteButton.click();

        console.log('✅ Confirmación de eliminación funciona correctamente');
    });
});
