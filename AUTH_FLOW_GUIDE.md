# 🔐 HƯỚNG DẪN LUỒNG XÁC THỰC (AUTH FLOW) - PLAYMANAGEMENT

Tài liệu này mô tả chi tiết luồng **Đăng ký**, **Đăng nhập**, **Phân quyền (Role-based)** trong hệ thống PlayManagement. Các developer trong team **PHẢI** đọc và follow để đảm bảo tính nhất quán.

---

## 📦 KIẾN TRÚC AUTH

```
User (id, name, email, password, role_id, email_verified_at)
  │
  └──> Role (id, name, display_name)
         ├── admin
         ├── owner
         └── customer
```

- **User** có `role_id` (FK → roles.id).
- **Middleware**: `auth` (xác thực), `role:X` (kiểm tra role).
- **User Model** có method `hasRole(string|array $roles): bool`.

---

## 1️⃣ LUỒNG ĐĂNG KÝ (REGISTER)

### Sơ đồ:

```
[Form Đăng ký] → POST /register
       │
       ├── Validate: name, email, password, password_confirmation
       ├── Gán role_id = customer (mặc định)
       ├── Tạo User
       ├── (BỎ QUA) Gửi email xác thực
       ├── (BỎ QUA) Tự động login
       └── Redirect → /login (kèm thông báo thành công)
```

### Code mẫu (`RegisteredUserController@store`):

```php
public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name'     => ['required', 'string', 'max:255'],
        'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $customerRole = Role::firstOrCreate(
        ['name' => 'customer'],
        ['display_name' => 'Customer']
    );

    $user = User::create([
        'role_id'  => $customerRole->id,
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // (Tạm tắt) event(new Registered($user));  // gửi email xác thực
    // (Tạm tắt) Auth::login($user);            // tự động login

    return redirect(route('login', absolute: false));
}
```

### ⚠️ QUY TẮC KHI ĐĂNG KÝ:
| Tình huống | Cách xử lý |
|------------|-------------|
| User tự đăng ký | Luôn gán `role_id = customer` |
| Admin tạo user từ dashboard | Dùng `UserController` ở `/admin/users`, có thể chọn role |
| Thêm field mới vào form register | Thêm vào `$fillable` của User + validation trong `store()` |

---

## 2️⃣ LUỒNG ĐĂNG NHẬP (LOGIN)

### Sơ đồ:

```
[Form Đăng nhập] → POST /login
       │
       ├── Validate: email, password
       ├── Rate limit: 5 lần/IP+email
       ├── Auth::attempt()
       ├── Regenerate session
       ├── Kiểm tra role:
       │    ├── admin  → redirect → /admin/dashboard
       │    ├── owner  → redirect → /owner/dashboard
       │    └── customer → redirect → /customer/dashboard
       └── Dùng redirect()->intended() để giữ nguyên URL trước đó
```

### Code mẫu (`AuthenticatedSessionController@store`):

```php
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();

    $user = Auth::user();

    if ($user->hasRole('admin')) {
        return redirect()->intended(route('admin.dashboard', absolute: false));
    } elseif ($user->hasRole('owner')) {
        return redirect()->intended(route('owner.dashboard', absolute: false));
    } else {
        return redirect()->intended(route('customer.dashboard', absolute: false));
    }
}
```

### ⚠️ QUY TẮC KHI THÊM ROLE MỚI:
Nếu bạn thêm role mới (VD: `staff`), phải cập nhật **cả 2 chỗ**:
1. `AuthenticatedSessionController@store` — thêm `elseif` redirect.
2. Route group trong `routes/web.php` — thêm group với `role:staff`.

---

## 3️⃣ BẢO VỆ ROUTE (MIDDLEWARE)

### Các middleware có sẵn:

| Middleware | Mục đích | Áp dụng |
|-----------|----------|---------|
| `auth` | Yêu cầu đăng nhập | Mọi route cần auth |
| `role:admin` | Chỉ admin | Route nhóm admin |
| `role:owner` | Chỉ chủ sân | Route nhóm owner |
| `role:customer` | Chỉ khách hàng | Route nhóm customer |
| `guest` | Chỉ người chưa đăng nhập | Trang login, register |

### Cách đăng ký route:

Mở `routes/web.php` và đặt route vào đúng nhóm role:

