<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - SIMRS</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card-login { width: 100%; max-width: 400px; border-radius: 15px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .card-header { background: #0d6efd; color: white; border-radius: 15px 15px 0 0 !important; padding: 20px; text-align: center; }
        .btn-login { width: 100%; border-radius: 50px; padding: 10px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="card card-login">
        <div class="card-header">
            <h4 class="mb-0">LOGIN STAFF</h4>
            <small>Silakan masuk untuk mengelola antrian</small>
        </div>
        <div class="card-body p-4">
            
            <?php 
            if(isset($_GET['pesan'])){
                if($_GET['pesan'] == "gagal"){
                    echo "<div class='alert alert-danger text-center'>Login Gagal! Username atau Password salah.</div>";
                } else if($_GET['pesan'] == "belum_login"){
                    echo "<div class='alert alert-warning text-center'>Anda harus login dulu!</div>";
                } else if($_GET['pesan'] == "logout"){
                    echo "<div class='alert alert-success text-center'>Anda berhasil logout.</div>";
                }
            }
            ?>

            <form action="cek_login.php" method="post">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan Username" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-login mt-3">MASUK</button>
                <div class="text-center mt-3">
                    <a href="index.html" class="text-decoration-none small">Kembali ke Beranda</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>