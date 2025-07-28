# 💼 Sistema de Compras y Ventas

> Sistema integral de gestión de transacciones comerciales con PostgreSQL, Laravel y arquitectura optimizada para el control de flujo de efectivo diario.

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15+-336791?style=flat-square&logo=postgresql)
![Tailwind](https://img.shields.io/badge/Tailwind-4.x-06B6D4?style=flat-square&logo=tailwindcss)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

## 🚀 Características Principales

### 💰 **Control Financiero**
- **Saldo Diario Automático**: Cálculo en tiempo real del flujo de efectivo
- **Reportes Temporales**: Vista semanal y mensual de ingresos/egresos
- **Transacciones Flexibles**: Compras y ventas con datos JSONB optimizados

### 🏗️ **Arquitectura Moderna**
- **PostgreSQL con JSONB**: Flexibilidad máxima para datos de facturas y referencias
- **Índices GIN**: Búsquedas ultra-rápidas en campos JSON
- **Folios Automáticos**: Generación secuencial inteligente
- **Sesiones en BD**: Escalabilidad y persistencia garantizada

### 🔐 **Seguridad Empresarial**
- **Autenticación Robusta**: Sistema seguro con encriptación de sesiones
- **Middleware Protegido**: Rutas seguras para panel administrativo
- **Validación CSRF**: Protección contra ataques de falsificación

### 📱 **Interfaz Responsiva**
- **Tailwind CSS 4.x**: Diseño moderno y adaptable
- **Dashboard Intuitivo**: Vista clara de métricas financieras
- **UX Optimizada**: Navegación fluida y eficiente

## 🛠️ Stack Tecnológico

| Componente | Tecnología | Versión |
|------------|------------|---------||
| **Backend** | Laravel | 12.x |
| **Base de Datos** | PostgreSQL | 15+ |
| **Frontend** | Livewire + Volt | 2.x |
| **Estilos** | Tailwind CSS | 4.x |
| **Testing** | Pest | 3.x |
| **Runtime** | PHP | 8.2+ |

## 📋 Requisitos del Sistema

### **Mínimos**
- **PHP** 8.2 o superior
- **PostgreSQL** 15+
- **Composer** 2.x
- **Node.js** 18+ y npm

### **Recomendados**
- **Laravel Herd** (para desarrollo local)
- **PostgreSQL** con extensiones JSON
- **Redis** (para cache en producción)

## ⚡ Instalación Rápida

### 1️⃣ **Clonar Repositorio**
```bash
git clone https://github.com/ederjgb94/sistema-compras-ventas.git
cd sistema-compras-ventas
```

### 2️⃣ **Configurar Dependencias**
```bash
# Instalar dependencias PHP
composer install

# Instalar dependencias Node.js
npm install
```

### 3️⃣ **Configurar Base de Datos**
```bash
# Crear base de datos PostgreSQL
createdb sistema_compras_ventas

# Copiar y configurar variables de entorno
cp .env.example .env
```

### 4️⃣ **Configurar .env**
```env
# Base de Datos
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sistema_compras_ventas
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password

# Sesiones (Recomendado para producción)
SESSION_DRIVER=database
SESSION_ENCRYPT=true
```

### 5️⃣ **Migrar y Sembrar**
```bash
# Generar clave de aplicación
php artisan key:generate

# Ejecutar migraciones
php artisan migrate

# Sembrar datos iniciales
php artisan db:seed

# Crear enlace de storage
php artisan storage:link
```

### 6️⃣ **Iniciar Desarrollo**
```bash
# Opción 1: Comando único (recomendado)
composer run dev

# Opción 2: Comandos separados
php artisan serve
npm run dev
```

## 🗄️ Estructura de Base de Datos

### **📊 Esquema Optimizado**

```sql
-- Contactos (Clientes/Proveedores)
contactos
├── id (bigint, PK)
├── tipo (enum: cliente|proveedor|ambos)
├── nombre (varchar, único)
├── email (varchar, nullable)
├── telefono (varchar, nullable)
├── direccion (text, nullable)
├── rfc (varchar, nullable)
├── activo (boolean, default: true)
└── timestamps

-- Métodos de Pago
metodos_pago
├── id (bigint, PK)
├── nombre (varchar, único)
├── activo (boolean, default: true)
└── timestamps

-- Transacciones (Core del Sistema)
transacciones
├── id (bigint, PK)
├── folio (varchar, único, auto-generado)
├── tipo (enum: compra|venta)
├── fecha (date)
├── contacto_id (FK → contactos)
├── referencia_tipo (varchar, nullable)
├── referencia_nombre (varchar, nullable)
├── referencia_datos (jsonb, nullable) -- Flexibilidad total
├── factura_tipo (varchar, nullable)
├── factura_numero (varchar, nullable)
├── factura_datos (jsonb, nullable) -- Campos dinámicos
├── factura_archivos (jsonb, nullable) -- Rutas de archivos
├── metodo_pago_id (FK → metodos_pago)
├── referencia_pago (varchar, nullable)
├── total (decimal 15,2)
├── observaciones (text, nullable)
└── timestamps

-- Índices GIN para JSONB (Búsquedas Ultra-Rápidas)
CREATE INDEX idx_referencia_datos_gin ON transacciones USING gin(referencia_datos);
CREATE INDEX idx_factura_datos_gin ON transacciones USING gin(factura_datos);
```

### **🔗 Relaciones Clave**
- `transacciones.contacto_id` → `contactos.id`
- `transacciones.metodo_pago_id` → `metodos_pago.id`
- **Soft Deletes**: Integridad histórica preservada
- **Timestamps**: Auditoría completa de cambios

## 👤 Credenciales por Defecto

| Usuario | Email | Password |
|---------|-------|----------|
| **Administrador** | admin@admin.com | admin |

> ⚠️ **Importante**: Cambiar credenciales en producción

## 🎯 Funcionalidades Implementadas

### ✅ **Módulo de Autenticación**
- Login/logout seguro
- Sesiones persistentes en BD
- Middleware de protección
- Encriptación de sesiones

### ✅ **Dashboard Financiero**
- **Saldo del Día**: Ingresos - Egresos automático
- **Saldo Semanal**: Últimos 7 días
- **Saldo Mensual**: Mes actual
- **Últimas Transacciones**: Vista rápida de actividad reciente

### ✅ **Base de Datos Flexible**
- Campos JSONB para máxima adaptabilidad
- Índices optimizados para consultas rápidas
- Relaciones bien definidas
- Migraciones versionadas

### ✅ **Arquitectura Escalable**
- Modelos Eloquent con relaciones
- Scopes para consultas complejas
- Seeders para datos iniciales
- Estructura modular

## 🚧 Roadmap de Desarrollo

### **🔄 Próximas Funcionalidades** (Issue #2)

#### **📝 CRUDs Completos**
- [ ] Gestión de Contactos (Crear/Editar/Eliminar)
- [ ] Gestión de Métodos de Pago
- [ ] Gestión de Transacciones con formularios dinámicos

#### **📊 Reportes Avanzados**
- [ ] Reportes por rango de fechas
- [ ] Gráficos de tendencias
- [ ] Exportación a Excel/PDF
- [ ] Análisis por contacto/método de pago

#### **🔍 Búsquedas Inteligentes**
- [ ] Filtros avanzados en transacciones
- [ ] Búsqueda full-text en JSONB
- [ ] Autocompletado en formularios

#### **📁 Gestión de Archivos**
- [ ] Subida de facturas/documentos
- [ ] Previsualización de archivos
- [ ] Organización por carpetas

#### **⚡ Optimizaciones**
- [ ] Cache Redis para reportes
- [ ] Paginación optimizada
- [ ] API REST para integraciones

## 🧪 Testing

```bash
# Ejecutar todas las pruebas
composer test

# Ejecutar pruebas específicas
php artisan test tests/Feature/DashboardTest.php

# Testing con cobertura
php artisan test --coverage
```

### **📋 Cobertura Actual**
- ✅ Autenticación
- ✅ Dashboard y cálculos
- ✅ Modelos y relaciones
- 🔄 CRUDs (pendiente Issue #2)

## 🚀 Despliegue

### **🐳 Docker (Recomendado)**
```bash
# Usando Laravel Sail
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed
```

### **☁️ Producción**
```bash
# Optimizar para producción
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

## 🤝 Contribución

### **🔄 Workflow de Desarrollo**
1. **Fork** del repositorio
2. **Crear rama**: `feature/descripcion` o `fix/descripcion`
3. **Desarrollar** con tests incluidos
4. **Pull Request** hacia `dev`
5. **Review** y merge automático si CI/CD pasa

### **📏 Estándares de Código**
- **PSR-12** para PHP
- **Conventional Commits** para mensajes
- **Tests obligatorios** para nuevas funcionalidades
- **Documentación** actualizada

## 📜 Licencia

Este proyecto está bajo la licencia **MIT**. Ver archivo [LICENSE](LICENSE) para más detalles.

## 🆘 Soporte

### **🐛 Reportar Problemas**
- [Issues en GitHub](https://github.com/ederjgb94/sistema-compras-ventas/issues)
- Incluir logs y pasos para reproducir

### **💬 Comunidad**
- [Discusiones](https://github.com/ederjgb94/sistema-compras-ventas/discussions)
- Email: tu-email@dominio.com

## 🙏 Agradecimientos

- **Laravel Team** por el framework excepcional
- **Comunidad PostgreSQL** por la robustez de la BD
- **Tailwind Labs** por el sistema de diseño
- **Livewire Team** por la reactividad sin JavaScript

---

<p align="center">
  <strong>Hecho con ❤️ para optimizar la gestión financiera empresarial</strong>
</p>

<p align="center">
  <a href="#-sistema-de-compras-y-ventas">⬆️ Volver arriba</a>
</p>