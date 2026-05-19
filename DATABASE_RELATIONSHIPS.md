# Database Relationships - CNW Play Management

## 1. Tổng quan

Dự án: **Hệ thống web quản lý đặt lịch sân thể thao**

CSDL khuyến nghị: **PostgreSQL**

Mục tiêu DB:

- Quản lý người dùng theo vai trò: `admin`, `owner`, `customer`
- Quản lý môn thể thao
- Quản lý sân thể thao của từng chủ sân
- Quản lý khung giờ đặt sân
- Quản lý lịch đặt sân
- Quản lý thanh toán
- Chống đặt trùng lịch

---

## 2. Danh sách bảng

```txt
roles
users
sports
fields
time_slots
bookings
payments
```

---

## 3. Bảng `roles`

### Mục đích

Lưu vai trò người dùng.

### Columns

| Column | Type | Constraint | Mô tả |
|---|---|---|---|
| id | bigint | PK | ID vai trò |
| name | varchar | unique | Tên role: admin, owner, customer |
| display_name | varchar | nullable | Tên hiển thị |
| created_at | timestamp | nullable | Ngày tạo |
| updated_at | timestamp | nullable | Ngày cập nhật |

### Dữ liệu mẫu

```txt
admin
owner
customer
```

### Quan hệ

```txt
roles 1-n users
```

---

## 4. Bảng `users`

### Mục đích

Lưu tài khoản người dùng.

### Columns chính

| Column | Type | Constraint | Mô tả |
|---|---|---|---|
| id | bigint | PK | ID người dùng |
| role_id | bigint | FK nullable | Liên kết roles.id |
| name | varchar | required | Họ tên |
| email | varchar | unique | Email đăng nhập |
| phone | varchar | nullable | Số điện thoại |
| address | varchar | nullable | Địa chỉ |
| password | varchar | required | Mật khẩu đã hash |
| email_verified_at | timestamp | nullable | Xác thực email |
| remember_token | varchar | nullable | Token ghi nhớ |
| created_at | timestamp | nullable | Ngày tạo |
| updated_at | timestamp | nullable | Ngày cập nhật |
| deleted_at | timestamp | nullable | Ngày xóa (Soft delete) |

### Vai trò

```txt
admin    → quản trị toàn hệ thống
owner    → chủ sân, quản lý sân của mình
customer → khách hàng, đặt lịch sân
```

### Quan hệ

```txt
users n-1 roles
users(owner) 1-n fields
users(customer) 1-n bookings
```

---

## 5. Bảng `sports`

### Mục đích

Lưu loại môn thể thao.

### Columns

| Column | Type | Constraint | Mô tả |
|---|---|---|---|
| id | bigint | PK | ID môn thể thao |
| name | varchar | unique | Tên môn: Bóng đá, Cầu lông, Tennis |
| slug | varchar | unique | Slug URL |
| description | text | nullable | Mô tả |
| is_active | boolean | default true | Trạng thái hoạt động |
| created_at | timestamp | nullable | Ngày tạo |
| updated_at | timestamp | nullable | Ngày cập nhật |
| deleted_at | timestamp | nullable | Ngày xóa (Soft delete) |

### Quan hệ

```txt
sports 1-n fields
```

---

## 6. Bảng `fields`

### Mục đích

Lưu thông tin sân thể thao.

### Columns

| Column | Type | Constraint | Mô tả |
|---|---|---|---|
| id | bigint | PK | ID sân |
| owner_id | bigint | FK users.id | Chủ sân |
| sport_id | bigint | FK sports.id | Loại môn thể thao |
| name | varchar | required | Tên sân |
| code | varchar | unique nullable | Mã sân |
| description | text | nullable | Mô tả |
| address | varchar | required | Địa chỉ sân |
| price_per_hour | decimal(12,2) | required | Giá thuê/giờ |
| open_time | time | nullable | Giờ mở cửa |
| close_time | time | nullable | Giờ đóng cửa |
| image | varchar | nullable | Ảnh sân |
| status | enum | default active | active, maintenance, inactive |
| created_at | timestamp | nullable | Ngày tạo |
| updated_at | timestamp | nullable | Ngày cập nhật |
| deleted_at | timestamp | nullable | Ngày xóa (Soft delete) |

### Status

```txt
active      → đang hoạt động
maintenance → đang bảo trì
inactive    → ngừng hoạt động
```

### Quan hệ

```txt
fields n-1 users(owner)
fields n-1 sports
fields 1-n bookings
fields 1-n time_slots
```

---

## 7. Bảng `time_slots`

### Mục đích

Lưu khung giờ đặt sân được tùy chỉnh cho từng sân cụ thể (mỗi sân có thể có khung giờ khác nhau: 60 phút, 90 phút...).

### Columns

| Column | Type | Constraint | Mô tả |
|---|---|---|---|
| id | bigint | PK | ID khung giờ |
| field_id | bigint | FK fields.id | Sân sở hữu khung giờ |
| start_time | time | required | Giờ bắt đầu |
| end_time | time | required | Giờ kết thúc |
| is_active | boolean | default true | Trạng thái hoạt động |
| created_at | timestamp | nullable | Ngày tạo |
| updated_at | timestamp | nullable | Ngày cập nhật |

### Unique

```txt
field_id + start_time + end_time unique
```

### Dữ liệu mẫu (Cho Sân A - Bóng đá 60p)

```txt
06:00 - 07:00
07:00 - 08:00
```

### Dữ liệu mẫu (Cho Sân B - Tennis 90p)

```txt
06:00 - 07:30
07:30 - 09:00
```

### Quan hệ

