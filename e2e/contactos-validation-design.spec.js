import { test, expect } from '@playwright/test';

test.describe('Contactos - Estabilidad del diseño en validación', () => {
    test.beforeEach(async ({ page }) => {
        // Login
        await page.goto('/login');
        await page.fill('input[type="email"]', 'test@example.com');
        await page.fill('input[type="password"]', 'password');
        await page.click('button[type="submit"]');
        await page.waitForURL('**/dashboard');
    });

    test('el diseño debe mantenerse estable cuando hay errores de validación', async ({ page }) => {
        // Ir al formulario de crear contacto
        await page.goto('/contactos/create');

        // Verificar diseño inicial
        await expect(page.locator('h1').filter({ hasText: 'Crear Nuevo Contacto' })).toBeVisible();

        // Verificar que el icono esté presente inicialmente
        const iconoInicial = page.locator('[data-lucide="user-plus"]');
        await expect(iconoInicial).toBeVisible();

        // Capturar estado inicial del contenedor del icono
        const iconContainerInitial = page.locator('.w-12.h-12.rounded-lg.bg-blue-100');
        await expect(iconContainerInitial).toBeVisible();

        console.log('✅ Diseño inicial verificado');

        // Intentar enviar formulario vacío (provocar error de validación)
        await page.click('button[type="submit"]');

        // Esperar a que aparezca el error de validación
        await expect(page.locator('text=El campo nombre es obligatorio')).toBeVisible({ timeout: 5000 });

        console.log('✅ Error de validación mostrado');

        // Verificar que el icono sigue presente después del error
        await expect(iconoInicial).toBeVisible();

        // Verificar que el contenedor del icono mantiene sus clases
        await expect(iconContainerInitial).toBeVisible();

        // Verificar que el título sigue visible y con el formato correcto
        await expect(page.locator('h1').filter({ hasText: 'Crear Nuevo Contacto' })).toBeVisible();

        // Verificar que el breadcrumb sigue funcionando
        await expect(page.locator('[data-lucide="users"]')).toBeVisible();
        await expect(page.locator('[data-lucide="chevron-right"]')).toBeVisible();

        console.log('✅ Diseño estable después del error de validación');

        // Llenar el campo nombre y enviar de nuevo
        await page.fill('input[wire\\:model="nombre"]', 'Test Contact');
        await page.click('button[type="submit"]');

        // Verificar redirección exitosa
        await expect(page).toHaveURL('/contactos');
        await expect(page.locator('text=Contacto creado exitosamente')).toBeVisible();

        console.log('✅ Formulario funciona correctamente después de corregir errores');
    });

    test('todos los iconos Lucide deben renderizarse correctamente después de errores', async ({ page }) => {
        await page.goto('/contactos/create');

        // Lista de iconos que deben estar presentes
        const iconosEsperados = [
            'user-plus',   // Icono principal
            'users',       // Breadcrumb
            'chevron-right' // Breadcrumb
        ];

        // Verificar iconos inicialmente
        for (const icono of iconosEsperados) {
            await expect(page.locator(`[data-lucide="${icono}"]`)).toBeVisible();
        }

        // Provocar error de validación
        await page.click('button[type="submit"]');
        await expect(page.locator('text=El campo nombre es obligatorio')).toBeVisible();

        // Verificar que todos los iconos siguen visibles
        for (const icono of iconosEsperados) {
            await expect(page.locator(`[data-lucide="${icono}"]`)).toBeVisible();
        }

        console.log('✅ Todos los iconos Lucide se mantienen estables');
    });
});
