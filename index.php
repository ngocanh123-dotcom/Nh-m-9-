<?php
session_start();

// Tai khoan demo cho 3 vai tro
$taiKhoanDemo = [
    'sinhvien' => [
        'username' => 'sv001',
        'password' => 'Sv@123',
        'tenVaiTro' => 'Sinh viên'
    ],
    'giangvien' => [
        'username' => 'gv001',
        'password' => 'Gv@123',
        'tenVaiTro' => 'Giảng viên'
    ],
    'quantri' => [
        'username' => 'admin',
        'password' => 'Admin@123',
        'tenVaiTro' => 'Quản trị viên'
    ]
];

$thongBao = '';
$loaiThongBao = '';
$username = '';
$role = 'sinhvien';

// Xu ly khi nhan nut dang nhap
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    if (!isset($taiKhoanDemo[$role])) {
        $thongBao = 'Vui lòng chọn vai trò hợp lệ.';
        $loaiThongBao = 'error';
    } elseif ($username === '') {
        $thongBao = 'Vui lòng nhập tên đăng nhập.';
        $loaiThongBao = 'error';
    } elseif (trim($password) === '') {
        $thongBao = 'Vui lòng nhập mật khẩu.';
        $loaiThongBao = 'error';
    } elseif (
        $username === $taiKhoanDemo[$role]['username'] &&
        $password === $taiKhoanDemo[$role]['password']
    ) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        $_SESSION['tenVaiTro'] = $taiKhoanDemo[$role]['tenVaiTro'];

        $thongBao = 'Đăng nhập thành công! Xin chào ' .
                    $taiKhoanDemo[$role]['tenVaiTro'] . '.';
        $loaiThongBao = 'success';
    } else {
        $thongBao = 'Tài khoản, mật khẩu hoặc vai trò không chính xác.';
        $loaiThongBao = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Quản lý học phần</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .brand-icon {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 18px;
        }

        .message {
            margin: 16px 0;
            padding: 14px 18px;
            border-radius: 12px;
        }

        .message:empty {
            display: none;
        }

        .message.error {
            color: #c62828;
            background: #fff1f0;
        }

        .message.success {
            color: #187443;
            background: #eaf8ef;
        }
    </style>
</head>
<body>
<main class="login-card">

    <div class="brand-icon" aria-hidden="true">
        <svg viewBox="0 0 64 64" width="52" height="52"
             fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="4" y="4" width="56" height="56" rx="16"
                  fill="#155EEF"/>
            <path d="M13 25L32 15L51 25L32 35L13 25Z"
                  fill="white"/>
            <path d="M20 29V39C27 44 37 44 44 39V29"
stroke="white" stroke-width="3"
                  stroke-linecap="round"/>
            <path d="M51 25V35" stroke="#70E1CF"
                  stroke-width="3" stroke-linecap="round"/>
            <circle cx="51" cy="38" r="3" fill="#70E1CF"/>
        </svg>
    </div>

    <h1>ĐĂNG NHẬP HỆ THỐNG</h1>
    <p class="subtitle">
        Hệ thống quản lý học phần và đăng ký học phần
    </p>

    <form method="POST" action="index.php" id="loginForm">
        <span class="section-label">Chọn vai trò đăng nhập</span>

        <div class="roles">
            <div class="role-option">
                <input type="radio" name="role" id="student"
                       value="sinhvien"
                       <?= $role === 'sinhvien' ? 'checked' : '' ?>>
                <label for="student">
                    <span class="role-icon">🎓</span>
                    <span>Sinh viên</span>
                </label>
            </div>

            <div class="role-option">
                <input type="radio" name="role" id="lecturer"
                       value="giangvien"
                       <?= $role === 'giangvien' ? 'checked' : '' ?>>
                <label for="lecturer">
                    <span class="role-icon">👩‍🏫</span>
                    <span>Giảng viên</span>
                </label>
            </div>

            <div class="role-option">
                <input type="radio" name="role" id="admin"
                       value="quantri"
                       <?= $role === 'quantri' ? 'checked' : '' ?>>
                <label for="admin">
                    <span class="role-icon">🛡️</span>
                    <span>Quản trị viên</span>
                </label>
            </div>
        </div>

        <div class="field">
            <label class="field-label" for="username">
                Tên đăng nhập
            </label>
            <div class="input-wrap">
                <input id="username" name="username" type="text"
                       placeholder="Nhập tên đăng nhập"
                       autocomplete="username" maxlength="60"
                       value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>">
            </div>
        </div>

        <div class="field">
            <label class="field-label" for="password">Mật khẩu</label>
            <div class="input-wrap">
                <input id="password" name="password" type="password"
                       placeholder="Nhập mật khẩu"
                       autocomplete="current-password" maxlength="128">
                <button class="toggle-password" type="button"
                        id="togglePassword">👁</button>
            </div>
        </div>

        <div class="options">
            <span></span>
            <a href="#" id="forgotLink">Quên mật khẩu?</a>
        </div>

        <div id="message"
class="message <?= htmlspecialchars($loaiThongBao, ENT_QUOTES, 'UTF-8') ?>"
             role="status" aria-live="polite"><?= htmlspecialchars($thongBao, ENT_QUOTES, 'UTF-8') ?></div>

        <button class="submit-btn" type="submit">ĐĂNG NHẬP</button>
    </form>

    <p class="footer">Tài khoản được cấp bởi quản trị viên</p>
</main>

<script>
document.getElementById('togglePassword').addEventListener('click', function () {
    const input = document.getElementById('password');
    const hien = input.type === 'password';
    input.type = hien ? 'text' : 'password';
    this.textContent = hien ? '🙈' : '👁';
});

document.getElementById('forgotLink').addEventListener('click', function (e) {
    e.preventDefault();
    alert('Vui lòng liên hệ quản trị viên để được hỗ trợ cấp lại mật khẩu.');
});
</script>
</body>
</html>