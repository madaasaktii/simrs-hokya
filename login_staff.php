<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Staff - SIMRS HOKYA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #f0f2f5 0%, #c2e9fb 100%); 
            height: 100vh; display: flex; align-items: center; justify-content: center; 
            font-family: 'Poppins', sans-serif;
        }
        .card-login { width: 100%; max-width: 420px; border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .card-header { background: #1977cc; color: white; padding: 22px; text-align: center; border: none; border-radius: 20px 20px 0 0 !important; }
        .btn-login { width: 100%; border-radius: 50px; padding: 12px; font-weight: 600; background-color: #1977cc; border: none; }
        .form-control, .form-select { border-radius: 10px; padding: 10px; }
        .small-note { font-size:0.9rem;color:#555 }
    </style>
</head>
<body>

    <div class="card card-login">
        <div class="card-header">
            <h4 class="mb-1 fw-bold"><i class="bi bi-lock-fill"></i> LOGIN STAFF</h4>
            <p class="mb-0 small opacity-75">Sistem Informasi Manajemen RS </p>
        </div>
        <div class="card-body p-4">
            <?php 
            if(isset($_GET['pesan'])){
                if($_GET['pesan'] == "gagal"){
                    echo "<div class='alert alert-danger text-center small py-2'>Login Gagal! Akun tidak ditemukan.</div>";
                } else if($_GET['pesan'] == "logout"){
                    echo "<div class='alert alert-success text-center small py-2'>Anda telah logout.</div>";
                }
            }
            ?>

            <form action="cek_login.php" method="post">
                <div class="mb-3">
                    <label class="form-label">Masuk Sebagai</label>
                    <select name="role" class="form-select" required>
                        <option value="" selected disabled>-- Pilih Hak Akses --</option>
                        <option value="admin">Admin / Staff Antrian</option>
                        <option value="dokter">Dokter Spesialis</option>
                        <option value="perawat">Perawat / Poli</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan Username" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-login mt-2">MASUK</button>
                
                <div class="text-center mt-4">
                    <a href="admin_dashboard.php" class="text-decoration-none small text-secondary">
                        <i class="bi bi-arrow-right"></i> Lihat halaman demo (Admin Dashboard)
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>