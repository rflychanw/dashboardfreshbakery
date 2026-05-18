<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FreshBakery</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: var(--bg-color);
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: var(--shadow);
        }
        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }
        .login-header i {
            font-size: 40px;
            color: var(--accent-color);
            margin-bottom: 16px;
        }
        .login-header h1 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
        }
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--bg-color);
            color: var(--text-primary);
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            border-color: var(--accent-color);
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: var(--accent-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-login:hover {
            opacity: 0.9;
        }
        .alert {
            padding: 12px;
            border-radius: 8px;
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            font-size: 14px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="fa-solid fa-cookie-bite"></i>
            <h1>FreshBakery</h1>
            <p style="color: var(--text-secondary); font-size: 14px; margin-top: 8px;">Silakan login untuk melanjutkan</p>
        </div>

        <?php if(session()->getFlashdata('msg')): ?>
            <div class="alert"><?= session()->getFlashdata('msg') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('login/auth') ?>" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>
        
        <div style="margin-top: 24px; text-align: center; font-size: 12px; color: var(--text-secondary);">
            <p>Hint: admin / admin123</p>
        </div>
    </div>
</body>
</html>
