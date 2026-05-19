# 🐳 HƯỚNG DẪN CHẠY DỰ ÁN VỚI DOCKER (LOCAL)

## 📋 Yêu cầu

- [Docker](https://docs.docker.com/get-docker/) (>= 24.x)
- [Docker Compose](https://docs.docker.com/compose/install/) (>= 2.x) — thường đi kèm với Docker Desktop

---

## 🚀 Khởi động

### 1. Clone dự án

```bash
git clone <repository-url>
cd CNW-PlayManagement
```

### 2. (Quan trọng) Tạo file `.env`

Nếu chưa có file `.env`, copy từ `.env.example`:

```bash
cp .env.example .env
```

> File `.env` đã được cấu hình sẵn PostgreSQL kết nối tới container `db`. Không cần sửa gì thêm.

### 3. Build & chạy container

**Lần đầu tiên** (hoặc khi có thay đổi về dependency):

```bash
docker compose up -d --build
```

**Các lần sau** (chỉ chạy lại container):

```bash
docker compose up -d
```

> `-d` chạy ngầm (detached). Bỏ `-d` nếu muốn xem log trực tiếp.

### 4. Kiểm tra trạng thái

```bash
docker compose ps
```

Cả 3 container đều phải ở trạng thái `Up`:
- `playmanagement-app` — PHP-FPM
- `playmanagement-nginx` — Nginx (cổng 80)
- `playmanagement-db` — PostgreSQL (cổng 5432)

### 5. Truy cập

| Ứng dụng | URL |
|----------|-----|
| Web app | http://localhost |
| Database | `localhost:5432` (dùng bất kỳ client PostgreSQL nào) |

> Mặc định PostgreSQL user: `playmanagement`, password: `playmanagement_secret`, database: `playmanagement`

---

## 🛠️ Development Mode

Khi cần code và test liên tục, chạy với target `dev` để cài đủ dev dependencies:

```bash
DOCKER_TARGET=dev APP_ENV=local APP_DEBUG=true docker compose up -d --build
```

Sau đó file thay đổi trong source code sẽ được tự động cập nhật nhờ volume mount.

---

## 📦 Các lệnh thường dùng

### Xem log

```bash
# Tất cả container
docker compose logs -f

# Riêng app
docker compose logs -f app

# Riêng database
docker compose logs -f db
```

### Chạy Artisan command

```bash
docker compose exec app php artisan route:list
docker compose exec app php artisan tinker
docker compose exec app php artisan make:controller TestController
```

### Chạy migration / seeder

```bash
# Đã chạy tự động khi container khởi động.
# Nếu cần chạy lại:
docker compose exec app php artisan migrate:fresh --seed
```

### Build lại (khi thêm Composer/NPM dependency)

```bash
docker compose up -d --build
```

### Dừng container

```bash
docker compose down
```

Xóa luôn volume database (mất hết dữ liệu):

```bash
docker compose down -v
```

---

## 🗄️ Kết nối Database

### Bằng command line

```bash
docker compose exec db psql -U playmanagement -d playmanagement
```

### Bằng GUI (TablePlus, DBeaver, pgAdmin...)

| Field | Value |
|-------|-------|
| Host | `localhost` |
| Port | `5432` |
| User | `playmanagement` |
| Password | `playmanagement_secret` |
| Database | `playmanagement` |

---

## ⚠️ Lưu ý

- **Lần đầu build** có thể mất 5-10 phút do phải tải image PHP, Composer, NPM packages.
- **Cổng 80** phải trống trên máy host. Nếu bị trùng, sửa trong `docker-compose.yml`:
  ```yaml
  ports:
    - "8080:80"   # chạy ở cổng 8080 thay vì 80
  ```
- **Volume `vendor` và `node_modules`** được tách riêng, không bị ghi đè bởi code host.
- **Database data** được lưu trong volume `postgres_data`, tồn tại ngay cả khi dừng container. Xoá bằng `docker compose down -v`.
