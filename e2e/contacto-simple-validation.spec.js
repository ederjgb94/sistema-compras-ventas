import { test, expect } from '@playwright/test';

test.describe('Verificación Manual del Diseño', () => {
    test('validar solo el HTML del formulario de contacto', async ({ page }) => {
        // Ir directo a login
        await page.goto('/login');

        // Verificar que estamos en login
        await expect(page.locator('input[type="email"]')).toBeVisible();
        await expect(page.locator('input[type="password"]')).toBeVisible();

        // Hacer login
        await page.fill('input[type="email"]', 'test@example.com');
        await page.fill('input[type="password"]', 'password');
        await page.click('button[type="submit"]');

        // Esperar redirect
        await page.waitForURL('**/dashboard', { timeout: 10000 });

        // Ir a crear contacto
        await page.goto('/contactos/create');

        // Esperar que aparezca el contenido
        await page.waitForLoadState('domcontentloaded');

        // Verificar elementos básicos sin preocuparse por visibilidad
        const h1 = await page.locator('h1').count();
        const iconElement = await page.locator('[data-lucide="user-plus"]').count();
        const breadcrumb = await page.locator('nav[aria-label="Breadcrumb"]').count();
        const sections = await page.locator('h3').count();

        console.log(`✅ Elementos encontrados:`);
        console.log(`   - H1 títulos: ${h1}`);
        console.log(`   - Icono user-plus: ${iconElement}`);
        console.log(`   - Breadcrumb: ${breadcrumb}`);
        console.log(`   - Secciones H3: ${sections}`);

        // Verificar que los elementos principales existen
        expect(h1).toBeGreaterThan(0);
        expect(iconElement).toBeGreaterThan(0);
        expect(breadcrumb).toBeGreaterThan(0);
        expect(sections).toBeGreaterThan(0);
    });
});
