# 📁 CẤU TRÚC THƯ MỤC DỰ ÁN - PLAYMANAGEMENT

```
CNW-PlayManagement/
├── app/                    # Mã nguồn chính (Logic backend)
│   ├── Enums/              # Enum constants (VD: RoleEnum)
│   ├── Http/
│   │   ├── Controllers/    # Controller xử lý request
│   │   │   ├── Admin/      # (dự kiến) Controller cho Admin
│   │   │   ├── Api/        # Controller API (mobile app)
│   │   │   ├── Auth/       # Controller xác thực web (login, register, ...)
│   │   │   └── ...
│   │   ├── Middleware/      # Middleware (VD: EnsureUserHasRole)
│   │   ├── Requests/       # Form Request validation
│   │   │   ├── Api/Auth/   # Validation cho API auth
│   │   │   ├── Auth/       # Validation cho web auth
│   │   │   └── User/       # Validation cho user CRUD
│   │   └── Resources/      # API Resources (transform DB -> JSON)
│   ├── Models/             # Eloquent Models
│   ├── Providers/          # Service Providers
│   ├── Traits/             # Traits dùng chung (VD: ApiResponse)
│   └── View/Components/    # Blade Components
│
├── bootstrap/              # Khởi tạo Laravel framework
│   └── app.php             # Đăng ký middleware alias (role, ...)
│
├── config/                 # File cấu hình (database, auth, sanctum, ...)
│
├── database/               # CSDL & Migrations
│   ├── factories/          # Factory tạo dữ liệu ảo (test/seeder)
│   ├── migrations/         # Migration định nghĩa bảng
│   └── seeders/            # Seeder nạp dữ liệu mẫu
│
├── public/                 # Thư mục gốc web (document root)
│   ├── build/              # Assets đã build (Vite)
│   └── index.php           # Entry point
│
├── resources/              # Giao diện (Frontend)
│   ├── css/                # File CSS / Tailwind
│   ├── js/                 # File JavaScript / Alpine
│   └── views/              # Blade templates
│       ├── admin/          # Giao diện Admin
│       ├── auth/           # Giao diện login, register, ...
│       ├── components/     # Blade component dùng chung
│       ├── customer/       # Giao diện Customer
│       ├── layouts/        # Layout (app, guest, navigation)
│       ├── owner/          # Giao diện Owner
│       ├── profile/        # Giao diện profile
│       └── users/          # Giao diện quản lý user
│
├── routes/                 # Định nghĩa route
│   ├── web.php             # Route cho Web (Blade)
│   ├── api.php             # Route cho API (JSON)
│   ├── auth.php            # Route xác thực web
│   └── console.php         # Route cho Artisan commands
│
├── storage/                # File tạm (log, session, cache, ...)
│
├── tests/                  # Unit & Feature tests
│   ├── Feature/Auth/       # Test luồng xác thực
│   └── Unit/               # Unit test
│
├── .agent/                 # AI Agent configuration (dự án phụ trợ)
│   ├── agents/             # Định nghĩa AI agents
│   ├── skills/             # Kỹ năng / mẫu code
│   ├── workflows/          # Quy trình làm việc tự động
│   └── rules/              # Rule cho AI
│
├── vendor/                 # Composer dependencies (git ignore)
├── node_modules/           # NPM dependencies (git ignore)
│
├── FEATURE_DEVELOPMENT_GUIDE.md   # Hướng dẫn phát triển tính năng
├── AUTH_FLOW_GUIDE.md             # Hướng dẫn luồng xác thực
├── DATABASE_RELATIONSHIPS.md      # Sơ đồ quan hệ CSDL
└── README.md                      # Giới thiệu dự án
```

---

## 🧩 CHI TIẾT TỪNG THÀNH PHẦN

### 1. `app/` — Mã nguồn Backend

Thư mục trung tâm chứa toàn bộ logic PHP của ứng dụng.

#### `app/Enums/`
Chứa các Enum class — hằng số cố định dùng trong toàn bộ dự án.

| File | Mục đích |
|------|----------|
| `RoleEnum.php` | Định nghĩa role: `admin`, `owner`, `customer` — dùng trong code thay vì viết string cứng |

> **Cách dùng:** `RoleEnum::ADMIN`, `RoleEnum::CUSTOMER`, `RoleEnum::values()`

---

#### `app/Models/`
Chứa Eloquent Models — tương ứng với từng bảng trong CSDL.

| File | Bảng | Mục đích |
|------|------|----------|
| `User.php` | `users` | Người dùng (có role_id → Role) |
| `Role.php` | `roles` | Vai trò (admin, owner, customer) |

