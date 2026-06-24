# Today Project E-Commerce

Laravel e-commerce platform with catalog browsing, customer authentication, cart, checkout, payment gateway handoff, order tracking, admin analytics, inventory management, reviews, wishlist, coupons, and responsive storefront UI.

## Requirements

- PHP 8.3 or newer
- Composer
- Node.js and npm
- MySQL or SQLite

## Installation

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
```

For local development:

```bash
composer run dev
```

## Environment Variables

Configure the normal Laravel database, mail, cache, queue, and session variables in `.env`.

Payment settings:

```dotenv
PAYMENT_DEFAULT_CURRENCY=NPR

ESEWA_ENABLED=true
ESEWA_SANDBOX=true
ESEWA_PRODUCT_CODE=EPAYTEST
ESEWA_SECRET_KEY="8gBm/:&EnhH.1/q"
ESEWA_PAYMENT_URL=https://rc-epay.esewa.com.np/api/epay/main/v2/form
ESEWA_STATUS_URL=https://rc.esewa.com.np/api/epay/transaction/status/

KHALTI_ENABLED=false
KHALTI_SECRET_KEY=
KHALTI_BASE_URL=https://dev.khalti.com/api/v2

PAYPAL_ENABLED=false
PAYPAL_CLIENT_ID=
PAYPAL_CLIENT_SECRET=
PAYPAL_BASE_URL=https://api-m.sandbox.paypal.com
PAYPAL_CURRENCY=USD

STRIPE_ENABLED=false
STRIPE_SECRET_KEY=
STRIPE_BASE_URL=https://api.stripe.com/v1
STRIPE_CURRENCY=usd
```

Cash on Delivery works without external keys. eSewa test mode works with the sandbox defaults above. Enable Khalti, PayPal, and Stripe only after adding valid developer keys.

## Default Admin

The database seeder creates:

- Email: `test@example.com`
- Password: `password`
- Role: `admin`

Existing admin users must have `role = admin` in the `users` table to access `/admin`.

## Main Routes

- Storefront: `/`
- Category listing: `/listing/{slug}`
- Product detail: `/details/{slug}`
- Cart: `/cart`
- Customer login: `/customer/login`
- Customer dashboard: `/customer/dashboard`
- Checkout: `/customer/checkout`
- Admin dashboard: `/admin`
- Admin orders: `/admin/orders`
- Product management: `/admin/product`
- Category management: `/admin/category`
- Attribute management: `/admin/attribute`

## Project Structure

- `app/Http/Controllers/Frontend` handles catalog, cart, checkout, wishlist, reviews, and payment callbacks.
- `app/Http/Controllers/Admin` handles admin dashboard, products, categories, attributes, and orders.
- `app/Models` contains catalog, customer, order, payment, coupon, review, and wishlist models.
- `app/Services` contains cart total calculation and payment gateway clients.
- `resources/views/frontend` contains storefront pages.
- `resources/views/admin` contains admin pages.
- `database/migrations` defines commerce schema.

## Testing

```bash
php artisan test --compact
```

Format PHP before committing:

```bash
vendor/bin/pint --dirty --format agent
```

## Deployment

1. Set production `.env` values: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, database, mail, queue, session, and payment keys.
2. Install optimized dependencies: `composer install --no-dev --optimize-autoloader`.
3. Build frontend assets: `npm ci && npm run build`.
4. Run migrations: `php artisan migrate --force`.
5. Cache production config: `php artisan config:cache`, `php artisan route:cache`, and `php artisan view:cache`.
6. Run a queue worker if queued mail/payment notifications are added later.
7. Point the web server document root to `public/`.
