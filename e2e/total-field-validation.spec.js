import { test, expect } from '@playwright/test';

test.describe('Validación del campo Total en formularios de transacciones', () => {
  test.beforeEach(async ({ page }) => {
    // Navegar al sitio
    await page.goto('http://sistema-compras-ventas.test');

    // Verificar si ya está logueado o necesita hacer login
    try {
      await page.waitForSelector('a[href="/login"]', { timeout: 2000 });
      // Hacer login
      await page.click('a[href="/login"]');
      await page.fill('input[name="email"]', 'admin@test.com');
      await page.fill('input[name="password"]', 'password123');
      await page.click('button[type="submit"]');
    } catch (e) {
      // Ya está logueado, solo verificar que está en dashboard
      console.log('Usuario ya está logueado');
    }

    // Esperar a que cargue el dashboard
    await page.waitForURL('**/dashboard', { timeout: 10000 });
  });

  test('Campo Total - Crear Ingreso: formato de moneda correcto', async ({ page }) => {
    // Ir a crear ingreso
    await page.click('a[href="/transacciones/ingresos/create"]');
    await page.waitForSelector('#total');

    const totalInput = page.locator('#total');

    // Test 1: Solo permite números y un punto decimal
    await totalInput.fill('123.456.78');
    await totalInput.blur();
    const value1 = await totalInput.inputValue();
    expect(value1).toBe('123456.78');

    // Test 2: Limita a dos decimales
    await totalInput.clear();
    await totalInput.fill('123.999');
    await totalInput.blur();
    const value2 = await totalInput.inputValue();
    expect(value2).toBe('123.99');

    // Test 3: Formatea a dos decimales al perder el foco
    await totalInput.clear();
    await totalInput.fill('123');
    await totalInput.blur();
    const value3 = await totalInput.inputValue();
    expect(value3).toBe('123.00');

    // Test 4: Maneja valores vacíos correctamente
    await totalInput.clear();
    await totalInput.blur();
    const value4 = await totalInput.inputValue();
    expect(value4).toBe('');

    // Test 5: No permite caracteres no numéricos
    await totalInput.clear();
    await totalInput.fill('abc123.45def');
    await totalInput.blur();
    const value5 = await totalInput.inputValue();
    expect(value5).toBe('123.45');

    console.log('✅ Campo Total en Crear Ingreso: todos los tests pasaron');
  });

  test('Campo Total - Crear Egreso: formato de moneda correcto', async ({ page }) => {
    // Ir a crear egreso
    await page.click('a[href="/transacciones/egresos/create"]');
    await page.waitForSelector('#total');

    const totalInput = page.locator('#total');

    // Test 1: Solo permite números y un punto decimal
    await totalInput.fill('456.789.10');
    await totalInput.blur();
    const value1 = await totalInput.inputValue();
    expect(value1).toBe('456789.10');

    // Test 2: Limita a dos decimales
    await totalInput.clear();
    await totalInput.fill('456.999');
    await totalInput.blur();
    const value2 = await totalInput.inputValue();
    expect(value2).toBe('456.99');

    console.log('✅ Campo Total en Crear Egreso: todos los tests pasaron');
  });

  test('Campo Total - Editar transacción: formato de moneda correcto', async ({ page }) => {
    // Ir a transacciones
    await page.click('a[href="/transacciones"]');
    await page.waitForSelector('.table tbody tr');

    // Buscar el primer botón de editar y hacer clic
    const editButton = page.locator('a[title="Editar transacción"]').first();
    await editButton.click();

    await page.waitForSelector('#total');
    const totalInput = page.locator('#total');

    // Test 1: Valor existente se mantiene
    const existingValue = await totalInput.inputValue();
    expect(existingValue).toMatch(/^\d+\.\d{2}$/);

    // Test 2: Puede modificar y mantiene formato
    await totalInput.clear();
    await totalInput.fill('789.123');
    await totalInput.blur();
    const value1 = await totalInput.inputValue();
    expect(value1).toBe('789.12');

    console.log('✅ Campo Total en Editar Transacción: todos los tests pasaron');
  });

  test('Campo Total - Comportamiento de focus mejorado', async ({ page }) => {
    // Ir a crear ingreso
    await page.click('a[href="/transacciones/ingresos/create"]');
    await page.waitForSelector('#total');

    const totalInput = page.locator('#total');

    // Test 1: Focus en campo vacío no hace nada especial
    await totalInput.focus();
    const value1 = await totalInput.inputValue();
    expect(value1).toBe('');

    // Test 2: Focus en campo con "0" selecciona el texto
    await totalInput.fill('0');
    await totalInput.focus();
    const selectedText = await page.evaluate(() => {
      const input = document.getElementById('total');
      return input.value.substring(input.selectionStart, input.selectionEnd);
    });
    expect(selectedText).toBe('0');

    // Test 3: Focus en campo con "0.00" selecciona el texto
    await totalInput.fill('0.00');
    await totalInput.focus();
    const selectedText2 = await page.evaluate(() => {
      const input = document.getElementById('total');
      return input.value.substring(input.selectionStart, input.selectionEnd);
    });
    expect(selectedText2).toBe('0.00');

    console.log('✅ Comportamiento de focus mejorado: todos los tests pasaron');
  });
});
