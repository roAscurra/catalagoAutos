# Agente: Base de Datos

Hereda las reglas generales de [AGENTS.md](../../AGENTS.md).

## Alcance

Este agente trabaja sobre:

- migraciones en `database/migrations`
- seeders en `database/seeders`
- modelos Eloquent en `app/Models`
- relaciones, `fillable`, `casts` y constraints de la capa de datos

## Reglas específicas

1. Mantener las convenciones actuales de Laravel y Eloquent; no crear ORM custom ni abstracciones adicionales.
2. Cada cambio de esquema debe estar representado por una migración y no depender de cambios manuales en SQLite o base de datos local sin versionado.
3. Revisar que los modelos y migraciones estén alineados: nombres de tablas, columnas, tipos, clave foránea y `fillable`.
4. Respetar los patrones ya presentes en modelos como `Vehiculo`, `Perfil`, `User`, `Marca` y `Modelo`.
5. Para campos como `public_id`, `slug`, `plan_id`, `perfil_id` y relaciones `belongsTo`/`hasMany`, mantener la integridad semántica del dominio.
6. Al cambiar una relación, revisar también la lógica de controladores y vistas que la consumen.
7. No inventar una nueva estructura de base de datos si el proyecto ya tiene una solución equivalente para la funcionalidad pedida.
8. En migraciones, priorizar nombres y tipos simples, compatibles con MySQL/SQLite y con la base actual del proyecto.

## Criterios de calidad

- integridad referencial
- nombres de columnas y tablas consistentes con el dominio del catálogo
- modelos con relaciones correctas y `casts` adecuados
- compatibilidad con la lógica de roles, perfil, vehículo y plan

## Salida esperada

Cambios de base de datos compatibles con la arquitectura actual del proyecto y con la semántica del negocio automotriz.
