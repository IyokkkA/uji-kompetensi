# NutriDiet — Backend Laravel + Admin Dashboard

Backend untuk aplikasi Android **NutriDiet** (lihat `Frontend(Android)/Nutridiet`).
Menyimpan semua data user dari frontend + dashboard admin (Blade + Tailwind + Chart.js).
Database default **SQLite**, isi awal **data dummy** via seeder.

## Cara jalan (Windows)

```powershell
cd "D:\uji kompetensi\backend(laravel)\nutridiet-admin"
php artisan serve --host=127.0.0.1 --port=8000
```

- Admin dashboard: http://127.0.0.1:8000/admin
  - Email: `admin@nutridiet.id` / Password: `admin123`
- User demo (password semua): `password123`

> HP fisik / emulator Android: ganti `BASE_URL` di `AuthConfig.kt` ke IP LAN kamu,
> misal `http://192.168.1.5:8000`. Emulator tetap `http://10.0.2.2:8000`.

## API untuk Android

| Method | Endpoint | Body |
|---|---|---|
| POST | `/api/register` | name, email, password, password_confirmation, goal (`weight_loss`/`maintenance`/`healthy_bulk`), phone (opsional) |
| POST | `/api/login` | email **atau** phone + password |
| GET | `/api/me` | header `Authorization: Bearer <token>` |
| GET/POST | `/api/nutrition` | food_name, calories, protein_g, carbs_g, fat_g, water_ml, meal_type, log_date |
| GET/POST | `/api/activities` | activity_type, duration_min, calories_burned, steps, log_date |

## Halaman admin

| URL | Isi |
|---|---|
| `/admin` | Statistik, grafik kalori 7 hari, donat goal, user/nutrisi/aktivitas terbaru |
| `/admin/users` | Cari + filter goal/status, tambah/edit/hapus, aktif-nonaktif |
| `/admin/users/{id}` | Detail user + riwayat nutrisi & aktivitas + total |
| `/admin/nutrition` | Semua log makanan/minuman |
| `/admin/activities` | Semua log olahraga |

## Reset data dummy

```powershell
php artisan migrate:fresh --seed
```
