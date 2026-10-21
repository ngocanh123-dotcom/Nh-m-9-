const form = document.getElementById("loginForm");
const usernameInput = document.getElementById("username");
const passwordInput = document.getElementById("password");
const rememberInput = document.getElementById("remember");
const message = document.getElementById("message");
const togglePassword = document.getElementById("togglePassword");
const usernameError = document.getElementById("usernameError");
const passwordError = document.getElementById("passwordError");

// Danh sách tài khoản demo (Hỗ trợ 3 tài khoản Admin + Giảng viên + Sinh viên)
const taiKhoanDemo = {
  sv001:  { password: "Sv@123",    role: "sinhvien",  tenVaiTro: "Sinh viên" },
  gv001:  { password: "Gv@123",    role: "giangvien", tenVaiTro: "Giảng viên" },
  admin:  { password: "Admin@123", role: "quantri",   tenVaiTro: "Quản trị viên" },
  admin1: { password: "Admin@123", role: "quantri",   tenVaiTro: "Quản trị viên 1" },
  admin2: { password: "Admin@123", role: "quantri",   tenVaiTro: "Quản trị viên 2" },
  admin3: { password: "Admin@123", role: "quantri",   tenVaiTro: "Quản trị viên 3" }
};

const tenVaiTroMap = {
  sinhvien: "Sinh viên",
  giangvien: "Giảng viên",
  quantri: "Quản trị viên"
};

// Icon SVG con mắt mở và đóng
const eyeOpenSVG = `
  <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/>
    <circle cx="12" cy="12" r="3"/>
  </svg>`;

const eyeClosedSVG = `
  <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
    <line x1="1" y1="1" x2="23" y2="23"/>
  </svg>`;

// Ghi nhớ tên đăng nhập nếu người dùng đã chọn trước đó
try {
  const savedUsername = localStorage.getItem("rememberedUsername");
  if (savedUsername && rememberInput) {
    usernameInput.value = savedUsername;
    rememberInput.checked = true;
  }
} catch (error) {
  // Bỏ qua nếu localStorage bị chặn
}

// Hiển thị thông báo tổng
function showMessage(text, type) {
  message.textContent = text;
  message.className = "message " + type;
}

// Hiển thị lỗi dưới từng trường
function setFieldError(input, errorEl, text) {
  input.classList.add("invalid");
  if (errorEl) {
    errorEl.textContent = text;
  }
}

// Xóa thông báo và trạng thái lỗi
function clearErrors() {
  usernameInput.classList.remove("invalid");
  passwordInput.classList.remove("invalid");
  if (usernameError) usernameError.textContent = "";
  if (passwordError) passwordError.textContent = "";
  message.className = "message";
  message.textContent = "";
}

// Hiện hoặc ẩn mật khẩu
togglePassword.addEventListener("click", () => {
  const shouldShow = passwordInput.type === "password";
  passwordInput.type = shouldShow ? "text" : "password";
  togglePassword.innerHTML = shouldShow ? eyeClosedSVG : eyeOpenSVG;
  togglePassword.title = shouldShow ? "Ẩn mật khẩu" : "Hiện mật khẩu";
});

// Xóa trạng thái lỗi khi người dùng nhập lại
usernameInput.addEventListener("input", () => {
  usernameInput.classList.remove("invalid");
  if (usernameError) usernameError.textContent = "";
  if (message.classList.contains("error") || message.classList.contains("success")) {
    message.className = "message";
    message.textContent = "";
  }
});

passwordInput.addEventListener("input", () => {
  passwordInput.classList.remove("invalid");
  if (passwordError) passwordError.textContent = "";
  if (message.classList.contains("error") || message.classList.contains("success")) {
    message.className = "message";
    message.textContent = "";
  }
});

// Xử lý Validation đăng nhập (Nhiệm vụ Thành viên 2)
form.addEventListener("submit", event => {
  event.preventDefault();
  clearErrors();

  const username = usernameInput.value.trim();
  const password = passwordInput.value;

  // 1. Kiểm tra vai trò đang được chọn
  const selectedRole = form.querySelector('input[name="role"]:checked');
  if (!selectedRole || !tenVaiTroMap[selectedRole.value]) {
    showMessage("Vui lòng chọn vai trò đăng nhập.", "error");
    return;
  }
  const role = selectedRole.value;

  // 2. Kiểm tra trường bắt buộc: Tên đăng nhập
  const usernameRegex = /^[a-zA-Z0-9_]{3,30}$/;
  if (!username) {
    setFieldError(usernameInput, usernameError, "Vui lòng nhập tên đăng nhập.");
    showMessage("Vui lòng nhập tên đăng nhập.", "error");
    usernameInput.focus();
    return;
  }
  if (!usernameRegex.test(username)) {
    setFieldError(
      usernameInput,
      usernameError,
      "Tên đăng nhập gồm 3-30 ký tự, không chứa khoảng trắng hoặc ký tự đặc biệt."
    );
    showMessage("Định dạng tên đăng nhập không hợp lệ.", "error");
    usernameInput.focus();
    return;
  }

  // 3. Kiểm tra trường bắt buộc: Mật khẩu
  if (!password.trim()) {
    setFieldError(passwordInput, passwordError, "Vui lòng nhập mật khẩu.");
    showMessage("Vui lòng nhập mật khẩu.", "error");
    passwordInput.focus();
    return;
  }
  if (password.length < 6) {
    setFieldError(passwordInput, passwordError, "Mật khẩu phải có ít nhất 6 ký tự.");
    showMessage("Mật khẩu phải có ít nhất 6 ký tự.", "error");
    passwordInput.focus();
    return;
  }

  // 4. Kiểm tra tài khoản, mật khẩu và đối chiếu vai trò
  const account = taiKhoanDemo[username];

  if (!account) {
    setFieldError(usernameInput, usernameError, "Tài khoản không tồn tại trong hệ thống.");
    showMessage("Tài khoản không tồn tại trong hệ thống!", "error");
    usernameInput.focus();
    return;
  }

  if (account.password !== password) {
    setFieldError(passwordInput, passwordError, "Mật khẩu không chính xác.");
    showMessage("Mật khẩu không chính xác!", "error");
    passwordInput.focus();
    return;
  }

  if (account.role !== role) {
    showMessage(
      `Sai vai trò! Tài khoản "${username}" không thuộc vai trò ${tenVaiTroMap[role]}.`,
      "error"
    );
    return;
  }

  // 5. Ghi nhớ tên đăng nhập khi người dùng chọn
  try {
    if (rememberInput && rememberInput.checked) {
      localStorage.setItem("rememberedUsername", username);
    } else {
      localStorage.removeItem("rememberedUsername");
    }
  } catch (error) {
    // Không làm hỏng form nếu storage bị chặn
  }

  // 6. Đăng nhập thành công
  showMessage(
    "Đăng nhập thành công! Xin chào " + account.tenVaiTro + ".",
    "success"
  );
});

// Xử lý quên mật khẩu
document.getElementById("forgotLink").addEventListener("click", event => {
  event.preventDefault();
  showMessage(
    "Vui lòng liên hệ quản trị viên để được hỗ trợ cấp lại mật khẩu.",
    "error"
  );
});