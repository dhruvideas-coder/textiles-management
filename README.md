# Textile SaaS — Billing, Challan & Shop Management Platform

A multi-tenant SaaS application for textile businesses to manage billing, delivery challans, inventory, customers, and staff. Built with Laravel 12, Tailwind CSS, and Google OAuth.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12 (PHP 8.2+) |
| Frontend | Blade + Tailwind CSS 4 + Alpine.js |
| Build Tool | Vite 7 |
| Database | MySQL |
| Auth | Google OAuth 2.0 (Laravel Socialite) |
| Roles & Permissions | Spatie Laravel Permission |
| PDF Generation | DomPDF (barryvdh/laravel-dompdf) |

---

## Features

### Super Admin
- **Shop Management** — Create, edit, activate/deactivate shops; each shop gets an owner user automatically
- **User Management** — Create and manage users (super_admin / owner / staff roles)
- **Subscription Management** — Assign and update billing plans per shop
- **Platform Analytics** — Overview of total shops, bills, challans, and revenue
- **Impersonation** — Log in as any shop owner to debug or assist

### Shop Owner
- **Bills** — Create GST-compliant bills with line items (pieces, meters, rate, taxes); download PDF; print thermal receipt; duplicate to draft
- **Challans** — Create delivery challans with product items (pieces, meters, weight); download PDF
- **Customers** — Full CRUD for customers with GSTIN, address, and bill history
- **Inventory** — Track fabric stock (meters), set low-stock thresholds, and link to bill/challan items
- **Staff Management** — Add / edit / remove staff users; controlled by plan's staff limit
- **Shop Settings** — Update shop name, logo, contact details, GST number, address, theme color
- **Subscription** — View current plan details and usage (bills this month vs. limit)
- **Shop Analytics** — Monthly/daily revenue charts, top customers, best-selling products, stock overview

### Staff
- Create bills and challans
- View customers and inventory
- Cannot delete bills, challans, or customers
- Cannot manage staff or shop settings

---

## Roles

| Role | Access |
|---|---|
| `super_admin` | Full platform management — no shop scope |
| `owner` | All shop features including staff, settings, subscription |
| `staff` | Bills, challans, customers (view/create only), inventory (view/create only) |

---

## Authentication

Login is **invite-only via Google OAuth**. Users must be created by the Super Admin before they can sign in.

**Flow:**
1. Super Admin creates a user with their Google email address
2. User visits `/login` and clicks "Continue with Google"
3. Google redirects back; the system matches the email and links the Google ID
4. User is logged in and redirected to the appropriate dashboard

Authenticated users visiting `/login` are automatically redirected to the dashboard.

---

## Installation

### Requirements
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL

### Steps

```bash
# 1. Clone the repository
git clone <repo-url>
cd "textiles management"

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies and build assets
npm install
npm run build

# 4. Copy environment file and configure
cp .env.example .env
php artisan key:generate

# 5. Configure .env — set DB credentials and Google OAuth keys
# DB_DATABASE, DB_USERNAME, DB_PASSWORD
# GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, GOOGLE_REDIRECT_URI

# 6. Run migrations and seed default data
php artisan migrate
php artisan db:seed

# 7. Link storage for file uploads
php artisan storage:link
```

### Google OAuth Setup
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project and enable the **Google+ API**
3. Create OAuth 2.0 credentials (Web Application)
4. Add `http://your-domain/auth/google/callback` as an authorized redirect URI
5. Copy the Client ID and Secret into `.env`

---

## Database Seeding

The default seed creates:
- Roles: `super_admin`, `owner`, `staff`
- Plans: `Free` (50 bills/month, 2 staff) and `Pro` (unlimited)
- Super Admin user (credentials printed to terminal after seeding)

```bash
php artisan db:seed
```

---

## Routes Reference

### Public
| Method | URI | Description |
|---|---|---|
| GET | `/login` | Login page (redirects to dashboard if already authenticated) |
| GET | `/auth/google/redirect` | Initiate Google OAuth |
| GET | `/auth/google/callback` | Google OAuth callback |

### Authenticated
| Method | URI | Description |
|---|---|---|
| GET | `/dashboard` | Role-based dashboard |
| POST | `/logout` | Logout |

### Admin (`/admin`, role: super_admin)
| Method | URI | Description |
|---|---|---|
| GET | `/admin/shops` | List all shops |
| GET/POST | `/admin/shops/create` | Create new shop + owner |
| GET/PUT | `/admin/shops/{shop}/edit` | Edit shop |
| GET | `/admin/shops/{shop}` | View shop details |
| DELETE | `/admin/shops/{shop}` | Delete shop |
| GET | `/admin/users` | List all users |
| GET/POST | `/admin/users/create` | Create user |
| GET/PUT | `/admin/users/{user}/edit` | Edit user |
| GET | `/admin/users/{user}` | View user details |
| DELETE | `/admin/users/{user}` | Delete user |
| GET | `/admin/subscriptions` | List subscriptions |
| PUT | `/admin/subscriptions/{shop}` | Update shop subscription |
| GET | `/admin/analytics` | Platform analytics |
| POST | `/admin/impersonate/{user}` | Impersonate user |
| POST | `/admin/impersonate/stop` | Stop impersonation |

