<?php
session_start();

// Tài khoản demo cho các vai trò (Bao gồm 3 Admin + Giảng viên + Sinh viên)
$taiKhoanDemo = [
    'sv001' => [
        'password' => 'Sv@123',
        'role' => 'sinhvien',
        'tenVaiTro' => 'Sinh viên'
    ],
    'gv001' => [
        'password' => 'Gv@123',
        'role' => 'giangvien',
        'tenVaiTro' => 'Giảng viên'
    ],
    'admin' => [
        'password' => 'Admin@123',
        'role' => 'quantri',
        'tenVaiTro' => 'Quản trị viên'
    ],
    'admin1' => [
        'password' => 'Admin@123',
        'role' => 'quantri',
        'tenVaiTro' => 'Quản trị viên 1'
    ],
    'admin2' => [
        'password' => 'Admin@123',
        'role' => 'quantri',
        'tenVaiTro' => 'Quản trị viên 2'
    ],
    'admin3' => [
        'password' => 'Admin@123',
        'role' => 'quantri',
        'tenVaiTro' => 'Quản trị viên 3'
    ]
];

$tenVaiTroMap = [
    'sinhvien' => 'Sinh viên',
    'giangvien' => 'Giảng viên',
    'quantri' => 'Quản trị viên'
];

$thongBao = '';
$loaiThongBao = '';
$loiUsername = '';
$loiPassword = '';
$username = '';
$role = 'sinhvien';