```txt
time_slots n-1 fields
time_slots 1-n bookings
```

---

## 8. Bảng `bookings`

### Mục đích

Lưu lịch đặt sân.

### Columns

| Column | Type | Constraint | Mô tả |
|---|---|---|---|
| id | bigint | PK | ID đặt lịch |
| customer_id | bigint | FK users.id | Khách hàng đặt sân |
| field_id | bigint | FK fields.id | Sân được đặt |
| time_slot_id | bigint | FK time_slots.id | Khung giờ đặt |
| booking_date | date | required | Ngày đặt sân |
| total_price | decimal(12,2) | default 0 | Tổng tiền |
| status | enum | default pending | Trạng thái đặt lịch |
| note | text | nullable | Ghi chú |
| confirmed_at | timestamp | nullable | Thời điểm xác nhận |
| cancelled_at | timestamp | nullable | Thời điểm hủy |
| created_at | timestamp | nullable | Ngày tạo |
| updated_at | timestamp | nullable | Ngày cập nhật |

### Status

```txt
pending   → chờ xác nhận
confirmed → đã xác nhận
cancelled → đã hủy
completed → đã hoàn thành
```

### Rule chống trùng lịch

Một sân không thể có 2 booking cùng ngày + cùng khung giờ nếu booking đang:

```txt
pending
confirmed
```

Unique logic:

```txt
field_id + booking_date + time_slot_id
```

Với PostgreSQL dùng partial unique index:

```sql
CREATE UNIQUE INDEX bookings_no_overlap_active_unique
ON bookings (field_id, booking_date, time_slot_id)
WHERE status IN ('pending', 'confirmed');
```

### Quan hệ

```txt
bookings n-1 users(customer)
bookings n-1 fields
bookings n-1 time_slots
bookings 1-1 payments
```

---

## 9. Bảng `payments`

### Mục đích

Lưu thanh toán cho booking.

### Columns

| Column | Type | Constraint | Mô tả |
|---|---|---|---|
| id | bigint | PK | ID thanh toán |
| booking_id | bigint | FK unique | Liên kết bookings.id |
| amount | decimal(12,2) | required | Số tiền thanh toán |
| method | enum | default cash | Phương thức thanh toán |
| status | enum | default unpaid | Trạng thái thanh toán |
| transaction_code | varchar | unique nullable | Mã giao dịch |
| paid_at | timestamp | nullable | Thời điểm thanh toán |
| note | text | nullable | Ghi chú |
| created_at | timestamp | nullable | Ngày tạo |
| updated_at | timestamp | nullable | Ngày cập nhật |

### Method

```txt
cash
bank_transfer
momo
vnpay
```

### Status

```txt
unpaid   → chưa thanh toán
paid     → đã thanh toán
refunded → đã hoàn tiền
```

### Quan hệ

```txt
payments 1-1 bookings
```

---

## 10. Sơ đồ quan hệ tổng quát

```txt
roles
  1 ─── n users

users(owner)
  1 ─── n fields

sports
  1 ─── n fields

users(customer)
  1 ─── n bookings

fields
  1 ─── n bookings
  1 ─── n time_slots

time_slots
  1 ─── n bookings

bookings
  1 ─── 1 payments
```

---

## 11. ERD dạng text

```txt
roles(id)
 └── users.role_id

users(id)
 ├── fields.owner_id
 └── bookings.customer_id

sports(id)
 └── fields.sport_id

fields(id)
 ├── time_slots.field_id
 └── bookings.field_id

time_slots(id)
 └── bookings.time_slot_id

bookings(id)
 └── payments.booking_id
```

---

## 12. Luồng nghiệp vụ đặt sân

```txt
customer đăng nhập
→ chọn sport
→ chọn field
→ chọn booking_date
→ chọn time_slot
→ hệ thống check trùng lịch
→ tạo booking status=pending
→ owner xác nhận
→ booking status=confirmed
→ customer thanh toán
→ payment status=paid
→ sau khi sử dụng sân
→ booking status=completed
```

---

## 13. Quyền thao tác theo role

### Admin

```txt
Quản lý users
Quản lý roles
Quản lý sports
Xem toàn bộ fields
Xem toàn bộ bookings
Xem toàn bộ payments
Xem báo cáo hệ thống
```

### Owner

```txt
Quản lý sân của mình
Xem booking của sân mình
Xác nhận booking
Hủy booking
Ghi nhận thanh toán
Xem doanh thu sân mình
```

### Customer

```txt
Xem danh sách sân
Tìm kiếm sân
Đặt lịch sân
Xem lịch đặt của mình
Hủy booking của mình nếu chưa confirmed/completed
Thanh toán booking
```

---

## 14. Gợi ý migration order

```txt
1. roles
2. users
3. sports
4. fields
5. time_slots
6. bookings
7. payments
```

Trong dự án hiện tại:

```txt
2026_05_18_113246_create_01_roles_table.php
2026_05_18_113247_create_02_sports_table.php
2026_05_18_113248_create_03_fields_table.php
2026_05_18_113249_create_04_time_slots_table.php
2026_05_18_113250_create_05_bookings_table.php
2026_05_18_113251_create_06_payments_table.php
```

---

## 15. Ghi chú thiết kế

- `owner_id` trong `fields` là user có role `owner`.
- `customer_id` trong `bookings` là user có role `customer`.
- `admin` không trực tiếp sở hữu sân, chỉ quản trị.
- `payment.booking_id` unique để đảm bảo mỗi booking chỉ có một bản ghi thanh toán.
- PostgreSQL phù hợp hơn MongoDB vì hệ thống có nhiều quan hệ, khóa ngoại, transaction, constraint.
