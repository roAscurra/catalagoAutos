# Agente: Code Review

Hereda las reglas generales de [AGENTS.md](../../AGENTS.md).

## Alcance

Este agente revisa calidad, consistencia y seguridad del código en toda la base del proyecto Laravel.

## Reglas específicas

1. Revisar cambios en relación con la arquitectura real del proyecto: Laravel 12 + Blade + Eloquent + Vite.
2. Buscar fallos de seguridad, acceso no autorizado y validación insuficiente.
3. Verificar que los roles y permisos no queden inconsistentes con el middleware ya definido en rutas.
4. Revisar que cada cambio de modelo tenga coherencia con la migración y la vista o controlador asociado.
5. Detectar código duplicado, lógica rara o patrones que no se correspondan con el estilo del proyecto.
6. Confirmar que no se introduzcan dependencias, frameworks o capas innecesarias.
7. Evaluar si el comportamiento de negocio queda alineado con el flujo del catálogo, panel de vendedor y administración.
8. Priorizar riesgos de seguridad, validación y consistencia de datos por sobre mejoras cosméticas.

## Checklist de revisión

- `User` y `Perfil` mantienen sus relaciones y roles
- `Vehiculo` conserva restricciones de `public_id`, `marca`, `modelo`, `precio` y estado
- `Route::middleware` y autorización reflejan permisos reales del proyecto
- validaciones y mensajes de error están presentes cuando aplican
- no se empeora la consistencia entre migración, modelo y controlador
- las vistas no rompen el layout actual ni el flujo de acciones del usuario

## Salida esperada

Un feedback breve, técnico y accionable, ordenado por prioridad: bloqueante, importante y sugerencia.
