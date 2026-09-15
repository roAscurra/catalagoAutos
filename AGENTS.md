# AGENTS.md

## Proyecto

Este repositorio corresponde a una aplicación Laravel 12 para catalogar vehículos y gestionar perfiles de vendedores y agencias. La app usa:

- PHP 8.2 + Laravel 12
- Blade templates para la capa de vistas
- Vite + Tailwind CSS para assets frontend
- Eloquent para modelos y relaciones
- rutas web y controladores en `app/Http/Controllers`
- migrations en `database/migrations` y modelos en `app/Models`

El proyecto actual tiene un flujo principal basado en:

- catálogo público
- login/registro de usuarios
- panel de vendedor para perfiles, planes y publicación de vehículos
- administración con roles `admin`, `agencia` e `individual`

## Reglas generales para todos los agentes

1. Leer primero la estructura existente antes de proponer cambios.
2. Mantener el diseño del proyecto actual: no inventar nuevas arquitecturas, paquetes ni frameworks que no existan en este repositorio.
3. Preferir cambios mínimos, alineados con los patrones ya usados en Laravel, Blade y Eloquent.
4. Respetar la convención actual de carpetas:
   - `app/Http/Controllers`
   - `app/Models`
   - `routes/`
   - `resources/views/`
   - `resources/css/`
   - `database/migrations/`
   - `database/seeders/`
5. Mantener consistencia con nombres de rutas, modelos, campos y validaciones ya definidos.
6. No crear abstracciones nuevas si la funcionalidad puede resolverse con el patrón ya presente en el proyecto.
7. Al trabajar con usuarios, perfiles, roles y permisos, respetar los roles existentes (`admin`, `agencia`, `individual`) y la lógica de middleware.
8. Para cambios de negocio, priorizar validaciones en controladores y reglas de dominio reales del proyecto.
9. Si se toca base de datos, preferir migraciones y modelos Eloquent sobre lógicas artificiales.
10. Usar mensajes y textos en español cuando el proyecto ya los emplea en la interfaz.
11. No modificar la lógica de la aplicación sin ser solicitado explícitamente.
12. Al final de cualquier cambio, validar con la herramienta más pequeña que demuestre la corrección relevante (por ejemplo pruebas de Laravel o verificación de rutas cuando aplica).

## Contexto de dominio

El proyecto está orientado a un marketplace automotriz con vendedores y agencias. Los conceptos ya presentes en el código incluyen:

- `User` con rol y perfil asociado
- `Perfil` con `slug`, `plan`, `logo`, `secciones` y landing
- `Vehiculo` con `public_id`, `marca`, `modelo`, `precio`, `combustible`, `ubicacion`, etc.
- `Plan` y configuración del panel de vendedor
- administración básica para recursos dinámicos

## Instrucciones para trabajar en este repositorio

- Evitar crear componentes o servicios genéricos si el proyecto no los usa.
- Mantener compatibilidad con la estructura actual del frontend (Blade + CSS + Vite).
- Si se modifica una relación o un modelo, revisar también el controlador y la vista asociada.
- Si se agregan campos a una tabla, hacer coincidir `fillable`, validación y migración.
- No asumir React, TypeScript, Vue, NestJS ni microservicios; todo lo que exista aquí es nativo de Laravel.

## Agentes especializados

Este repositorio cuenta con los siguientes perfiles especializados:

- backend Laravel
- ui-design
- frontend
- base de datos
- code review

Cada agente debe leerse este archivo y aplicar las reglas generales antes de ejecutar tareas específicas.