> Mỗi Model là 1 class PHP extends `Model` (hoặc `Authenticatable`), chứa relationships, scopes, accessors, mutators. **Không viết logic nghiệp vụ trong Model** — chỉ viết logic liên quan đến dữ liệu.

---

#### `app/Http/Controllers/`
Xử lý request, điều phối luồng dữ liệu giữa Request → Model → Response.

| Thư mục | Mục đích |
|----------|----------|
| `Auth/` | Xác thực Web (login, register, password reset, email verify) |
| `Api/` | API endpoints cho Mobile App |
| `Controller.php` | Base controller (tất cả controller đều extends class này) |

> **Mỗi Controller chỉ nên có 1 nhiệm vụ duy nhất.** VD: `Auth/AuthenticatedSessionController` chỉ xử lý login/logout.

---

#### `app/Http/Middleware/`
Lọc request trước khi vào Controller.

| File | Alias | Mục đích |
|------|-------|----------|
| `EnsureUserHasRole.php` | `role` | Kiểm tra user có role phù hợp không, nếu không trả về 403 |

> Middleware đăng ký trong `bootstrap/app.php`. Dùng trong route: `->middleware(['auth', 'role:admin'])`.

---

#### `app/Http/Requests/`
Form Request — chứa logic validation + authorization riêng cho từng action.

| Thư mục | Mục đích |
|----------|----------|
| `Auth/` | Validation cho web auth |
| `Api/Auth/` | Validation cho API auth (LoginRequest, RegisterRequest, ...) |
| `User/` | Validation cho CRUD user |

> **Nguyên tắc:** Không viết `$request->validate(...)` trong Controller. Luôn tạo Form Request riêng.

---

#### `app/Http/Resources/`
API Resources — định dạng dữ liệu trả về JSON chuẩn cho Mobile App.

| File | Mục đích |
|------|----------|
| `UserResource.php` | Format dữ liệu User khi trả về API |
| `RoleResource.php` | Format dữ liệu Role khi trả về API |

---

#### `app/Traits/`
Chia sẻ method dùng chung giữa nhiều class.

| File | Mục đích |
|------|----------|
| `ApiResponse.php` | Chuẩn hóa JSON response cho toàn bộ API (`success()`, `error()`, `paginated()`) |

---

#### `app/View/Components/`
Blade Components — class PHP đi kèm với component Blade.

| File | View | Mục đích |
|------|------|----------|
| `AppLayout.php` | `layouts/app.blade.php` | Layout chính cho user đã đăng nhập |
| `GuestLayout.php` | `layouts/guest.blade.php` | Layout cho trang không cần login |

---

### 2. `bootstrap/` — Khởi động Framework

| File | Mục đích |
|------|----------|
| `app.php` | Cấu hình middleware alias (`role`, `abilities`, `ability`), xử lý exception cho API |

> **Thường chỉ sửa khi:** Thêm middleware mới, thay đổi cách xử lý lỗi.

---

### 3. `config/` — Cấu hình

Chứa toàn bộ file cấu hình của Laravel và các package.

| File | Mục đích |
|------|----------|
| `app.php` | Cấu hình app (name, timezone, locale, ...) |
| `auth.php` | Cấu hình guard (`web`, `sanctum`), password broker |
| `database.php` | Kết nối CSDL (mysql, sqlite, ...) |
| `sanctum.php` | Cấu hình Sanctum (API tokens) |
| `cors.php` | CORS settings |

> **Khi config ở đây:** Có thể đọc bằng `config('auth.defaults.guard')`, `config('app.name')`.

---

### 4. `database/` — Cơ sở Dữ liệu

| Thư mục | Mục đích |
|----------|----------|
| `migrations/` | File định nghĩa cấu trúc bảng. Chạy theo thứ tự: `0001_01_01_000000_create_users_table.php` → ... → `06_payments_table.php` |
| `factories/` | Factory tạo dữ liệu giả (dùng trong seeder hoặc test) |
| `seeders/` | Nạp dữ liệu mẫu (VD: tạo role mặc định, tài khoản admin) |

> ⚠️ **Quan trọng:** Migration đã được thiết kế sẵn. **Không tự ý tạo migration mới.** Nếu cần thay đổi, báo lead kiến trúc.

---

### 5. `public/` — Document Root

Thư mục gốc mà web server (Nginx/Apache) trỏ tới.

