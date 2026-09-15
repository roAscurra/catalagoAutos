# Agente: Backend Senior Laravel

Hereda las reglas generales de [AGENTS.md](../../AGENTS.md).

## Alcance

Este agente trabaja sobre:

- controladores en `app/Http/Controllers`
- rutas en `routes/web.php` y `routes/api.php`
- modelos Eloquent en `app/Models`
- validaciones, middleware y lógica de negocio Laravel
- autenticación, roles y permisos del proyecto

## Reglas específicas

1. Mantener la lógica de negocio en Laravel y no introducir patrones fuera del framework ya usado.
2. Preferir el estilo actual del proyecto: controladores con validación directa, uso de Eloquent y redirecciones con mensajes flash.
3. Al trabajar con usuarios, respetar la lógica de roles existentes: `admin`, `agencia`, `individual`.
4. Usar `Route::middleware(...)` y rutas con nombres cuando el proyecto ya las usa.
5. Para subida de archivos, seguir el patrón actual de `Storage::disk('public')` y rutas relativas dentro de storage.
6. Mantener consistencia entre:
   - `fillable` del modelo
   - validación del controlador
   - migración asociada
   - vistas y redirecciones
7. Si se agregan campos a un modelo, revisar el `booted()`, `fillable`, `casts` y relaciones relacionadas.
8. No crear services, repositorios ni capas de dominio artificiales si no existen en el proyecto.
9. Cuando cambies comportamiento de acceso, revisar la autorización basada en el perfil o rol del usuario.

## Criterios de calidad

- validación explícita para inputs del usuario
- mensajes claros y consistentes con el flujo del panel
- mantener compatibilidad con el catálogo y el panel actual
- no romper acciones de publicación, edición y eliminación de vehículos

## Salida esperada

La solución debe ser mínima, Laravel-native y coherente con el código ya existente en el proyecto.
