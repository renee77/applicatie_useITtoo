// Hamburger menu: toggle login/winkelwagen dropdown op mobiel
const hamburger = document.getElementById("hamburger");
if (hamburger) {
  const loginMenu = document.getElementById("login");

  hamburger.addEventListener("click", function (e) {
    e.stopPropagation();
    const isOpen = loginMenu.classList.toggle("open");
    this.setAttribute("aria-expanded", isOpen);
  });

  // Sluit menu bij klik buiten de header
  document.addEventListener("click", function (e) {
    if (!hamburger.contains(e.target) && !loginMenu.contains(e.target)) {
      loginMenu.classList.remove("open");
      hamburger.setAttribute("aria-expanded", "false");
    }
  });
}

const loginOverlay = document.getElementById("loginOverlay");

if (loginOverlay) {
  // Klik op het login-icoontje in de header opent de login popup
  document.getElementById("loginLogo").addEventListener("click", function () {
    loginOverlay.style.display = "flex";
  });

  // Klik op de login-knop in de header opent de login popup
  const loginBtn = document.querySelector("button.login");
  if (loginBtn) {
    loginBtn.addEventListener("click", function () {
      loginOverlay.style.display = "flex";
    });
  }

  // Klik op de sluitknop (×) sluit de login popup
  document.getElementById("loginClose").addEventListener("click", function () {
    loginOverlay.style.display = "none";
  });

  // Klik buiten de popup (op de donkere overlay) sluit de login popup
  // e.target is het element waarop geklikt is
  // this is de overlay zelf — als die gelijk zijn, is er buiten de popup geklikt
  loginOverlay.addEventListener("click", function (e) {
    if (e.target === this) {
      this.style.display = "none";
    }
  });

  // Als er een foutmelding in de loginpopup staat, open de popup automatisch
  // zodat de gebruiker de foutmelding direct ziet na een mislukte loginpoging
  if (document.querySelector(".login-popup__error")) {
    loginOverlay.style.display = "flex";
  }
}
