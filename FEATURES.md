# 📊 DANH SÁCH TÍNH NĂNG - PLAYMANAGEMENT

> **Dự án:** Hệ thống web quản lý đặt lịch sân thể thao
>
> **Tổng số tính năng:** 38
>
> **Tổng số module:** 8 (Auth, Users, Sports, Fields, TimeSlots, Bookings, Payments, Profile)

---

## 🔐 Nhóm 1: Xác thực & Tài khoản (6 tính năng)

| # | Tính năng | Mô tả | Màn hình | DB Table |
|---|-----------|-------|----------|----------|
| 1 | Đăng ký tài khoản | Người dùng tạo tài khoản mới với họ tên, email, mật khẩu. Mặc định vai trò `customer`. | Register | `users`, `roles` |
| 2 | Đăng nhập | Xác thực bằng email + mật khẩu. Điều hướng dashboard theo role. | Login | `users` |
| 3 | Quên mật khẩu | Gửi email chứa link đặt lại mật khẩu. | Forgot Password | `users` |
| 4 | Đặt lại mật khẩu | Nhập mật khẩu mới thông qua token từ email. | Reset Password | `users` |
| 5 | Chỉnh sửa hồ sơ cá nhân | Cập nhật họ tên, số điện thoại, địa chỉ. Email chỉ đọc. | Hồ sơ cá nhân | `users` |
| 6 | Đổi mật khẩu | Đổi mật khẩu hiện tại sang mật khẩu mới. | Hồ sơ cá nhân | `users` |

---

## 🛡️ Nhóm 2: Admin - Quản trị hệ thống (10 tính năng)

| # | Tính năng | Mô tả | Màn hình | DB Table |
|---|-----------|-------|----------|----------|
| 7 | Dashboard tổng quan | Hiển thị thống kê: tổng users, tổng sân, tổng đặt lịch, doanh thu. | Admin Dashboard | all |
| 8 | Xem danh sách người dùng | Bảng dữ liệu tất cả users, lọc theo role/trạng thái, tìm kiếm, phân trang. | User Management | `users`, `roles` |
| 9 | Thêm người dùng mới | Form tạo user: họ tên, email, SĐT, địa chỉ, mật khẩu, chọn vai trò (Admin/Owner/Customer). | Thêm người dùng mới | `users`, `roles` |
| 10 | Sửa thông tin người dùng | Chỉnh sửa thông tin user hiện có, thay đổi vai trò. | Thêm người dùng mới (edit) | `users`, `roles` |
| 11 | Xóa người dùng | Soft delete user khỏi hệ thống. | User Management | `users` |
| 12 | Quản lý môn thể thao | CRUD môn thể thao: tên, slug, mô tả, bật/tắt trạng thái hoạt động. | Sports Management | `sports` |
| 13 | Xem tất cả sân thể thao | Bảng dữ liệu toàn bộ sân trong hệ thống, lọc theo môn/trạng thái, xem thông tin chủ sân. | Tất cả sân thể thao | `fields`, `sports`, `users` |
| 14 | Xem tất cả đặt lịch | Bảng toàn bộ bookings, lọc theo ngày/trạng thái/môn, xem chi tiết. Thống kê: pending, confirmed, completed, cancelled. | Quản lý đặt lịch | `bookings`, `fields`, `users` |
| 15 | Xem tất cả thanh toán | Bảng toàn bộ payments, lọc theo phương thức/trạng thái/ngày. Tổng doanh thu, phân theo unpaid/paid/refunded. | Quản lý thanh toán | `payments`, `bookings` |
| 16 | Báo cáo tổng quan hệ thống | Thống kê tổng hợp trên dashboard: biểu đồ, số liệu key metrics. | Admin Dashboard | all |

---

## 🏟️ Nhóm 3: Owner - Chủ sân (11 tính năng)

| # | Tính năng | Mô tả | Màn hình | DB Table |
|---|-----------|-------|----------|----------|
| 17 | Dashboard chủ sân | Tổng quan: số sân sở hữu, đặt lịch hôm nay, chờ xác nhận, doanh thu tháng. Danh sách đặt lịch gần đây. | Owner Dashboard | `fields`, `bookings`, `payments` |
| 18 | Xem danh sách sân của mình | Grid các sân mà owner sở hữu, hiển thị ảnh, tên, môn, giá, trạng thái. | Sân của tôi | `fields` |
| 19 | Thêm sân mới | Form tạo sân: upload ảnh, tên, mã sân, chọn môn thể thao, mô tả, địa chỉ, giá/giờ, giờ mở/đóng cửa, trạng thái. | Thêm sân mới | `fields` |
| 20 | Sửa thông tin sân | Chỉnh sửa thông tin sân hiện có. | Thêm sân mới (edit) | `fields` |
| 21 | Xóa sân | Soft delete sân khỏi hệ thống. | Sân của tôi | `fields` |
| 22 | Quản lý khung giờ | CRUD khung giờ cho từng sân: thêm/xóa slot (start_time - end_time). Hiển thị dạng timeline. | Quản lý khung giờ | `time_slots` |
| 23 | Bật/Tắt khung giờ | Toggle trạng thái `is_active` của từng time slot. | Quản lý khung giờ | `time_slots` |
| 24 | Xem đặt lịch sân mình | Bảng dữ liệu bookings thuộc sân của owner, lọc theo sân/ngày/trạng thái. | Quản lý đặt lịch sân | `bookings` |
| 25 | Xác nhận đặt lịch | Chuyển booking từ `pending` → `confirmed`. Ghi nhận `confirmed_at`. | Quản lý đặt lịch sân | `bookings` |
| 26 | Hủy đặt lịch | Chuyển booking sang `cancelled`. Ghi nhận `cancelled_at`. | Quản lý đặt lịch sân | `bookings` |
| 27 | Xem báo cáo doanh thu | Biểu đồ doanh thu theo ngày/tháng, phân theo sân. Tỷ lệ hủy, doanh thu trung bình. | Báo cáo doanh thu | `payments`, `bookings` |

