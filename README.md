# Sumatra Briks — Ecommerce + CMS Orders + Demo Products

**Patch for existing Laravel application, not a full Laravel project.** Built from the supplied `resources.zip` (Blade/routes/storage only) and the previous storefront/CMS patches. Application controllers, Eloquent models, migrations, database credentials, and actual table schema were NOT supplied. Not end-to-end tested.

## Included
- Premium ecommerce storefront using DATABASE products and existing cart/checkout routes
- Six local concept product images and hero asset
- CMS order list, detail, status changes, receipt/proof access controller and admin sidebar link (requires integration)
- `database/seeders/SumatraDemoProductsSeeder.php`: six *fake* products, sample IDR prices, qty 100, copied product images. **No dummy orders or payment accounts**.

## Installation (LOCAL DEVELOPMENT ONLY)
1. Back up files & database; make a new Git branch.
2. Unzip into Laravel project root, merging `app/`, `config/`, `database/`, `public/`, `resources/`. Do not replace `.env` or existing `routes/web.php`.
3. For CMS orders, append content from `INSTALL-ROUTES.txt` to existing `routes/web.php` **once**. If you already installed CMS Orders patch, do NOT append the routes again.
4. Confirm current table columns in `products` match `name`, `slug`, `price`, `stock`; the seeder accommodates missing optional `category`, `description`, `is_active`, `sku`, `images` columns but cannot infer other mandatory fields or image relations. If schema differs, adapt the seeder first.
5. On LOCAL (`APP_ENV=local`) run:

```
php artisan optimize:clear
php artisan db:seed --class=SumatraDemoProductsSeeder
php artisan storage:link
npm run build
php artisan serve
```

6. Check `/admin/products`, `/products`, `/`, `/cart`, `/checkout` and a test checkout. Run seeder repeatedly safely: existing products are SKIPPED (not overwritten).
7. Price examples: C25 35,000; C26 37,000; C27 39,000; C28 41,000; Hexa 45,000; Pillow 43,000 IDR; stock 100 each. These figures are **not approved product prices** and must NOT be used in production.
8. For order CMS, confirm correct model, order table columns, relation `items`, payment proof disk, auth and status flow against the actual app. Refer to original CMS orders patch instructions. These are not guaranteed compatible without the missing backend code.

## Safety
- Seeder explicitly BLOCKS production/staging and never deletes or updates existing products.
- Don't run `migrate:fresh` or any database reset.
- Configure real bank settings only when owner confirms values: `SUMATRA_BANK_NAME`, `SUMATRA_BANK_ACCOUNT`, `SUMATRA_BANK_HOLDER`.
- Admin order access requires auth + explicit admin allowlist; original admin products routes in uploaded source did not have auth protection; resolve before going live.
- The original routes only establish the existence of checkout endpoints; payment/stock workflow and proof privacy must be verified.

## CMS orders config examples
```
SUMATRA_ORDER_ADMIN_EMAILS="actual-admin@example.com"
SUMATRA_ORDER_MODEL="App\Models\Transaction"
SUMATRA_ORDER_PROOF_COLUMN="payment_proof"
SUMATRA_ORDER_PROOF_DISK="public"
```
Those are **examples, not guaranteed model/column names**.
