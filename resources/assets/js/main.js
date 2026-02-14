(function () {
  "use strict";

  // Helper untuk akses localStorage yang aman
  function getStorage(key) {
    try {
      return localStorage.getItem(key);
    } catch (e) {
      return null;
    }
  }

  if (getStorage("vyzordarktheme")) {
    document.querySelector("html").setAttribute("data-theme-mode", "dark");
    document.querySelector("html").setAttribute("data-menu-styles", "transparent");
    document.querySelector("html").setAttribute("data-header-styles", "transparent");
  }
  
  if (getStorage("vyzorrtl")) {
    let html = document.querySelector("html");
    html.setAttribute("dir", "rtl");
    document.querySelector("#style")?.setAttribute("href", "build/assets/libs/bootstrap/css/bootstrap.rtl.min.css");
  }

  // ... (Logika lainnya tetap sama, hanya panggil getStorage)

  function localStorageBackup() {
    let html = document.querySelector("html");
    
    // PERSIST SIDEBAR STATE (SAFE VERSION)
    const sidebarToggled = getStorage("sidebar-toggled");
    if (window.innerWidth >= 992) {
        if (sidebarToggled && sidebarToggled !== "open") {
            html.setAttribute("data-toggled", sidebarToggled);
        } else {
            html.removeAttribute("data-toggled");
        }
    } else {
        html.setAttribute("data-toggled", "close");
    }
  }
  
  localStorageBackup();
})();