```php
// ─── PUBLIC (không cần đăng nhập) ───
Route::get('/san-pham', [ProductController::class, 'index'])->name('products.index');

// ─── CẦN ĐĂNG NHẬP (nhưng không phân biệt role) ───
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});

// ─── ADMIN ───
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
});

// ─── OWNER ───
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');
});

// ─── CUSTOMER ───
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
});
```

> **Lưu ý:** `'verified'` middleware đã được tạm gỡ khỏi toàn bộ route. Không thêm lại khi chưa có chỉ đạo.

---

## 4️⃣ KIỂM TRA ROLE TRONG CONTROLLER

Dùng method `hasRole()` có sẵn trên User model:

```php
// Kiểm tra 1 role
if (auth()->user()->hasRole('admin')) { ... }

// Kiểm tra nhiều role (OR)
if (auth()->user()->hasRole(['admin', 'owner'])) { ... }

// Lấy tên role
$roleName = auth()->user()->role->name;

// Lấy display_name
$displayName = auth()->user()->role->display_name;
```

---

## 5️⃣ KIỂM TRA ROLE TRONG BLADE VIEW

```blade
@auth
    @if(auth()->user()->hasRole('admin'))
        <a href="{{ route('admin.dashboard') }}">Trang quản trị</a>
    @elseif(auth()->user()->hasRole('owner'))
        <a href="{{ route('owner.dashboard') }}">Trang chủ sân</a>
    @else
        <a href="{{ route('customer.dashboard') }}">Trang khách hàng</a>
    @endif
@endauth

@guest
    <a href="{{ route('login') }}">Đăng nhập</a>
@endauth
```

---

## 6️⃣ LÀM VIỆC VỚI USER TRONG CONTROLLER

### Lấy user hiện tại:
```php
$user = Auth::user();              // instance User hoặc null
$user = auth()->user();            // helper function
$id   = Auth::id();                // chỉ lấy ID
```

### Tạo user với role cụ thể:
```php
$role = Role::where('name', 'owner')->first();

User::create([
    'role_id'  => $role->id,
    'name'     => $request->name,
    'email'    => $request->email,
    'password' => Hash::make($request->password),
]);
```

---

## 7️⃣ CÁC FILE QUAN TRỌNG CẦN BIẾT

| File | Vai trò |
|------|---------|
| `app/Models/User.php` | Model User — trait `HasRoles`, `HasApiTokens`, `SoftDeletes` |
| `app/Models/Role.php` | Model Role — `hasMany(User)` |
| `app/Http/Middleware/EnsureUserHasRole.php` | Middleware kiểm tra role, alias `role` |
| `app/Http/Controllers/Auth/RegisteredUserController.php` | Xử lý đăng ký |
| `app/Http/Controllers/Auth/AuthenticatedSessionController.php` | Xử lý đăng nhập, role redirect |
| `app/Http/Requests/Auth/LoginRequest.php` | Validate + rate limit đăng nhập |
| `routes/web.php` | Route web + role groups |
| `routes/auth.php` | Route auth (login, register, logout, ...) |
| `bootstrap/app.php` | Đăng ký alias middleware `role` |

---

## 8️⃣ CÁC LỖI THƯỜNG GẶP & CÁCH XỬ LÝ

| Lỗi | Nguyên nhân | Giải pháp |
|-----|-------------|-----------|
| `403 Forbidden` | User không có role phù hợp | Kiểm tra `role_id` trong DB, dùng `User::find()->hasRole('...')` |
| `Unauthenticated` (API) | Token hết hạn hoặc không gửi | Gửi `Authorization: Bearer {token}` trong header |
| Không redirect đúng role sau login | Thiếu `elseif` trong `AuthenticatedSessionController` | Thêm nhánh cho role mới |
| Route báo `404` | Route đặt sai prefix hoặc sai method | Kiểm tra `php artisan route:list` |

---

## 9️⃣ CHECKLIST KHI THÊM TÍNH NĂNG MỚI

- [ ] Tính năng dành cho ai? (admin / owner / customer / public)
- [ ] Đã đặt route đúng group middleware chưa? (`auth`, `role:X`)
- [ ] Nếu là API, đã dùng `auth:sanctum` thay vì `auth` chưa?
- [ ] Có cần kiểm tra role trong Controller không? Dùng `$user->hasRole()`
- [ ] Trong Blade, đã kiểm tra `@auth` / `@guest` / role chưa?
- [ ] Nếu thêm role mới, đã update `AuthenticatedSessionController` chưa?
