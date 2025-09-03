# 🧺 Laundry Management System

Aplikasi manajemen laundry berbasis web yang dibangun dengan **Laravel 12** untuk membantu usaha laundry dalam mengelola order, pelanggan, transaksi, dan laporan keuangan secara efisien.

<p align="center">
<a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a>
</p>

## ✨ Fitur Utama

### 👥 Role-based Authentication
- **Admin**: Akses penuh ke seluruh sistem dan laporan
- **Kasir**: Management order dan transaksi harian

### 🧾 Management Order
- Input order baru dengan multiple items
- Tracking status order (Baru, Dicuci, Setrika, Selesai, Diambil)
- Generate invoice otomatis
- Cetak struk untuk pelanggan

### 👥 Management Pelanggan
- Database pelanggan dengan riwayat order
- Fitur piutang dan pembayaran
- Notifikasi order selesai

### 💰 Management Keuangan
- Laporan penjualan harian, mingguan, bulanan
- Laporan pengeluaran operasional
- Laba/rugi usaha
- Management piutang pelanggan

### 📊 Dashboard Analytics
- Statistik pendapatan dan order
- Grafik performa bisnis
- Quick overview aktivitas terkini

## 🛠️ Tech Stack

- **Framework**: Laravel 12
- **Frontend**: Bootstrap 5, JavaScript
- **Database**: MySQL
- **PDF Generation**: DomPDF
- **Authentication**: Laravel Sanctum
- **Deployment**: Ready for Render/Vercel

## 📦 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL Database

### Setup Steps

1. **Clone Repository**
   ```bash
   git clone https://github.com/mchervan/laundry-app.git
   cd laundry-app
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   npm run build
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   
   Configure database settings in `.env`:
   ```env
   DB_DATABASE=laundry_db
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

4. **Database Migration**
   ```bash
   php artisan migrate --seed
   ```

5. **Start Development Server**
   ```bash
   php artisan serve
   npm run dev
   ```

## 👤 Default Login

### Admin Account
- **Email**: admin@laundry.com
- **Password**: admin123

### Kasir Account  
- **Email**: kasir.pemuda@laundry.com
- **Password**: kasir123


## 🚀 Deployment

### Deploy to Render.com
1. Connect GitHub repository to Render
2. Set build command: `composer install && npm install && npm run build`
3. Set start command: `php artisan serve --host=0.0.0.0 --port=$PORT`
4. Add environment variables in Render dashboard

### Environment Variables
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.render.com
DB_HOST=your-database-host
DB_DATABASE=your-database-name
DB_USERNAME=your-database-user
DB_PASSWORD=your-database-password
```

## 🤝 Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 📞 Contact

- **Developer**: Mochamad Ervan
- **Email**: [mchervan@vancraftstudio.onmicrosoft.com](mailto:mchervan@vancraftstudio.onmicrosoft.com)
- **GitHub**: [mchervan](https://github.com/mchervan)
- **LinkedIn**: [Mochamad Ervan](https://www.linkedin.com/in/mochamad-ervan-248172226)

---

⭐ Star this repository if you find it helpful!