---

## 🎾 Nhóm 4: Customer - Khách hàng (8 tính năng)

| # | Tính năng | Mô tả | Màn hình | DB Table |
|---|-----------|-------|----------|----------|
| 28 | Dashboard khách hàng | Tổng quan cá nhân: đặt lịch sắp tới, tổng lượt đặt, lịch sử gần đây. Quick action "Đặt lịch ngay". | Customer Dashboard | `bookings` |
| 29 | Xem danh sách sân | Duyệt danh sách sân theo dạng grid, mỗi sân hiển thị ảnh, tên, môn, giá, địa chỉ. | Venue Listings | `fields`, `sports` |
| 30 | Tìm kiếm & lọc sân | Tìm sân theo tên/địa chỉ, lọc theo môn thể thao, khoảng giá, sắp xếp. | Tìm kiếm sân thể thao | `fields`, `sports` |
| 31 | Xem chi tiết sân | Xem đầy đủ thông tin sân: ảnh, mô tả, giờ hoạt động, giá, chủ sân. Bảng khung giờ trống theo ngày. | Field Detail & Booking | `fields`, `time_slots`, `bookings` |
| 32 | Đặt lịch sân | Chọn ngày + khung giờ trống → tạo booking (`status=pending`). Hệ thống check trùng lịch tự động. | Field Detail & Booking | `bookings` |
| 33 | Xem lịch sử đặt lịch | Danh sách tất cả bookings của mình, phân tab: Sắp tới / Đã hoàn thành / Đã hủy. | Đặt lịch của tôi | `bookings` |
| 34 | Hủy đặt lịch | Hủy booking đang ở trạng thái `pending` (chưa xác nhận). | Đặt lịch của tôi | `bookings` |
| 35 | Thanh toán đặt lịch | Chọn phương thức (Tiền mặt / Chuyển khoản / MoMo / VNPay) → xác nhận thanh toán. | Thanh toán | `payments` |

---

## 🌐 Nhóm 5: Public - Trang công khai (3 tính năng)

| # | Tính năng | Mô tả | Màn hình | DB Table |
|---|-----------|-------|----------|----------|
| 36 | Trang chủ Desktop | Landing page giới thiệu hệ thống, CTA đăng ký/đăng nhập, giới thiệu môn thể thao. | Home Desktop | - |
| 37 | Trang chủ Mobile | Phiên bản mobile responsive của trang chủ. | Home Mobile | - |
| 38 | Xem sân công khai | Xem chi tiết sân không cần đăng nhập (chuyển hướng login khi đặt lịch). | Field Detail Mobile | `fields` |

---

## 📋 BẢNG TÓM TẮT

| Nhóm | Số tính năng | Vai trò |
|------|-------------|---------|
| 🔐 Xác thực & Tài khoản | **6** | Tất cả |
| 🛡️ Admin | **10** | `role:admin` |
| 🏟️ Owner | **11** | `role:owner` |
| 🎾 Customer | **8** | `role:customer` |
| 🌐 Public | **3** | Không cần đăng nhập |
| **TỔNG CỘNG** | **38** | |

---

## 🗄️ MODULE & DATABASE MAPPING

| Module | DB Tables | CRUD Operations |
|--------|-----------|-----------------|
| **Auth** | `users`, `roles` | Register, Login, Forgot/Reset Password |
| **Users** | `users`, `roles` | Create, Read, Update, Delete (Admin only) |
| **Sports** | `sports` | Create, Read, Update, Delete (Admin only) |
| **Fields** | `fields`, `sports`, `users` | Create, Read, Update, Delete (Owner), Read (Admin, Customer) |
| **Time Slots** | `time_slots`, `fields` | Create, Read, Update, Delete (Owner only) |
| **Bookings** | `bookings`, `fields`, `time_slots`, `users` | Create (Customer), Read (All roles), Update status (Owner), Cancel (Customer/Owner) |
| **Payments** | `payments`, `bookings` | Create (Customer), Read (All roles), Update status (Owner) |
| **Profile** | `users` | Read, Update (Self only) |

---

## 🔄 LUỒNG NGHIỆP VỤ CHÍNH

```
Customer đăng nhập
  → Tìm kiếm / duyệt sân thể thao
  → Chọn sân → Xem chi tiết
  → Chọn ngày + khung giờ trống
  → Hệ thống kiểm tra trùng lịch (field_id + booking_date + time_slot_id)
  → Tạo booking (status = pending)
  → Owner nhận thông báo → Xác nhận (status = confirmed)
  → Customer thanh toán (payment status = paid)
  → Sau khi sử dụng sân (booking status = completed)
```

---

## 📌 GHI CHÚ

- Mỗi tính năng tương ứng ít nhất 1 màn hình trên Stitch Design.
- Một số màn hình phục vụ nhiều tính năng (VD: form thêm/sửa dùng chung 1 màn).
- Tính năng CRUD được đếm riêng (Create, Read, Update, Delete) khi mỗi thao tác có logic riêng biệt.
- Phân quyền qua middleware `role:admin`, `role:owner`, `role:customer`.
- Chống đặt trùng lịch qua partial unique index trên PostgreSQL.
