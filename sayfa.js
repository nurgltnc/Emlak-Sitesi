// Modal açma ve kapama işlevi
const loginBtn = document.getElementById('loginBtn');
const registerBtn = document.getElementById('registerBtn');
const loginModal = document.getElementById('loginModal');
const registerModal = document.getElementById('registerModal');
const toRegister = document.getElementById('toRegister');
const toLogin = document.getElementById('toLogin');
const closeBtns = document.querySelectorAll('.close-btn');

// Giriş yap butonuna tıklanınca login modalını göster
loginBtn.onclick = function() {
    loginModal.style.display = 'block';
}

// Kayıt ol butonuna tıklanınca register modalını göster
registerBtn.onclick = function() {
    registerModal.style.display = 'block';
}

// Kayıt ol sayfasına gitmek için tıklanacak link
toRegister.onclick = function() {
    registerModal.style.display = 'block';
    loginModal.style.display = 'none';
}

// Giriş yap sayfasına gitmek için tıklanacak link
toLogin.onclick = function() {
    loginModal.style.display = 'block';
    registerModal.style.display = 'none';
}

// Modal kapama butonları
closeBtns.forEach(btn => {
    btn.onclick = function() {
        loginModal.style.display = 'none';
        registerModal.style.display = 'none';
    }
});

// Eğer modal dışında bir yere tıklanırsa modali kapat
window.onclick = function(event) {
    if (event.target == loginModal || event.target == registerModal) {
        loginModal.style.display = 'none';
        registerModal.style.display = 'none';
    }
}

// Kayıt formunun AJAX ile gönderilmesi
document.getElementById('registerForm').onsubmit = function(event) {
    event.preventDefault();  // Sayfa yenilenmesin

    const formData = new FormData(this);
    fetch('kayit.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Kayıt başarılı!");
            loginModal.style.display = 'block';
            registerModal.style.display = 'none';
        } else {
            alert("Kayıt başarısız, tekrar deneyin.");
        }
    })
    .catch(error => console.error('Error:', error));
};

// Giriş formunun AJAX ile gönderilmesi
document.getElementById('loginForm').onsubmit = function(event) {
    event.preventDefault();  // Sayfa yenilenmesin

    const formData = new FormData(this);
    fetch('giris.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Giriş başarılı!");
            window.location.href = "anasayfa.php";  // Giriş sonrası ana sayfaya yönlendir
        } else {
            alert("Geçersiz kullanıcı adı veya şifre.");
        }
    })
    .catch(error => console.error('Error:', error));
};

// Açılır menüyü açma fonksiyonu
function openNav() {
    document.getElementById("mySidebar").style.width = "250px";
    document.getElementById("overlay").style.display = "block";
}

// Açılır menüyü kapatma fonksiyonu
function closeNav() {
    document.getElementById("mySidebar").style.width = "0";
    document.getElementById("overlay").style.display = "none";
}


    function togglePhoneInput(useExisting) {
        const phoneInput = document.getElementById('user_tel');
        phoneInput.disabled = useExisting;
        if (useExisting) {
            phoneInput.value = ''; // Mevcut numara seçildiğinde input temizlenir
        }
    }

