# MANUAL BOOK
## Sistem Log Generator - Versi Web Admin

---

### INFORMASI DOKUMEN

| Informasi | Detail |
|-----------|--------|
| **Nama Sistem** | Log Generator Web Admin |
| **Versi** | 1.0.0 |
| **Platform** | Website (Browser-based) |
| **Dikembangkan Oleh** | Tim Pengembang UNP & UNTL |
| **Tanggal Rilis** | 2026 |
| **Hak Cipta** | © 2026 Universitas Negeri Padang & Universidade Nacional Timor Lorosa'e |

---

## DAFTAR ISI

1. [Pendahuluan](#1-pendahuluan)
2. [Persyaratan Sistem](#2-persyaratan-sistem)
3. [Arsitektur Sistem](#3-arsitektur-sistem)
4. [Peran dan Hak Akses](#4-peran-dan-hak-akses)
5. [Autentikasi Admin](#5-autentikasi-admin)
6. [Dashboard](#6-dashboard)
7. [Manajemen Pengguna](#7-manajemen-pengguna)
8. [Manajemen Role dan Permission](#8-manajemen-role-dan-permission)
9. [Manajemen Institusi](#9-manajemen-institusi)
10. [Template Logbook](#10-template-logbook)
11. [Data Types Management](#11-data-types-management)
12. [Available Templates](#12-available-templates)
13. [Logbook Data Management](#13-logbook-data-management)
14. [Participant Management](#14-participant-management)
15. [Verification System](#15-verification-system)
16. [Export Management](#16-export-management)
17. [Notification Management](#17-notification-management)
18. [Audit Trail](#18-audit-trail)
19. [Reports & Analytics](#19-reports--analytics)
20. [API Reference](#20-api-reference)
21. [Troubleshooting](#21-troubleshooting)
22. [Glosarium](#22-glosarium)

---

## 1. PENDAHULUAN

### 1.1 Tentang Log Generator Web Admin

**Log Generator Web Admin** adalah sistem manajemen berbasis web yang menyediakan antarmuka administrasi untuk mengelola seluruh aspek aplikasi Log Generator. Sistem ini dikembangkan menggunakan framework Laravel dan menyediakan RESTful API untuk komunikasi dengan aplikasi mobile Android.

### 1.2 Tujuan Sistem

Sistem Log Generator Web Admin bertujuan untuk:

1. **Manajemen Terpusat**: Menyediakan pusat kendali untuk seluruh operasi sistem
2. **Role-Based Access Control (RBAC)**: Mengimplementasikan kontrol akses berbasis peran dengan granular permissions
3. **Audit Trail**: Mencatat seluruh aktivitas sistem untuk keperluan audit dan keamanan
4. **Integrasi API**: Menyediakan API yang aman untuk aplikasi mobile dan integrasi pihak ketiga
5. **Ekspor Data**: Menghasilkan laporan dan dokumen dalam berbagai format

### 1.3 Fitur Utama Web Admin

| No | Modul | Deskripsi |
|----|-------|-----------|
| 1 | Dashboard | Statistik dan ringkasan sistem secara real-time |
| 2 | User Management | Pengelolaan pengguna dan autentikasi |
| 3 | Role & Permission | Pengaturan hak akses dan peran |
| 4 | Institution Management | Pengelolaan institusi dan organisasi |
| 5 | Template Management | Pengelolaan template logbook standar |
| 6 | Data Types | Konfigurasi tipe data yang tersedia |
| 7 | Logbook Data | Pengelolaan data logbook secara keseluruhan |
| 8 | Participant Management | Pengelolaan partisipan lintas template |
| 9 | Verification System | Sistem verifikasi data |
| 10 | Export Management | Manajemen ekspor dokumen |
| 11 | Notification System | Sistem notifikasi push dan in-app |
| 12 | Audit Trail | Pencatatan dan pemantauan aktivitas |
| 13 | Reports & Analytics | Laporan dan analisis data |

### 1.4 Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| **Backend Framework** | Laravel 12.0 |
| **Runtime** | PHP 8.2 |
| **Database** | PostgreSQL |
| **Authentication** | Laravel Sanctum 4.2 |
| **Authorization** | Spatie Laravel Permission 6.21 |
| **Frontend Admin** | Blade Templates, Tailwind CSS |
| **Charts** | Chart.js |
| **Document Export** | PHPWord 1.3, DomPDF 3.1 |
| **Push Notification** | Firebase Cloud Messaging |
| **Email** | Laravel Mail dengan Brevo SMTP |

---

## 2. PERSYARATAN SISTEM

### 2.1 Persyaratan Server

| Komponen | Minimum | Rekomendasi |
|----------|---------|-------------|
| **CPU** | 2 Core | 4 Core |
| **RAM** | 2 GB | 4 GB |
| **Storage** | 20 GB SSD | 50 GB SSD |
| **OS** | Ubuntu 20.04 LTS | Ubuntu 22.04 LTS |

### 2.2 Persyaratan Software

| Software | Versi |
|----------|-------|
| **PHP** | >= 8.2 |
| **Composer** | >= 2.0 |
| **Node.js** | >= 18.0 |
| **PostgreSQL** | >= 14.0 |
| **Nginx/Apache** | Latest stable |
| **Redis** (Optional) | >= 6.0 |

### 2.3 Persyaratan Browser (Admin)

| Browser | Versi Minimum |
|---------|---------------|
| **Google Chrome** | 90+ |
| **Mozilla Firefox** | 88+ |
| **Microsoft Edge** | 90+ |
| **Safari** | 14+ |

### 2.4 PHP Extensions Required

```
- BCMath PHP Extension
- Ctype PHP Extension
- cURL PHP Extension
- DOM PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PCRE PHP Extension
- PDO PHP Extension
- PDO PostgreSQL Extension
- Tokenizer PHP Extension
- XML PHP Extension
- ZIP PHP Extension
```

---

## 3. ARSITEKTUR SISTEM

### 3.1 Overview Arsitektur

```
┌─────────────────────────────────────────────────────────────────┐
│                         CLIENTS                                  │
├─────────────────────┬───────────────────────────────────────────┤
│   Android App       │         Web Admin (Browser)               │
│   (Flutter)         │         (Blade + Tailwind)                │
└─────────┬───────────┴─────────────────────┬─────────────────────┘
          │                                 │
          ▼                                 ▼
┌─────────────────────────────────────────────────────────────────┐
│                      LARAVEL API                                 │
├─────────────────────────────────────────────────────────────────┤
│  • Authentication (Sanctum)                                      │
│  • Authorization (Spatie Permission)                             │
│  • Rate Limiting (Throttle)                                      │
│  • Request Validation                                            │
└─────────────────────────────────────────────────────────────────┘
          │
          ▼
┌─────────────────────────────────────────────────────────────────┐
│                    BUSINESS LOGIC LAYER                          │
├─────────────────────────────────────────────────────────────────┤
│  Controllers │ Services │ Events │ Listeners │ Jobs             │
└─────────────────────────────────────────────────────────────────┘
          │
          ▼
┌─────────────────────────────────────────────────────────────────┐
│                      DATA LAYER                                  │
├────────────────┬───────────────────┬────────────────────────────┤
│  PostgreSQL    │   File Storage    │    Firebase FCM            │
│  (Primary DB)  │   (Documents)     │    (Push Notification)     │
└────────────────┴───────────────────┴────────────────────────────┘
```

### 3.2 Database Schema Overview

#### Tabel Utama

| Tabel | Deskripsi |
|-------|-----------|
| `users` | Data pengguna sistem |
| `institutions` | Data institusi/organisasi |
| `logbook_template` | Template logbook |
| `logbook_field` | Kolom/field dalam template |
| `logbook_datas` | Data entri logbook |
| `logbook_data_verifications` | Verifikasi data |
| `logbook_participants` | Data partisipan |
| `logbook_roles` | Peran dalam logbook |
| `user_logbook_access` | Hak akses pengguna ke logbook |
| `logbook_exports` | Riwayat ekspor dokumen |
| `notifications` | Notifikasi sistem |
| `audit_logs` | Log audit aktivitas |
| `roles` | Role sistem (Spatie) |
| `permissions` | Permission sistem (Spatie) |

### 3.3 Entity Relationship

```
users ──┬── institutions (many-to-one)
        ├── user_logbook_access ── logbook_template
        ├── audit_logs
        ├── notifications
        └── fcm_tokens

logbook_template ──┬── logbook_field
                   ├── logbook_datas ── logbook_data_verifications
                   ├── logbook_participants
                   ├── user_logbook_access
                   └── logbook_exports

institutions ──┬── users
               ├── logbook_template
               ├── available_templates
               └── required_data_participants
```

---

## 4. PERAN DAN HAK AKSES

### 4.1 Sistem Role

Sistem menggunakan dua lapisan role:

1. **System Roles** (via Spatie Permission)
2. **Logbook Roles** (per template)

### 4.2 System Roles

| Role | Level | Deskripsi |
|------|-------|-----------|
| **Super Admin** | Tertinggi | Akses penuh ke semua fitur sistem |
| **Admin** | Tinggi | Manajemen pengguna, institusi, dan template |
| **Manager** | Menengah | Pengelolaan template dan laporan |
| **Institution Admin** | Menengah | Admin untuk satu institusi |
| **User** | Dasar | Pengguna standar aplikasi mobile |

### 4.3 Logbook Roles

| Role | Akses |
|------|-------|
| **Owner** | Pemilik template, akses penuh |
| **Supervisor** | Verifikasi data, memberikan nilai |
| **Editor** | Input dan edit data |
| **Viewer** | Hanya melihat data |

### 4.4 Permission Matrix - System Roles

| Permission | Super Admin | Admin | Manager | Institution Admin | User |
|------------|:-----------:|:-----:|:-------:|:-----------------:|:----:|
| users.view.all | ✓ | ✓ | - | - | - |
| users.create | ✓ | ✓ | - | - | - |
| users.manage | ✓ | ✓ | - | - | - |
| users.search | ✓ | ✓ | ✓ | ✓ | - |
| roles.manage | ✓ | ✓ | - | - | - |
| permissions.view | ✓ | ✓ | - | - | - |
| permissions.create | ✓ | - | - | - | - |
| permissions.manage | ✓ | - | - | - | - |
| institutions.manage | ✓ | ✓ | ✓ | - | - |
| institution.manage-own | ✓ | - | - | ✓ | - |
| institution.view-members | ✓ | ✓ | ✓ | ✓ | - |
| institution.manage-members | ✓ | - | - | ✓ | - |
| templates.manage | ✓ | ✓ | ✓ | ✓ | - |
| data-types.manage | ✓ | ✓ | - | - | - |
| participants.manage | ✓ | ✓ | ✓ | ✓ | - |
| required-data-participants.manage | ✓ | ✓ | - | ✓ | - |
| logbooks.export.manage | ✓ | ✓ | ✓ | - | - |
| notifications.send | ✓ | ✓ | ✓ | - | - |
| notifications.send.all | ✓ | - | - | - | - |
| system.admin | ✓ | - | - | - | - |

### 4.5 Permission Categories by Risk Level

| Risk Level | Permissions | Deskripsi |
|------------|-------------|-----------|
| **Critical** | permissions.manage, system.admin | Mempengaruhi keseluruhan sistem |
| **High** | users.manage, roles.manage | Dapat membuat perubahan signifikan |
| **Medium** | templates.manage, institutions.manage | Operasi sehari-hari |
| **Low** | notifications.send, users.search | Operasi rutin |

---

## 5. AUTENTIKASI ADMIN

### 5.1 Login Admin

#### Via Web Browser

1. Akses URL: `https://[domain]/admin/login`
2. Masukkan **Email** dan **Password**
3. Klik **"Login"**
4. Jika berhasil, redirect ke Dashboard

#### Via API

```http
POST /api/admin/login
Content-Type: application/json

{
    "email": "admin@example.com",
    "password": "your_password"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {...},
        "token": "Bearer eyJ...",
        "expires_at": "2026-02-08T12:00:00Z"
    }
}
```

### 5.2 Token Management

| Parameter | Nilai |
|-----------|-------|
| Token Type | Bearer Token (Sanctum) |
| Expiration | 24 jam (configurable) |
| Refresh | Via `/api/admin/refresh-token` |

### 5.3 Logout

```http
POST /api/admin/logout
Authorization: Bearer {token}
```

### 5.4 Rate Limiting

| Endpoint | Limit |
|----------|-------|
| Login | 5 request per menit |
| Register | 3 request per 5 menit |
| General API | 60 request per menit |
| Sensitive Operations | 20 request per menit |

### 5.5 Session Security

- **HTTPS Required**: Semua komunikasi terenkripsi
- **CSRF Protection**: Token CSRF untuk form submission
- **IP Logging**: Pencatatan IP address untuk audit
- **User Agent Logging**: Pencatatan browser/device

---

## 6. DASHBOARD

### 6.1 Overview Dashboard

Dashboard menyediakan ringkasan statistik sistem secara real-time:

```
┌────────────────────────────────────────────────────────────────┐
│                        DASHBOARD                                │
├────────────────┬───────────────┬───────────────┬───────────────┤
│  Total Users   │  Logbooks     │  Entries      │  Institutions │
│     125        │      48       │    1,234      │      12       │
├────────────────┴───────────────┴───────────────┴───────────────┤
│                   User Registration Chart                       │
│     [Line chart showing daily registrations]                    │
├─────────────────────────────────────────────────────────────────┤
│                   Logbook Activity Chart                        │
│     [Bar chart showing entries per template]                    │
├─────────────────────────────────────────────────────────────────┤
│                    Recent Activity                              │
│     • User John created new logbook "Daily Report"              │
│     • Admin verified 5 entries                                  │
│     • New user registered: jane@example.com                     │
└─────────────────────────────────────────────────────────────────┘
```

### 6.2 Dashboard Widgets

#### Statistics Cards

| Widget | Data | Refresh |
|--------|------|---------|
| Total Users | Jumlah user terdaftar | Real-time |
| Total Logbooks | Jumlah template aktif | Real-time |
| Total Entries | Jumlah entri data | Real-time |
| Total Institutions | Jumlah institusi | Real-time |

#### Charts

| Chart | Tipe | Data |
|-------|------|------|
| User Registrations | Line Chart | Registrasi harian 7 hari terakhir |
| Logbook Activity | Bar Chart | Entri per template |
| Role Distribution | Pie Chart | Distribusi role pengguna |

#### Recent Activity

Menampilkan 10 aktivitas terbaru dari audit log:
- User creation
- Logbook creation
- Data verification
- Role changes

### 6.3 API Endpoints Dashboard

```http
GET /api/admin/stats
GET /api/admin/user-registrations
GET /api/admin/logbook-activity
GET /api/admin/recent-activity
```

---

## 7. MANAJEMEN PENGGUNA

### 7.1 Daftar Pengguna

Menampilkan semua pengguna dengan informasi:

| Kolom | Deskripsi |
|-------|-----------|
| Name | Nama lengkap |
| Email | Alamat email |
| Role | System role |
| Institution | Institusi (jika ada) |
| Status | Active/Inactive |
| Email Verified | Status verifikasi email |
| Last Login | Waktu login terakhir |
| Created At | Tanggal registrasi |

#### Filters

- **Search**: Berdasarkan nama atau email
- **Role**: Filter berdasarkan role
- **Status**: Active/Inactive
- **Institution**: Filter berdasarkan institusi
- **Email Verified**: Verified/Unverified

### 7.2 Membuat User Baru

```http
POST /api/admin/users
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "SecurePassword123",
    "phone_number": "+6281234567890",
    "role": "User",
    "institution_id": "uuid" // optional
}
```

**Roles yang dapat ditugaskan:**

| Oleh | Dapat Membuat Role |
|------|-------------------|
| Super Admin | Admin, Manager, Institution Admin, User |
| Admin | Manager, Institution Admin, User |

**Catatan:**
- User yang dibuat oleh Admin otomatis terverifikasi email-nya
- Audit log otomatis dibuat

### 7.3 Update User

```http
PUT /api/admin/users/{userId}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "John Updated",
    "phone_number": "+6281234567899"
}
```

### 7.4 Update User Role

```http
PUT /api/admin/users/{userId}/role
Authorization: Bearer {token}
Content-Type: application/json

{
    "role": "Manager"
}
```

### 7.5 Toggle User Status

```http
PATCH /api/admin/users/{userId}/status
Authorization: Bearer {token}
```

Mengaktifkan/menonaktifkan user. User yang dinonaktifkan tidak dapat login.

### 7.6 Delete User

```http
DELETE /api/admin/users/{userId}
Authorization: Bearer {token}
```

**Peringatan:** Penghapusan user bersifat permanen dan akan mempengaruhi data terkait.

### 7.7 Pencarian User

```http
GET /api/users/search?q=john
Authorization: Bearer {token}
```

Digunakan untuk:
- Menambah member ke logbook
- Menambah anggota institusi
- Pencarian umum

---

## 8. MANAJEMEN ROLE DAN PERMISSION

### 8.1 Daftar Roles

```http
GET /api/roles
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Super Admin",
            "guard_name": "web",
            "is_system": true,
            "permissions_count": 45,
            "permissions": [...]
        }
    ]
}
```

### 8.2 Detail Role

```http
GET /api/roles/{id}
Authorization: Bearer {token}
```

Menampilkan:
- Informasi role
- Daftar permissions
- Jumlah users dengan role ini

### 8.3 Membuat Role Baru

```http
POST /api/roles
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Custom Role",
    "permissions": [1, 2, 3, 4]
}
```

**Catatan:** Role sistem (Super Admin, Admin, Manager, Institution Admin, User) tidak dapat dihapus.

### 8.4 Update Role

```http
PUT /api/roles/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Updated Role Name",
    "permissions": [1, 2, 3, 4, 5]
}
```

### 8.5 Delete Role

```http
DELETE /api/roles/{id}
Authorization: Bearer {token}
```

**Batasan:**
- Role sistem tidak dapat dihapus
- Role dengan users tidak dapat dihapus

### 8.6 Permission Management

#### List All Permissions

```http
GET /api/permissions
Authorization: Bearer {token}
```

#### Create Permission

```http
POST /api/permissions
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "custom.permission"
}
```

#### Assign Permission to Role

```http
POST /api/permissions/assign-to-role
Authorization: Bearer {token}
Content-Type: application/json

{
    "role_id": 2,
    "permission_ids": [10, 11, 12]
}
```

### 8.7 Permission Registry

Sistem menyediakan permission registry untuk manajemen canggih:

```http
GET /api/permission-registry
GET /api/permission-registry/risk-level/{level}
GET /api/permission-registry/my-permissions
GET /api/permission-registry/role-matrix
```

---

## 9. MANAJEMEN INSTITUSI

### 9.1 Tentang Institusi

Institusi merepresentasikan organisasi (universitas, perusahaan, dll) yang menggunakan sistem. Setiap institusi dapat memiliki:
- Admin institusi sendiri
- Template standar
- Requirement data partisipan

### 9.2 Daftar Institusi

```http
GET /api/institutions
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": "uuid",
            "name": "Universitas Negeri Padang"
        }
    ]
}
```

### 9.3 Detail Institusi

```http
GET /api/institutions/details
Authorization: Bearer {token}
```

**Response mencakup:**
- Informasi lengkap institusi
- Jumlah templates
- Jumlah users

### 9.4 Membuat Institusi

```http
POST /api/institutions
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Universitas Baru",
    "description": "Deskripsi institusi",
    "phone_number": "+6212345678",
    "address": "Jl. Contoh No. 123",
    "company_type": "University",
    "company_email": "info@universitas.ac.id"
}
```

### 9.5 Update Institusi

```http
PUT /api/institutions/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Nama Baru",
    "description": "Deskripsi baru"
}
```

**Catatan:** Mendukung partial update - hanya field yang dikirim yang akan diupdate.

### 9.6 Delete Institusi

```http
DELETE /api/institutions/{id}
Authorization: Bearer {token}
```

**Peringatan:** Institusi dengan templates atau users terkait memerlukan penanganan khusus.

### 9.7 Members Institusi

#### List Members

```http
GET /api/institutions/{id}/members
Authorization: Bearer {token}
```

#### Add Member to Institution

```http
POST /api/institution/members
Authorization: Bearer {token}
Content-Type: application/json

{
    "user_id": "uuid",
    "role": "User"
}
```

### 9.8 Institution Admin Access

Institution Admin memiliki endpoint khusus:

```http
GET /api/institution/my-institution
PUT /api/institution/my-institution
```

---

## 10. TEMPLATE LOGBOOK

### 10.1 Tentang Template

Template logbook mendefinisikan struktur logbook termasuk:
- Nama dan deskripsi
- Kolom/field dengan tipe data
- Institusi terkait (opsional)

### 10.2 Daftar Templates

```http
GET /api/templates
Authorization: Bearer {token}
```

### 10.3 Templates untuk Admin

```http
GET /api/templates/admin/all
Authorization: Bearer {token}
```

Menampilkan semua templates dengan informasi tambahan:
- Creator name dan email
- Institution name
- Total entries count

### 10.4 User Templates

```http
GET /api/templates/user
Authorization: Bearer {token}
```

Menampilkan templates yang dapat diakses oleh user yang login, termasuk role mereka di setiap template.

### 10.5 Membuat Template

```http
POST /api/templates
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Daily Activity Log",
    "description": "Template untuk catatan aktivitas harian",
    "institution_id": "uuid" // optional
}
```

**Automatic Actions:**
- User pembuat otomatis menjadi Owner
- Audit log dibuat

### 10.6 Update Template

```http
PUT /api/templates/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Updated Name",
    "description": "Updated description"
}
```

**Permission:** Owner atau Admin

### 10.7 Delete Template

```http
DELETE /api/templates/{id}
Authorization: Bearer {token}
```

**Peringatan:** Semua data terkait (fields, entries, participants) akan ikut terhapus.

### 10.8 Manajemen Fields

#### Get Fields by Template

```http
GET /api/templates/{templateId}/fields
Authorization: Bearer {token}
```

#### Create Field

```http
POST /api/fields
Authorization: Bearer {token}
Content-Type: application/json

{
    "template_id": "uuid",
    "name": "Tanggal Kegiatan",
    "data_type": "date",
    "order": 1
}
```

#### Batch Create Fields

```http
POST /api/fields/batch
Authorization: Bearer {token}
Content-Type: application/json

{
    "template_id": "uuid",
    "fields": [
        {"name": "Nama", "data_type": "text", "order": 1},
        {"name": "Tanggal", "data_type": "date", "order": 2},
        {"name": "Deskripsi", "data_type": "textarea", "order": 3}
    ]
}
```

---

## 11. DATA TYPES MANAGEMENT

### 11.1 Available Data Types

Sistem menyediakan pengelolaan tipe data yang tersedia untuk fields:

| Data Type | Input Widget | Deskripsi |
|-----------|--------------|-----------|
| `text` | TextField | Teks pendek |
| `textarea` | TextArea | Teks panjang |
| `number` | NumberField | Angka |
| `date` | DatePicker | Tanggal |
| `time` | TimePicker | Waktu |
| `datetime` | DateTimePicker | Tanggal dan waktu |
| `select` | Dropdown | Pilihan tunggal |
| `checkbox` | Checkbox | Ya/Tidak |
| `image` | ImagePicker | Gambar |
| `location` | LocationPicker | Koordinat GPS |
| `file` | FilePicker | File attachment |

### 11.2 List Data Types

```http
GET /api/available-data-types
GET /api/available-data-types/active
Authorization: Bearer {token}
```

### 11.3 Create Data Type

```http
POST /api/available-data-types
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "rating",
    "description": "Rating 1-5 stars",
    "is_active": true
}
```

### 11.4 Toggle Active Status

```http
PATCH /api/available-data-types/{id}/toggle
Authorization: Bearer {token}
```

### 11.5 Delete Data Type

```http
DELETE /api/available-data-types/{id}
Authorization: Bearer {token}
```

---

## 12. AVAILABLE TEMPLATES

### 12.1 Tentang Available Templates

Available Templates adalah template standar yang disediakan institusi untuk digunakan pengguna saat membuat logbook baru.

### 12.2 List Available Templates

```http
GET /api/available-templates
GET /api/available-templates/active
GET /api/available-templates/institution/{institutionId}
Authorization: Bearer {token}
```

### 12.3 Create Available Template

```http
POST /api/available-templates
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Laporan Praktikum",
    "description": "Template standar untuk laporan praktikum",
    "institution_id": "uuid",
    "required_columns": [
        {"name": "Tanggal", "data_type": "date"},
        {"name": "Judul", "data_type": "text"},
        {"name": "Deskripsi", "data_type": "textarea"}
    ],
    "is_active": true
}
```

### 12.4 Toggle Active Status

```http
PATCH /api/available-templates/{id}/toggle
Authorization: Bearer {token}
```

---

## 13. LOGBOOK DATA MANAGEMENT

### 13.1 Data Entries

#### List All Entries

```http
GET /api/logbook-entries
GET /api/logbook-entries/template/{templateId}
Authorization: Bearer {token}
```

#### Get Template Summary

```http
GET /api/logbook-entries/template/{templateId}/summary
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "total_entries": 150,
        "verified_count": 120,
        "pending_count": 30,
        "contributors": 5
    }
}
```

### 13.2 Create Entry

```http
POST /api/logbook-entries
Authorization: Bearer {token}
Content-Type: application/json

{
    "template_id": "uuid",
    "data": {
        "Tanggal": "2026-02-07",
        "Judul": "Kegiatan Hari Ini",
        "Deskripsi": "Deskripsi kegiatan..."
    }
}
```

### 13.3 Update Entry

```http
PUT /api/logbook-entries/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "data": {
        "Judul": "Updated Title"
    }
}
```

### 13.4 Delete Entry

```http
DELETE /api/logbook-entries/{id}
Authorization: Bearer {token}
```

---

## 14. PARTICIPANT MANAGEMENT

### 14.1 Tentang Participants

Participants adalah data peserta yang terkait dengan template logbook, seperti:
- Mahasiswa dalam logbook kehadiran
- Peserta pelatihan
- Anggota tim proyek

### 14.2 List Participants

```http
GET /api/participants?template_id={uuid}
GET /api/logbook/participants?template_id={uuid}
Authorization: Bearer {token}
```

**Query Parameters:**
- `template_id`: Required - Filter by template
- `min_grade` / `max_grade`: Filter by grade range
- `search`: Search in data JSON
- `sort_by`: grade, created_at, updated_at
- `sort_direction`: asc, desc
- `per_page`: Pagination size

### 14.3 Get Participant Stats

```http
GET /api/participants/stats?template_id={uuid}
Authorization: Bearer {token}
```

### 14.4 Create Participant

```http
POST /api/participants
Authorization: Bearer {token}
Content-Type: application/json

{
    "template_id": "uuid",
    "data": {
        "Nama Lengkap": "John Doe",
        "NIM": "12345678",
        "Email": "john@example.com"
    },
    "grade": null
}
```

### 14.5 Bulk Create Participants

```http
POST /api/participants/bulk
Authorization: Bearer {token}
Content-Type: application/json

{
    "template_id": "uuid",
    "participants": [
        {
            "data": {"Nama": "John", "NIM": "001"},
            "grade": null
        },
        {
            "data": {"Nama": "Jane", "NIM": "002"},
            "grade": null
        }
    ]
}
```

### 14.6 Update Grade

```http
PATCH /api/participants/{id}/grade
Authorization: Bearer {token}
Content-Type: application/json

{
    "template_id": "uuid",
    "grade": 85
}
```

### 14.7 Bulk Update Grades

```http
PATCH /api/participants/grades/bulk
Authorization: Bearer {token}
Content-Type: application/json

{
    "template_id": "uuid",
    "grades": [
        {"participant_id": "uuid1", "grade": 85},
        {"participant_id": "uuid2", "grade": 90}
    ]
}
```

### 14.8 Required Data Participants

Institusi dapat mendefinisikan field wajib untuk partisipan:

```http
GET /api/required-data-participants/institution/{institutionId}
GET /api/required-data-participants/template/{templateId}
Authorization: Bearer {token}
```

```http
POST /api/required-data-participants
Authorization: Bearer {token}
Content-Type: application/json

{
    "institution_id": "uuid",
    "data_name": "NIM",
    "is_active": true
}
```

---

## 15. VERIFICATION SYSTEM

### 15.1 Tentang Verification

Sistem verifikasi memungkinkan Supervisor untuk:
- Memvalidasi data yang diinputkan
- Memberikan catatan verifikasi
- Menolak data yang tidak valid

### 15.2 Get Data for Verification

```http
GET /api/logbook-data-verification/data?template_id={uuid}&verified_status=all
Authorization: Bearer {token}
```

**Query Parameters:**
- `template_id`: Required
- `verified_status`: all, verified, unverified
- `per_page`: Pagination size

### 15.3 Verify Data

```http
POST /api/logbook-data-verification/data/{dataId}/verify
Authorization: Bearer {token}
Content-Type: application/json

{
    "verification_notes": "Data valid dan lengkap"
}
```

### 15.4 Reject Data

```http
POST /api/logbook-data-verification/data/{dataId}/reject
Authorization: Bearer {token}
Content-Type: application/json

{
    "rejection_reason": "Data tidak lengkap, mohon lengkapi tanggal"
}
```

### 15.5 Bulk Verify

```http
POST /api/logbook-data-verification/bulk-verify
Authorization: Bearer {token}
Content-Type: application/json

{
    "template_id": "uuid",
    "data_ids": ["uuid1", "uuid2", "uuid3"],
    "verification_notes": "Verified in bulk"
}
```

### 15.6 Bulk Reject

```http
POST /api/logbook-data-verification/bulk-reject
Authorization: Bearer {token}
Content-Type: application/json

{
    "template_id": "uuid",
    "data_ids": ["uuid1", "uuid2"],
    "rejection_reason": "Data tidak memenuhi standar"
}
```

### 15.7 Verification Stats

```http
GET /api/logbook-data-verification/stats?template_id={uuid}
Authorization: Bearer {token}
```

---

## 16. EXPORT MANAGEMENT

### 16.1 Export Formats

| Format | Extension | Library |
|--------|-----------|---------|
| Microsoft Word | .docx | PHPWord |
| PDF | .pdf | DomPDF |

### 16.2 Export to Word

```http
GET /api/logbook-export/template/{templateId}/word
Authorization: Bearer {token}
```

### 16.3 Export to PDF

```http
GET /api/logbook-export/template/{templateId}/pdf
Authorization: Bearer {token}
```

### 16.4 Export Content

Dokumen ekspor mencakup:

1. **Header**
   - Logo institusi (jika ada)
   - Nama logbook
   - Deskripsi

2. **Identity Table**
   - Tanggal dibuat
   - Pembuat
   - Institusi
   - Total entries

3. **Data Table**
   - Semua entries dalam format tabel
   - Status verifikasi

4. **Contributors Section**
   - Daftar member dan role mereka

5. **Participants Section**
   - Daftar partisipan dengan data dan grade

6. **Footer**
   - Tanggal ekspor
   - Pengekspor

### 16.5 Export History

```http
GET /api/logbook-export/template/{templateId}/history
GET /api/logbook-export/my-exports
Authorization: Bearer {token}
```

### 16.6 Download Export

```http
GET /api/logbook-export/{exportId}/download
Authorization: Bearer {token}
```

### 16.7 Delete Export

```http
DELETE /api/logbook-export/{exportId}
Authorization: Bearer {token}
```

### 16.8 Admin Export Management

```http
GET /api/logbook-export/admin/stats
DELETE /api/logbook-export/admin/cleanup?days=30
Authorization: Bearer {token}
```

---

## 17. NOTIFICATION MANAGEMENT

### 17.1 Notification Types

| Type | Trigger | Description |
|------|---------|-------------|
| `data_verified` | Verification | Data telah diverifikasi |
| `data_rejected` | Rejection | Data ditolak |
| `logbook_invitation` | Access Grant | Undangan ke logbook |
| `admin_notification` | Admin Send | Notifikasi dari admin |
| `system_notification` | System | Pengumuman sistem |

### 17.2 User Notifications

```http
GET /api/notifications
GET /api/notifications/stats
Authorization: Bearer {token}
```

### 17.3 Mark as Read

```http
POST /api/notifications/{id}/read
POST /api/notifications/mark-all-read
Authorization: Bearer {token}
```

### 17.4 Delete Notification

```http
DELETE /api/notifications/{id}
Authorization: Bearer {token}
```

### 17.5 Send Notification (Admin)

```http
POST /api/notifications/send
Authorization: Bearer {token}
Content-Type: application/json

{
    "user_ids": ["uuid1", "uuid2"],
    "title": "Pengumuman Penting",
    "message": "Isi pesan...",
    "action_text": "Lihat Detail",
    "action_url": "https://..."
}
```

### 17.6 Send to Role

```http
POST /api/notifications/send-to-role
Authorization: Bearer {token}
Content-Type: application/json

{
    "role": "Manager",
    "title": "Notifikasi untuk Manager",
    "message": "Isi pesan..."
}
```

### 17.7 Send to Template Members

```http
POST /api/notifications/send-to-template
Authorization: Bearer {token}
Content-Type: application/json

{
    "template_id": "uuid",
    "title": "Update Logbook",
    "message": "Ada update pada logbook..."
}
```

### 17.8 Send to All Users

```http
POST /api/notifications/send-all
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Pengumuman Sistem",
    "message": "Maintenance dijadwalkan..."
}
```

**Permission:** Hanya Super Admin

### 17.9 Push Notification (FCM)

Sistem menggunakan Firebase Cloud Messaging untuk push notification:

#### Save FCM Token

```http
POST /api/fcm-tokens
Authorization: Bearer {token}
Content-Type: application/json

{
    "token": "fcm_token_string",
    "device_type": "android",
    "device_name": "Samsung Galaxy"
}
```

#### Delete FCM Token

```http
DELETE /api/fcm-tokens/{token}
Authorization: Bearer {token}
```

---

## 18. AUDIT TRAIL

### 18.1 Tentang Audit Trail

Audit Trail mencatat semua aktivitas penting dalam sistem untuk keperluan:
- Keamanan
- Compliance
- Debugging
- Monitoring

### 18.2 Data yang Dicatat

| Field | Deskripsi |
|-------|-----------|
| `user_id` | ID user yang melakukan aksi |
| `action` | Tipe aksi (CREATE, UPDATE, DELETE, dll) |
| `description` | Deskripsi detail aksi |
| `ip_address` | IP address pengguna |
| `user_agent` | Browser/device information |
| `model_type` | Model yang terpengaruh |
| `model_id` | ID model yang terpengaruh |
| `details` | Data tambahan (JSON) |
| `created_at` | Timestamp |

### 18.3 Action Types

| Action | Deskripsi |
|--------|-----------|
| `LOGIN` | User login |
| `LOGOUT` | User logout |
| `CREATE_USER` | Membuat user baru |
| `UPDATE_USER` | Update user |
| `DELETE_USER` | Hapus user |
| `CREATE_TEMPLATE` | Membuat template |
| `CREATE_INSTITUTION` | Membuat institusi |
| `VERIFY_DATA` | Memverifikasi data |
| `REJECT_DATA` | Menolak data |
| `EXPORT_LOGBOOK` | Mengekspor logbook |
| `SEND_NOTIFICATION` | Mengirim notifikasi |

### 18.4 Statistics

```http
GET /api/admin/audit-trail/statistics?start_date=2026-01-01&end_date=2026-02-07
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "period": {
            "start_date": "2026-01-01",
            "end_date": "2026-02-07",
            "days": 38
        },
        "summary": {
            "total_activities": 1500,
            "unique_users": 45,
            "action_types": 12
        },
        "activity_by_type": [...],
        "daily_trend": [...],
        "top_users": [...]
    }
}
```

### 18.5 Get Logs

```http
GET /api/admin/audit-trail/logs?start_date=2026-01-01&end_date=2026-02-07
Authorization: Bearer {token}
```

**Query Parameters:**
- `start_date`, `end_date`: Date range
- `action`: Filter by action type
- `user_id`: Filter by user
- `search`: Search in description
- `per_page`: Pagination size

### 18.6 Get Action Types

```http
GET /api/admin/audit-trail/action-types
Authorization: Bearer {token}
```

### 18.7 Export Logs

```http
GET /api/admin/audit-trail/export?start_date=2026-01-01&end_date=2026-02-07&format=csv
Authorization: Bearer {token}
```

---

## 19. REPORTS & ANALYTICS

### 19.1 Dashboard Summary

```http
GET /api/admin/reports/dashboard-summary
Authorization: Bearer {token}
```

### 19.2 Logbook Reports

```http
GET /api/admin/reports/logbook?start_date=2026-01-01&end_date=2026-02-07
Authorization: Bearer {token}
```

**Data:**
- Total templates
- Total entries
- Entries per template
- Verification rate
- Top contributors

### 19.3 User Activity Reports

```http
GET /api/admin/reports/user-activity?start_date=2026-01-01&end_date=2026-02-07
Authorization: Bearer {token}
```

**Data:**
- Active users
- New registrations
- Login frequency
- Most active users

### 19.4 Institution Performance

```http
GET /api/admin/reports/institution-performance
Authorization: Bearer {token}
```

**Data:**
- Institution rankings
- Template count per institution
- User count per institution
- Activity metrics

### 19.5 Export Data

```http
GET /api/admin/reports/export?type=users&format=xlsx
Authorization: Bearer {token}
```

**Export Types:**
- `users`: Data pengguna
- `logbooks`: Data template dan entries
- `institutions`: Data institusi
- `activities`: Data aktivitas

**Export Formats:**
- `csv`
- `xlsx`
- `pdf`

### 19.6 Scheduled Reports (Coming Soon)

```http
GET /api/admin/reports/scheduled
POST /api/admin/reports/scheduled
DELETE /api/admin/reports/scheduled/{id}
PATCH /api/admin/reports/scheduled/{id}/toggle
```

---

## 20. API REFERENCE

### 20.1 Base URL

```
Production: https://api.loggenerator.com/api
Development: http://localhost:8000/api
```

### 20.2 Authentication

Semua endpoint protected menggunakan Bearer Token:

```http
Authorization: Bearer {your_sanctum_token}
```

### 20.3 Response Format

**Success Response:**
```json
{
    "success": true,
    "message": "Operation successful",
    "data": {...}
}
```

**Error Response:**
```json
{
    "success": false,
    "message": "Error description",
    "errors": {...}
}
```

### 20.4 HTTP Status Codes

| Code | Meaning |
|------|---------|
| 200 | OK - Request berhasil |
| 201 | Created - Resource berhasil dibuat |
| 400 | Bad Request - Request tidak valid |
| 401 | Unauthorized - Token tidak valid |
| 403 | Forbidden - Tidak memiliki izin |
| 404 | Not Found - Resource tidak ditemukan |
| 422 | Unprocessable Entity - Validasi gagal |
| 429 | Too Many Requests - Rate limit exceeded |
| 500 | Internal Server Error - Kesalahan server |

### 20.5 Pagination

Request dengan pagination:
```http
GET /api/users?page=1&per_page=15
```

Response pagination:
```json
{
    "data": [...],
    "pagination": {
        "current_page": 1,
        "per_page": 15,
        "total": 100,
        "last_page": 7,
        "has_more": true
    }
}
```

### 20.6 Public Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/health` | GET | Health check |
| `/api/login` | POST | User login |
| `/api/register` | POST | User registration |
| `/api/auth/google` | POST | Google OAuth login |
| `/api/website/homepage-data` | GET | Website data |

### 20.7 Complete API Routes

Untuk daftar lengkap API routes, lihat file:
- `routes/api.php`
- Dokumentasi OpenAPI/Swagger (jika tersedia)

---

## 21. TROUBLESHOOTING

### 21.1 Common Issues

#### 401 Unauthorized

| Penyebab | Solusi |
|----------|--------|
| Token expired | Login ulang untuk mendapatkan token baru |
| Token invalid | Periksa format: "Bearer {token}" |
| Missing token | Sertakan header Authorization |

#### 403 Forbidden

| Penyebab | Solusi |
|----------|--------|
| Permission denied | Periksa role dan permission user |
| Wrong logbook role | Periksa akses ke template |
| IP blocked | Hubungi administrator |

#### 422 Validation Error

| Penyebab | Solusi |
|----------|--------|
| Missing required field | Periksa semua field wajib |
| Invalid format | Sesuaikan format data (UUID, email, dll) |
| Duplicate entry | Gunakan nilai unik |

#### 429 Too Many Requests

| Penyebab | Solusi |
|----------|--------|
| Rate limit exceeded | Tunggu beberapa menit |
| Too many login attempts | Gunakan exponential backoff |

#### 500 Internal Server Error

| Penyebab | Solusi |
|----------|--------|
| Server error | Periksa log: `storage/logs/laravel.log` |
| Database error | Periksa koneksi database |
| PHP error | Periksa PHP error log |

### 21.2 Database Issues

#### Migration Failed

```bash
# Reset dan migrate ulang
php artisan migrate:fresh --seed

# Periksa status
php artisan migrate:status
```

#### Connection Error

```bash
# Periksa koneksi PostgreSQL
psql -h localhost -U username -d database

# Periksa .env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=loggenerator
DB_USERNAME=username
DB_PASSWORD=password
```

### 21.3 Cache Issues

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild cache
php artisan config:cache
php artisan route:cache
```

### 21.4 File Permission Issues

```bash
# Set proper permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 21.5 Log Monitoring

```bash
# Tail Laravel log
tail -f storage/logs/laravel.log

# Search specific error
grep "error_message" storage/logs/laravel.log
```

### 21.6 Support Contact

- **Technical Support**: tech@loggenerator.com
- **Bug Reports**: bugs@loggenerator.com
- **Security Issues**: security@loggenerator.com

---

## 22. GLOSARIUM

| Istilah | Definisi |
|---------|----------|
| **API** | Application Programming Interface - antarmuka untuk komunikasi antar sistem |
| **Bearer Token** | Jenis token autentikasi yang dikirim via header Authorization |
| **CRUD** | Create, Read, Update, Delete - operasi dasar database |
| **FCM** | Firebase Cloud Messaging - layanan push notification dari Google |
| **JWT** | JSON Web Token - format token untuk autentikasi |
| **Middleware** | Software yang memproses request sebelum mencapai controller |
| **OAuth** | Open Authorization - standar autentikasi terbuka |
| **Permission** | Izin spesifik untuk melakukan aksi tertentu |
| **RBAC** | Role-Based Access Control - kontrol akses berbasis peran |
| **REST** | Representational State Transfer - arsitektur API |
| **Role** | Peran yang menentukan sekumpulan permissions |
| **Sanctum** | Package Laravel untuk autentikasi API token |
| **Spatie Permission** | Package Laravel untuk manajemen role dan permission |
| **Template** | Struktur logbook yang mendefinisikan fields |
| **UUID** | Universally Unique Identifier - ID unik 128-bit |
| **Webhook** | Callback HTTP yang dipicu oleh event |

---

## LAMPIRAN

### A. Environment Variables

```env
# Application
APP_NAME="Log Generator"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://api.loggenerator.com

# Database
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=loggenerator
DB_USERNAME=username
DB_PASSWORD=password

# Authentication
SANCTUM_STATEFUL_DOMAINS=loggenerator.com
SESSION_DOMAIN=.loggenerator.com

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=username
MAIL_PASSWORD=password
MAIL_FROM_ADDRESS=noreply@loggenerator.com

# Firebase
FIREBASE_CREDENTIALS=path/to/credentials.json
FIREBASE_PROJECT_ID=project-id
```

### B. Artisan Commands

```bash
# Database
php artisan migrate              # Run migrations
php artisan db:seed              # Seed database
php artisan migrate:fresh --seed # Reset and seed

# Cache
php artisan cache:clear
php artisan config:cache
php artisan route:cache

# Permissions
php artisan permission:cache-reset
php artisan db:seed --class=PermissionSeeder

# Queue
php artisan queue:work
php artisan queue:restart

# Maintenance
php artisan down
php artisan up
```

### C. Useful SQL Queries

```sql
-- Count users by role
SELECT r.name, COUNT(u.id) 
FROM users u 
JOIN model_has_roles mhr ON u.id::text = mhr.model_id 
JOIN roles r ON mhr.role_id = r.id 
GROUP BY r.name;

-- Logbook entries per template
SELECT lt.name, COUNT(ld.id) as entries 
FROM logbook_template lt 
LEFT JOIN logbook_datas ld ON lt.id = ld.template_id 
GROUP BY lt.name 
ORDER BY entries DESC;

-- Verification summary
SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN is_verified = true THEN 1 ELSE 0 END) as verified,
    SUM(CASE WHEN is_verified = false THEN 1 ELSE 0 END) as rejected
FROM logbook_datas;
```

---

**AKHIR DOKUMEN**

*Manual Book ini merupakan dokumen resmi yang dilindungi hak cipta. Dilarang memperbanyak atau mendistribusikan tanpa izin tertulis dari pemilik hak cipta.*

© 2026 Universitas Negeri Padang & Universidade Nacional Timor Lorosa'e