// Xử lý Validation phía Server (PHP) khi submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    if (!isset($tenVaiTroMap[$role])) {
        $thongBao = 'Vui lòng chọn vai trò đăng nhập hợp lệ.';
        $loaiThongBao = 'error';
    } elseif ($username === '') {
        $loiUsername = 'Vui lòng nhập tên đăng nhập.';
        $thongBao = 'Vui lòng nhập tên đăng nhập.';
        $loaiThongBao = 'error';
    } elseif (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) {
        $loiUsername = 'Tên đăng nhập gồm 3-30 ký tự, không chứa ký tự đặc biệt.';
        $thongBao = 'Tên đăng nhập không đúng định dạng.';
        $loaiThongBao = 'error';
    } elseif (trim($password) === '') {
        $loiPassword = 'Vui lòng nhập mật khẩu.';
        $thongBao = 'Vui lòng nhập mật khẩu.';
        $loaiThongBao = 'error';
    } elseif (strlen($password) < 6) {
        $loiPassword = 'Mật khẩu phải có ít nhất 6 ký tự.';
        $thongBao = 'Mật khẩu phải có ít nhất 6 ký tự.';
        $loaiThongBao = 'error';
    } elseif (!isset($taiKhoanDemo[$username])) {
        $loiUsername = 'Tài khoản không tồn tại trong hệ thống.';
        $thongBao = 'Tài khoản không tồn tại trong hệ thống.';
        $loaiThongBao = 'error';
    } elseif ($taiKhoanDemo[$username]['password'] !== $password) {
        $loiPassword = 'Mật khẩu không chính xác.';
        $thongBao = 'Mật khẩu không chính xác.';
        $loaiThongBao = 'error';
    } elseif ($taiKhoanDemo[$username]['role'] !== $role) {
        $thongBao = 'Sai vai trò! Tài khoản "' . htmlspecialchars($username) . '" không thuộc vai trò ' . $tenVaiTroMap[$role] . '.';
        $loaiThongBao = 'error';
    } else {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        $_SESSION['tenVaiTro'] = $taiKhoanDemo[$username]['tenVaiTro'];

        $thongBao = 'Đăng nhập thành công! Xin chào ' . $taiKhoanDemo[$username]['tenVaiTro'] . '.';
        $loaiThongBao = 'success';
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
</head>
<body>
<main class="login-card">
    <div class="login-container">

        <!-- Logo mũ cử nhân trên cùng giống hệt ảnh mẫu -->
        <div class="brand-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="#155eef" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 10L12 5 2 10l10 5 10-5z"/>
                <path d="M6 12v5c3 2.5 9 2.5 12 0v-5"/>
                <path d="M22 10v6"/>
            </svg>
        </div>

        <h1>ĐĂNG NHẬP HỆ THỐNG</h1>
        <p class="subtitle">
            Hệ thống quản lý học phần và đăng ký học phần
        </p>

        <form method="POST" action="index.php" id="loginForm" novalidate>
            <span class="section-label">Chọn vai trò đăng nhập</span>

            <div class="roles">
                <!-- Vai trò: Sinh viên -->
                <div class="role-option">
                    <input type="radio" name="role" id="student"
                           value="sinhvien"
                           <?= $role === 'sinhvien' ? 'checked' : '' ?>>
                    <label for="student">
                        <span class="role-icon">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 10L12 5 2 10l10 5 10-5z"/>
                                <path d="M6 12v5c3 2.5 9 2.5 12 0v-5"/>
                            </svg>
                        </span>
                        <span>Sinh viên</span>
                    </label>
                </div>

                <!-- Vai trò: Giảng viên -->
                <div class="role-option">
                    <input type="radio" name="role" id="lecturer"
                           value="giangvien"
                           <?= $role === 'giangvien' ? 'checked' : '' ?>>
                    <label for="lecturer">
                        <span class="role-icon">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="12" rx="2"/>
                                <path d="M12 16v4"/>
                                <path d="M8 20h8"/>
                            </svg>
                        </span>
                        <span>Giảng viên</span>
                    </label>
                </div>

                <!-- Vai trò: Quản trị viên -->
                <div class="role-option">
                    <input type="radio" name="role" id="admin"
                           value="quantri"
                           <?= $role === 'quantri' ? 'checked' : '' ?>>
                    <label for="admin">
                        <span class="role-icon">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                <path d="M9 12l2 2 4-4"/>
                            </svg>
                        </span>
                        <span>Quản trị viên</span>
                    </label>
                </div>
            </div>

            <!-- Trường Tên đăng nhập -->
            <div class="field">
                <label class="field-label" for="username">
                    Tên đăng nhập
                </label>
                <div class="input-wrap">
                    <span class="input-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </span>
                    <input id="username" name="username" type="text"
                           class="<?= $loiUsername !== '' ? 'invalid' : '' ?>"
                           placeholder="Nhập tên đăng nhập"
                           autocomplete="username" maxlength="60"
                           value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <small id="usernameError" class="field-error"><?= htmlspecialchars($loiUsername, ENT_QUOTES, 'UTF-8') ?></small>
            </div>

            <!-- Trường Mật khẩu -->
            <div class="field">
                <label class="field-label" for="password">Mật khẩu</label>
                <div class="input-wrap">
                    <span class="input-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="11" width="16" height="10" rx="2"/>
                            <path d="M8 11V7a4 4 0 0 1 8 0v4"/>
                            <circle cx="12" cy="16" r="1"/>
                        </svg>
                    </span>
                    <input id="password" name="password" type="password"
                           class="<?= $loiPassword !== '' ? 'invalid' : '' ?>"
                           placeholder="Nhập mật khẩu"
                           autocomplete="current-password" maxlength="128">
                    <button class="toggle-password" type="button"
                            id="togglePassword" title="Hiện mật khẩu" aria-label="Hiện mật khẩu">
                        <svg id="eyeOpen" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                <small id="passwordError" class="field-error"><?= htmlspecialchars($loiPassword, ENT_QUOTES, 'UTF-8') ?></small>
            </div>

            <!-- Tùy chọn Ghi nhớ & Quên mật khẩu -->
            <div class="options">
                <label class="remember" for="remember">
                    <input type="checkbox" id="remember" name="remember">
                    <span>Ghi nhớ tên đăng nhập</span>
                </label>
                <a href="#" id="forgotLink">Quên mật khẩu?</a>
            </div>

            <!-- Thông báo trạng thái -->
            <div id="message"
                 class="message <?= htmlspecialchars($loaiThongBao, ENT_QUOTES, 'UTF-8') ?>"
                 role="status" aria-live="polite"><?= htmlspecialchars($thongBao, ENT_QUOTES, 'UTF-8') ?></div>

            <button class="submit-btn" type="submit">ĐĂNG NHẬP</button>
        </form>

        <p class="footer">Tài khoản được cấp bởi quản trị viên</p>

    </div>
</main>

<script src="script.js"></script>
</body>
</html>