| File/Folder | Mục đích |
|-------------|----------|
| `index.php` | Entry point — mọi request đều đi qua file này (Front Controller) |
| `.htaccess` | URL rewriting (Apache) |
| `build/` | Assets đã build từ Vite (CSS, JS) — không sửa trực tiếp |

> **Khi deploy:** Chỉ cần copy `public/` vào document root của server.

---

### 6. `resources/` — Giao diện (Frontend)

Toàn bộ mã nguồn giao diện.

#### `resources/views/` — Blade Templates

| Thư mục | Dành cho | Mục đích |
|----------|----------|----------|
| `admin/` | Admin | Dashboard, quản lý user, ... |
| `owner/` | Chủ sân | Dashboard, quản lý sân, lịch, ... |
| `customer/` | Khách hàng | Dashboard, đặt sân, lịch sử, ... |
| `auth/` | Guest | Login, register, quên mật khẩu, ... |
| `layouts/` | Chung | `app.blade.php` (layout có navigation), `guest.blade.php` (layout trống) |
| `components/` | Chung | `input-error`, `nav-link`, `dropdown`, `modal`, `primary-button`, ... |
| `profile/` | Chung | Xem/sửa thông tin cá nhân |
| `users/` | Admin | CRUD user |

> **Quy tắc:** View của role nào đặt trong thư mục role đó. Component dùng chung đặt trong `components/`.

#### `resources/css/` — Stylesheet

File `app.css` — import Tailwind CSS. Chỉ sửa khi cần custom style toàn cục.

#### `resources/js/` — JavaScript

File `app.js` + `bootstrap.js` — import Alpine.js, Bootstrap JS.

---

### 7. `routes/` — Định tuyến

| File | Middleware chính | Mục đích |
|------|-----------------|----------|
| `web.php` | `web` (session, cookie) | Route trả về Blade view — cho trình duyệt |
| `api.php` | `api` (stateless, throttle) | Route trả về JSON — cho Mobile App |
| `auth.php` | `web` | Route xác thực (được `require` trong `web.php`) |
| `console.php` | — | Route cho Artisan commands |

> **`auth.php` được include trong `web.php`** qua dòng `require __DIR__.'/auth.php';`.

---

### 8. `tests/` — Kiểm thử

| Thư mục | Mục đích |
|----------|----------|
| `Feature/` | Test luồng tính năng (VD: đăng ký, đăng nhập, CRUD) |
| `Feature/Auth/` | Test riêng luồng xác thực |
| `Unit/` | Test từng unit nhỏ (Model method, Helper, ...) |

> Chạy test: `php artisan test`

---

### 9. `storage/` — Lưu trữ Tạm

| Thư mục | Mục đích |
|----------|----------|
| `app/public/` | File upload công khai (link symbolic → `public/storage`) |
| `framework/views/` | Blade cache (tự động xóa khi deploy) |
| `framework/cache/` | Cache |
| `framework/sessions/` | Session (file-based) |
| `logs/` | Log lỗi (`laravel.log`) |

---

### 10. `tests/` — Kiểm thử

| Thư mục | Mục đích |
|----------|----------|
| `Feature/` | Test luồng tính năng (VD: đăng ký, đăng nhập, CRUD) |
| `Feature/Auth/` | Test riêng luồng xác thực |
| `Unit/` | Test từng unit nhỏ (Model method, Helper, ...) |

---

## 🗺️ SƠ ĐỒ LUỒNG REQUEST

```
Request
  │
  ├── public/index.php (Front Controller)
  │
  ├── bootstrap/app.php (Khởi tạo middleware, exception handler)
  │
  ├── routes/web.php hoặc routes/api.php (Xác định route)
  │
  ├── Middleware (auth, role:X, throttle, ...)
  │
  ├── Controller (Xử lý logic)
  │   ├── Form Request (Validation)
  │   ├── Model (Eloquent)
  │   └── Response
  │       ├── Web: redirect() / view()
  │       └── API: (new UserResource($data))->response()
  │
  └── Response trả về client
```

---

## 🧠 NGUYÊN TẮC KHI THÊM FILE MỚI

1. **Controller** → `app/Http/Controllers/[Role]/[TênNhiệmVụ]Controller.php`
2. **Model** → `app/Models/[TênModel].php`
3. **Form Request** → `app/Http/Requests/[TênNhiệmVụ]Request.php`
4. **API Resource** → `app/Http/Resources/[TênResource].php`
5. **Blade View** → `resources/views/[role]/[tính_năng]/[action].blade.php`
6. **Test** → `tests/Feature/[TênNhiệmVụ]Test.php`
