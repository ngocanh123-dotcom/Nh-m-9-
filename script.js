const form = document.getElementById("loginForm");
const usernameInput = document.getElementById("username");
const passwordInput = document.getElementById("password");
const rememberInput = document.getElementById("remember");
const message = document.getElementById("message");
const togglePassword = document.getElementById("togglePassword");

// Tài khoản demo cho 3 vai trò
// Lưu ý: chỉ dùng để kiểm thử giao diện, chưa kết nối SQL.
const taiKhoanDemo = {
  sinhvien: {
    username: "sv001",
    password: "Sv@123"
  },
  giangvien: {
    username: "gv001",
    password: "Gv@123"
  },
  quantri: {
    username: "admin",
    password: "Admin@123"
  }
};

// Ghi nhớ tên đăng nhập nếu người dùng chủ động chọn.
// Không lưu mật khẩu trong localStorage.
try {
  const savedUsername = localStorage.getItem("rememberedUsername");

  if (savedUsername) {
    usernameInput.value = savedUsername;
    rememberInput.checked = true;
  }
} catch (error) {
  // Form vẫn hoạt động nếu trình duyệt chặn localStorage.
}

// Hiển thị thông báo
function showMessage(text, type) {
  message.textContent = text;
  message.className = "message " + type;
}

// Xóa thông báo và trạng thái lỗi
function clearErrors() {
  usernameInput.classList.remove("invalid");
  passwordInput.classList.remove("invalid");
  message.className = "message";
  message.textContent = "";
}

// Hiện hoặc ẩn mật khẩu
togglePassword.addEventListener("click", () => {
  const shouldShow = passwordInput.type === "password";

  passwordInput.type = shouldShow ? "text" : "password";
  togglePassword.textContent = shouldShow ? "🙈" : "👁";
  togglePassword.title = shouldShow
    ? "Ẩn mật khẩu"
    : "Hiện mật khẩu";
});

// Xóa trạng thái lỗi khi người dùng nhập lại
[usernameInput, passwordInput].forEach(input => {
  input.addEventListener("input", () => {
    input.classList.remove("invalid");

    if (
      message.classList.contains("error") ||
      message.classList.contains("success")
    ) {
      message.className = "message";
      message.textContent = "";
    }
  });
});

// Xử lý đăng nhập
form.addEventListener("submit", event => {
  event.preventDefault();
  clearErrors();

  const username = usernameInput.value.trim();
  const password = passwordInput.value;

  // Lấy vai trò đang được chọn
  const selectedRole = form.querySelector(
    'input[name="role"]:checked'
  );

  if (!selectedRole) {
    showMessage(
      "Vui lòng chọn vai trò đăng nhập.",
      "error"
    );
    return;
  }

  const role = selectedRole.value;

  // Kiểm tra tài khoản
  if (!username) {
    usernameInput.classList.add("invalid");

    showMessage(
      "Vui lòng nhập tên đăng nhập.",
      "error"
    );

    usernameInput.focus();
    return;
  }

  // Kiểm tra mật khẩu
  if (!password.trim()) {
    passwordInput.classList.add("invalid");

    showMessage(
      "Vui lòng nhập mật khẩu.",
      "error"
    );

    passwordInput.focus();
    return;
  }

  // Kiểm tra vai trò có nằm trong danh sách demo không
  const account = taiKhoanDemo[role];

  if (!account) {
    showMessage(
      "Vai trò không hợp lệ. Vui lòng chọn lại.",
      "error"
    );
    return;
  }

  // Kiểm tra tài khoản và mật khẩu theo vai trò
  if (
    username !== account.username ||
    password !== account.password
  ) {
    showMessage(
      "Tài khoản, mật khẩu hoặc vai trò không chính xác!",
      "error"
    );
    return;
  }

  // Chỉ ghi nhớ tên đăng nhập khi người dùng chọn
  try {
    if (rememberInput.checked) {
      localStorage.setItem(
        "rememberedUsername",
        username
      );
    } else {
      localStorage.removeItem("rememberedUsername");
    }
  } catch (error) {
    // Không làm hỏng form nếu storage bị chặn.
  }

  // Đăng nhập demo thành công
  const tenVaiTro = {
    sinhvien: "Sinh viên",
    giangvien: "Giảng viên",
    quantri: "Quản trị viên"
  };

  showMessage(
    "Đăng nhập thành công! Xin chào " +
      tenVaiTro[role] +
      ".",
    "success"
  );
});

// Xử lý quên mật khẩu
document.getElementById("forgotLink").addEventListener(
  "click",
  event => {
    event.preventDefault();

    showMessage(
      "Vui lòng liên hệ quản trị viên để được hỗ trợ cấp lại mật khẩu.",
      "error"
    );
  }
);