### Owner (`/owner`, role: owner or staff)
| Method | URI | Description |
|---|---|---|
| GET | `/owner/bills` | List bills (filterable by customer, date, number) |
| GET/POST | `/owner/bills/create` | Create bill |
| GET | `/owner/bills/{bill}` | View bill |
| GET | `/owner/bills/{bill}/edit` | Edit bill form (update disabled — use duplicate) |
| POST | `/owner/bills/{bill}/duplicate` | Duplicate bill to draft |
| GET | `/owner/bills/{bill}/pdf` | Download bill as PDF |
| GET | `/owner/bills/{bill}/thermal` | Thermal print view |
| DELETE | `/owner/bills/{bill}` | Delete bill (owner only) |
| GET | `/owner/challans` | List challans |
| GET/POST | `/owner/challans/create` | Create challan |
| GET | `/owner/challans/{challan}` | View challan |
| GET | `/owner/challans/{challan}/pdf` | Download challan as PDF |
| DELETE | `/owner/challans/{challan}` | Delete challan (owner only) |
| GET | `/owner/customers` | List customers |
| GET/POST | `/owner/customers/create` | Add customer |
| GET | `/owner/customers/{customer}` | View customer + bill history |
| GET/PUT | `/owner/customers/{customer}/edit` | Edit customer |
| DELETE | `/owner/customers/{customer}` | Delete customer (owner only) |
| GET | `/owner/inventory` | List inventory items |
| GET/POST | `/owner/inventory/create` | Add inventory item |
| GET | `/owner/inventory/{inventory}` | View inventory item |
| GET/PUT | `/owner/inventory/{inventory}/edit` | Edit inventory item |
| DELETE | `/owner/inventory/{inventory}` | Delete inventory item |
| GET | `/owner/analytics` | Shop analytics dashboard |

### Owner-Only (`/owner`, role: owner)
| Method | URI | Description |
|---|---|---|
| GET | `/owner/staff` | List staff |
| GET/POST | `/owner/staff/create` | Add staff member |
| GET | `/owner/staff/{staff}` | View staff member |
| GET/PUT | `/owner/staff/{staff}/edit` | Edit staff member |
| DELETE | `/owner/staff/{staff}` | Remove staff member |
| GET/PUT | `/owner/settings` | Shop settings |
| GET | `/owner/subscription` | View current plan & usage |

---

## Project Structure

```
app/
  Http/
    Controllers/
      Admin/          # ShopController, UserController, SubscriptionController,
                      # AnalyticsController, ImpersonationController
      Auth/           # GoogleAuthController
      Owner/          # BillController, ChallanController, CustomerController,
                      # InventoryController, StaffController, ShopSettingController,
                      # AnalyticsController, SubscriptionController
    Middleware/
      EnsureRole.php          # Role-based access check
      EnsureShopIsActive.php  # Blocks inactive shops/users
      EnsureFeatureAccess.php # Plan feature gate
      ResolveTenant.php       # Sets shop context for multi-tenancy
  Models/             # User, Shop, Plan, Subscription, Bill, BillItem,
                      # Challan, ChallanItem, Customer, Inventory, UsageLog
  Services/
    BillService.php           # Bill creation, numbering, totals, duplication
    ChallanService.php        # Challan creation and numbering
    SubscriptionService.php   # Plan limits, usage tracking
    AnalyticsService.php      # Dashboard metrics

resources/views/
  layouts/app.blade.php   # Main layout with sidebar navigation
  auth/login.blade.php    # Google OAuth login page
  dashboard/              # admin.blade.php, owner.blade.php
  admin/                  # shops/, users/, subscriptions/, analytics/
  owner/                  # bills/, challans/, customers/, inventory/,
                          # staff/, settings/, subscription/, analytics/

database/
  migrations/   # 15 migrations
  seeders/      # Roles, Plans, SuperAdmin
```

---

## Bill Numbering

Bills are numbered in the format: `BIL-YYYYMM-XXXX` (e.g. `BIL-202604-0001`), auto-incremented per shop per month.

Challans use the format: `CHL-YYYYMM-XXXX`.

---

## Multi-Tenancy

All shop-owned records (bills, challans, customers, inventory, etc.) automatically scope to the logged-in user's shop via a global scope. Super admins bypass all scopes and see all data.

---

## Subscription Plans

Plans are defined in the `plans` table. Each plan supports:
- `max_bills_per_month` — monthly bill creation limit (null = unlimited)
- `max_staff_users` — maximum staff accounts (null = unlimited)
- `features` — JSON object for feature flags

Default plans seeded:
- **Free** — 50 bills/month, 2 staff
- **Pro** — Unlimited bills and staff

---

## Known Design Decisions

- **Bill editing is disabled** — Bills cannot be directly edited after creation. Use "Duplicate to Draft" to safely revise a bill. This prevents accidental modification of finalized invoices.
- **Challan editing is disabled** — Same rationale as bills.
- **Google-only login** — No username/password. All users authenticate via Google OAuth with their pre-registered email.
- **Invite-only access** — Unknown emails are rejected at login. Only Super Admin can create user accounts.
