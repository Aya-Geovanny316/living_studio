# GT Hobby - Catalogo y Cotizaciones (Laravel 11)

Tienda catalogo con carrito y flujo de cotizacion (RFQ) para GT Hobby.

## Requisitos
- PHP 8.2+
- Composer
- Node.js + NPM
- Base de datos (SQLite por defecto)

## Instalacion
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install
npm run dev
```

## Configuracion de correo
En `.env` configura:
```
MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=...
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=...
MAIL_FROM_NAME="GT Hobby"
QUOTES_TO_EMAIL=ventas@gthobby.com
```

## Usuarios por defecto
- Admin:
  - Email: `admin@gthobby.test`
  - Password: `password`

## Rutas principales
- `/` Home
- `/catalogo` Catalogo
- `/producto/{slug}` Detalle de producto
- `/carrito` Carrito
- `/cotizacion` Solicitud de cotizacion
- `/mi-cuenta` Panel cliente
- `/admin` Panel admin

## Tipografias
El PDF indica Lufga (logo), marbl y JOS (texto). Para reemplazar:
- Heading: `Sora` (Google Fonts) -> reemplazar por Lufga si se agrega localmente.
- Body: `Manrope` (Google Fonts) -> reemplazar por marbl/JOS.

## Assets
Si no hay logo oficial, los placeholders estan en:
- `public/brand/placeholders`
- `public/brand/promos`

Logos temporales:
- `public/brand/gt-hobby-logo.svg`
- `public/brand/gt-hobby-favicon.svg`

Reemplazar manteniendo las rutas.
