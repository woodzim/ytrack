(function () {
  "use strict";

  /* page loader */
  function hideLoader() {
    const loader = document.getElementById("loader");
    loader.classList.add("d-none")
  }

  window.addEventListener("load", hideLoader);
  /* page loader */

  /* tooltip */
  const tooltipTriggerList = document.querySelectorAll(
    '[data-bs-toggle="tooltip"]'
  );
  if (typeof bootstrap !== 'undefined') {
    const tooltipList = [...tooltipTriggerList].map(
      (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
    );
  }

  /* popover  */
  const popoverTriggerList = document.querySelectorAll(
    '[data-bs-toggle="popover"]'
  );
  if (typeof bootstrap !== 'undefined') {
    const popoverList = [...popoverTriggerList].map(
      (popoverTriggerEl) => new bootstrap.Popover(popoverTriggerEl)
    );
  }

  /* breadcrumb date range picker */
  // Get today's date
  const today = new Date();

  // Calculate the start date (today) and end date (30 days from today)
  const startDate = today.toISOString().split('T')[0]; // Format: YYYY-MM-DD
  const endDate = new Date(today);
  endDate.setDate(today.getDate() + 30); // Add 30 days
  const endDateFormatted = endDate.toISOString().split('T')[0]; // Format: YYYY-MM-DD

  if (document.querySelector("#daterange")) {
    flatpickr("#daterange", {
      mode: "range",
      dateFormat: "Y-m-d",
      defaultDate: [startDate, endDateFormatted],
      onReady: function (selectedDates, dateStr, instance) {
        updateInputDisplay([startDate, endDateFormatted], instance);
      },
      onChange: function (selectedDates, dateStr, instance) {
        updateInputDisplay(selectedDates, instance);
      }
    });
  }

  // Function to update the input display with formatted date range
  function updateInputDisplay(dates, instance) {
    if (dates.length === 2) {
      const startDateFormatted = formatDate(dates[0]);
      const endDateFormatted = formatDate(dates[1]);
      instance.input.value = `${startDateFormatted} to ${endDateFormatted}`;
    } else {
      instance.input.value = ''; // Clear value if less than 2 dates
    }
  }

  function formatDate(dateString) {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0'); // Get day and pad with leading zero if necessary
    const month = date.toLocaleString('default', { month: 'short' }); // Get the short month name
    const year = date.getFullYear(); // Get the year
    return `${day}, ${month} ${year}`; // Return formatted date
  }
  /* breadcrumb date range picker */

  if (document.querySelector("#switcher-canvas")) {

    //switcher color pickers
    const pickrContainerPrimary = document.querySelector(
      ".pickr-container-primary"
    );
    const themeContainerPrimary = document.querySelector(
      ".theme-container-primary"
    );
    const pickrContainerBackground = document.querySelector(
      ".pickr-container-background"
    );
    const themeContainerBackground = document.querySelector(
      ".theme-container-background"
    );

    /* for theme primary */
    const nanoThemes = [
      [
        "nano",
        {
          defaultRepresentation: "RGB",
          components: {
            preview: true,
            opacity: false,
            hue: true,

            interaction: {
              hex: false,
              rgba: true,
              hsva: false,
              input: true,
              clear: false,
              save: false,
            },
          },
        },
      ],
    ];
    const nanoButtons = [];
    let nanoPickr = null;
    for (const [theme, config] of nanoThemes) {
      const button = document.createElement("button");
      button.innerHTML = theme;
      nanoButtons.push(button);

      button.addEventListener("click", () => {
        const el = document.createElement("p");
        pickrContainerPrimary.appendChild(el);

        /* Delete previous instance */
        if (nanoPickr) {
          nanoPickr.destroyAndRemove();
        }

        /* Apply active class */
        for (const btn of nanoButtons) {
          btn.classList[btn === button ? "add" : "remove"]("active");
        }

        /* Create fresh instance */
        nanoPickr = new Pickr(
          Object.assign(
            {
              el,
              theme,
              default: "#985ffd",
            },
            config
          )
        );

        /* Set events */
        nanoPickr.on("changestop", (source, instance) => {
          let color = instance.getColor().toRGBA();
          let html = document.querySelector("html");
          html.style.setProperty(
            "--primary-rgb",
            `${Math.floor(color[0])}, ${Math.floor(color[1])}, ${Math.floor(
              color[2]
            )}`
          );
          /* theme color picker */
          localStorage.setItem(
            "primaryRGB",
            `${Math.floor(color[0])}, ${Math.floor(color[1])}, ${Math.floor(
              color[2]
            )}`
          );
          // updateColors();
        });
      });

      themeContainerPrimary.appendChild(button);
    }
    nanoButtons[0].click();
    /* for theme primary */

    /* for theme background */
    const nanoThemes1 = [
      [
        "nano",
        {
          defaultRepresentation: "RGB",
          components: {
            preview: true,
            opacity: false,
            hue: true,

            interaction: {
              hex: false,
              rgba: true,
              hsva: false,
              input: true,
              clear: false,
              save: false,
            },
          },
        },
      ],
    ];
    const nanoButtons1 = [];
    let nanoPickr1 = null;
    for (const [theme, config] of nanoThemes) {
      const button = document.createElement("button");
      button.innerHTML = theme;
      nanoButtons1.push(button);

      button.addEventListener("click", () => {
        const el = document.createElement("p");
        pickrContainerBackground.appendChild(el);

        /* Delete previous instance */
        if (nanoPickr1) {
          nanoPickr1.destroyAndRemove();
        }

        /* Apply active class */
        for (const btn of nanoButtons) {
          btn.classList[btn === button ? "add" : "remove"]("active");
        }

        /* Create fresh instance */
        nanoPickr1 = new Pickr(
          Object.assign(
            {
              el,
              theme,
              default: "#985ffd",
            },
            config
          )
        );

        /* Set events */
        nanoPickr1.on("changestop", (source, instance) => {
          let color = instance.getColor().toRGBA();
          let html = document.querySelector("html");
          html.style.setProperty(
            "--body-bg-rgb",
            `${color[0]}, ${color[1]}, ${color[2]}`
          );
          document
            .querySelector("html")
            .style.setProperty(
              "--body-bg-rgb2",
              `${color[0] + 14}, ${color[1] + 14}, ${color[2] + 14}`
            );
          document
            .querySelector("html")
            .style.setProperty(
              "--light-rgb",
              `${color[0] + 14}, ${color[1] + 14}, ${color[2] + 14}`
            );
          document
            .querySelector("html")
            .style.setProperty(
              "--form-control-bg",
              `rgb(${color[0] + 14}, ${color[1] + 14}, ${color[2] + 14})`
            );
          document
            .querySelector("html")
            .style.setProperty(
              "--gray-3",
              `rgb(${color[0] + 14}, ${color[1] + 14}, ${color[2] + 14})`
            );
          localStorage.removeItem("bgtheme");
          // updateColors();
          html.setAttribute("data-theme-mode", "dark");
          html.setAttribute("data-menu-styles", "dark");
          html.setAttribute("data-header-styles", "dark");
          document.querySelector('#switcher-menu-dark').checked = true;
          document.querySelector('#switcher-header-dark').checked = true;
          document.querySelector("#switcher-dark-theme").checked = true;
          localStorage.setItem(
            "bodyBgRGB",
            `${color[0]}, ${color[1]}, ${color[2]}`
          );
          localStorage.setItem(
            "bodylightRGB",
            `${color[0] + 14}, ${color[1] + 14}, ${color[2] + 14}`
          );
        });
      });
      themeContainerBackground.appendChild(button);
    }
    nanoButtons1[0].click();
    /* for theme background */

  }

  /* header theme toggle */
  function toggleTheme() {
    let html = document.querySelector("html");
    if (html.getAttribute("data-theme-mode") === "dark") {
      html.setAttribute("data-theme-mode", "light");
      html.setAttribute("data-header-styles", "transparent");
      html.setAttribute("data-menu-styles", "transparent");
      if (!localStorage.getItem("primaryRGB")) {
        html.setAttribute("style", "");
      }
      html.removeAttribute("data-bg-theme");
      
      if (document.querySelector("#switcher-canvas")) {
        document.querySelector("#switcher-light-theme").checked = true;
        document.querySelector("#switcher-menu-transparent").checked = true;
      }
      document
        .querySelector("html")
        .style.removeProperty("--body-bg-rgb", localStorage.bodyBgRGB);
      // checkOptions();
      html.style.removeProperty("--body-bg-rgb2");
      html.style.removeProperty("--light-rgb");
      html.style.removeProperty("--form-control-bg");
      html.style.removeProperty("--input-border");
      if (document.querySelector("#switcher-canvas")) {
        document.querySelector("#switcher-header-transparent").checked = true;
        document.querySelector("#switcher-menu-transparent").checked = true;
        document.querySelector("#switcher-light-theme").checked = true;
        document.querySelector("#switcher-background4").checked = false;
        document.querySelector("#switcher-background3").checked = false;
        document.querySelector("#switcher-background2").checked = false;
        document.querySelector("#switcher-background1").checked = false;
        document.querySelector("#switcher-background").checked = false;
      }
      localStorage.removeItem("vyzordarktheme");
      localStorage.removeItem("vyzorMenu");
      localStorage.removeItem("vyzorHeader");
      localStorage.removeItem("bodylightRGB");
      localStorage.removeItem("bodyBgRGB");
      html.setAttribute("data-header-styles", "transparent");
    } else {
      html.setAttribute("data-theme-mode", "dark");
      html.setAttribute("data-header-styles", "transparent");
      if (!localStorage.getItem("primaryRGB")) {
        html.setAttribute("style", "");
      }
      html.setAttribute("data-menu-styles", "transparent");
      
      if (document.querySelector("#switcher-canvas")) {
        document.querySelector("#switcher-dark-theme").checked = true;
        document.querySelector("#switcher-menu-transparent").checked = true;
        document.querySelector("#switcher-header-transparent").checked = true;
        // checkOptions();
        document.querySelector("#switcher-menu-transparent").checked = true;
        document.querySelector("#switcher-header-transparent").checked = true;
        document.querySelector("#switcher-dark-theme").checked = true;
        document.querySelector("#switcher-background4").checked = false;
        document.querySelector("#switcher-background3").checked = false;
        document.querySelector("#switcher-background2").checked = false;
        document.querySelector("#switcher-background1").checked = false;
        document.querySelector("#switcher-background").checked = false;
      }
      localStorage.setItem("vyzordarktheme", "true");
      localStorage.setItem("vyzorMenu", "transparent");
      localStorage.setItem("vyzorHeader", "transparent");
      localStorage.removeItem("bodylightRGB");
      localStorage.removeItem("bodyBgRGB");
    }
  }
  let layoutSetting = document.querySelector(".layout-setting");
  if (layoutSetting) {
    layoutSetting.addEventListener("click", toggleTheme);
  }
  /* header theme toggle */

  /* header theme toggle */
  let html = document.querySelector("html");
  if (html.getAttribute('data-vertical-style') === 'doublemenu') {
    function toggleTheme1() {
      let html = document.querySelector("html");
      if (html.getAttribute("data-theme-mode") === "dark") {
        html.setAttribute("data-theme-mode", "light");
        html.setAttribute("data-header-styles", "transparent");
        html.setAttribute("data-menu-styles", "transparent");
        if (!localStorage.getItem("primaryRGB")) {
          html.setAttribute("style", "");
        }
        html.removeAttribute("data-bg-theme");
        if (document.querySelector("#switcher-canvas")) {
          document.querySelector("#switcher-light-theme").checked = true;
          document.querySelector("#switcher-menu-transparent").checked = true;
        }
        document
          .querySelector("html")
          .style.removeProperty("--body-bg-rgb", localStorage.bodyBgRGB);
        // checkOptions();
        html.style.removeProperty("--body-bg-rgb2");
        html.style.removeProperty("--light-rgb");
        html.style.removeProperty("--form-control-bg");
        html.style.removeProperty("--input-border");
        if (document.querySelector("#switcher-canvas")) {
          document.querySelector("#switcher-header-transparent").checked = true;
          document.querySelector("#switcher-menu-transparent").checked = true;
          document.querySelector("#switcher-light-theme").checked = true;
          document.querySelector("#switcher-background4").checked = false;
          document.querySelector("#switcher-background3").checked = false;
          document.querySelector("#switcher-background2").checked = false;
          document.querySelector("#switcher-background1").checked = false;
          document.querySelector("#switcher-background").checked = false;
        }
        localStorage.removeItem("vyzordarktheme");
        localStorage.removeItem("vyzorMenu");
        localStorage.removeItem("vyzorHeader");
        localStorage.removeItem("bodylightRGB");
        localStorage.removeItem("bodyBgRGB");
        html.setAttribute("data-header-styles", "transparent");
      } else {
        html.setAttribute("data-theme-mode", "dark");
        html.setAttribute("data-header-styles", "transparent");
        if (!localStorage.getItem("primaryRGB")) {
          html.setAttribute("style", "");
        }
        html.setAttribute("data-menu-styles", "transparent");
        if (document.querySelector("#switcher-canvas")) {
          document.querySelector("#switcher-dark-theme").checked = true;
          document.querySelector("#switcher-menu-transparent").checked = true;
          document.querySelector("#switcher-header-transparent").checked = true;
          // checkOptions();
          document.querySelector("#switcher-menu-transparent").checked = true;
          document.querySelector("#switcher-header-transparent").checked = true;
          document.querySelector("#switcher-dark-theme").checked = true;
          document.querySelector("#switcher-background4").checked = false;
          document.querySelector("#switcher-background3").checked = false;
          document.querySelector("#switcher-background2").checked = false;
          document.querySelector("#switcher-background1").checked = false;
          document.querySelector("#switcher-background").checked = false;
        }
        localStorage.setItem("vyzordarktheme", "true");
        localStorage.setItem("vyzorMenu", "transparent");
        localStorage.setItem("vyzorHeader", "transparent");
        localStorage.removeItem("bodylightRGB");
        localStorage.removeItem("bodyBgRGB");
      }
    }
    let layoutSetting1 = document.querySelector(".layout-setting-doublemenu");
    if (layoutSetting1) {
      layoutSetting1.addEventListener("click", toggleTheme1);
    }

  }
  /* header theme toggle */

  /* Choices JS */
  document.addEventListener("DOMContentLoaded", function () {
    var genericExamples = document.querySelectorAll("[data-trigger]");
    for (let i = 0; i < genericExamples.length; ++i) {
      var element = genericExamples[i];
      if (typeof Choices !== 'undefined' && element.nodeName !== 'DIV' && !element.classList.contains('choices__input')) {
        new Choices(element, {
          allowHTML: true,
          placeholderValue: "This is a placeholder set in the config",
          searchPlaceholderValue: "Search",
        });
      }
    }
  });
  /* Choices JS */

  /* footer year */
  document.getElementById("year").innerHTML = new Date().getFullYear();
  /* footer year */

  /* node waves */
  Waves.attach(".btn-wave", ["waves-light"]);
  Waves.init();
  /* node waves */

  /* card with close button */
  let DIV_CARD = ".card";
  let cardRemoveBtn = document.querySelectorAll(
    '[data-bs-toggle="card-remove"]'
  );
  cardRemoveBtn.forEach((ele) => {
    ele.addEventListener("click", function (e) {
      e.preventDefault();
      let $this = this;
      let card = $this.closest(DIV_CARD);
      card.remove();
      return false;
    });
  });
  /* card with close button */

  /* card with fullscreen */
  let cardFullscreenBtn = document.querySelectorAll(
    '[data-bs-toggle="card-fullscreen"]'
  );
  cardFullscreenBtn.forEach((ele) => {
    ele.addEventListener("click", function (e) {
      let $this = this;
      let card = $this.closest(DIV_CARD);
      card.classList.toggle("card-fullscreen");
      card.classList.remove("card-collapsed");
      e.preventDefault();
      return false;
    });
  });
  /* card with fullscreen */

  /* count-up */
  var i = 1;
  setInterval(() => {
    document.querySelectorAll(".count-up").forEach((ele) => {
      if (ele.getAttribute("data-count") >= i) {
        i = i + 1;
        ele.innerText = i;
      }
    });
  }, 10);
  /* count-up */

  /* Progressbar Top */
  window.addEventListener('scroll', () => {
    var widnowScroll = document.body.scrollTop || document.documentElement.scrollTop,
      height = document.documentElement.scrollHeight - document.documentElement.clientHeight,
      scrollAmount = (widnowScroll / height) * 100;
    document.querySelector(".progress-top-bar").style.width = scrollAmount + "%";
  })
  /* Progressbar Top */

  /* back to top */
  const scrollToTop = document.querySelector(".scrollToTop");
  const $rootElement = document.documentElement;
  const $body = document.body;
  window.onscroll = () => {
    const scrollTop = window.scrollY || window.pageYOffset;
    const clientHt = $rootElement.scrollHeight - $rootElement.clientHeight;
    if (window.scrollY > 100) {
      scrollToTop.style.display = "flex";
    } else {
      scrollToTop.style.display = "none";
    }
  };
  scrollToTop.onclick = () => {
    window.scrollTo(0, 0);
  };
  /* back to top */

  /* Form submission throttle and loading state */
  document.addEventListener('submit', function (event) {
    const form = event.target;
    
    // Check if the form is already submitting to prevent loops
    if (form.dataset.submitting === 'true') {
      event.preventDefault();
      return;
    }

    // Find the submit button(s)
    const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
    
    // Handle buttons that might be outside the form but associated via form attribute
    const formId = form.getAttribute('id');
    let externalButtons = [];
    if (formId) {
      externalButtons = document.querySelectorAll(`button[type="submit"][form="${formId}"], input[type="submit"][form="${formId}"]`);
    }

    const allButtons = [...submitButtons, ...externalButtons];

    // Check if any button is already disabled (might be handled by onsubmit="return confirm()")
    // If the event was already prevented by an inline onsubmit, don't do anything
    if (event.defaultPrevented) {
      return;
    }

    // Mark form as submitting
    form.dataset.submitting = 'true';

    allButtons.forEach(button => {
      // Prevent double click by disabling the button
      button.disabled = true;
      
      // Store original content if not already stored
      if (!button.dataset.originalContent) {
        button.dataset.originalContent = button.innerHTML;
      }
      
      // Get button width to maintain it if possible
      const width = button.offsetWidth;
      button.style.minWidth = `${width}px`;

      // Add loading spinner
      let loadingText = '';
      const content = button.innerHTML.toLowerCase();
      if (button.classList.contains('text-danger') || content.includes('hapus')) {
        loadingText = 'Menghapus...';
      } else if (content.includes('tambah')) {
        loadingText = 'Menambah...';
      } else if (content.includes('edit') || content.includes('simpan') || content.includes('update')) {
        loadingText = 'Menyimpan...';
      } else {
        loadingText = 'Loading...';
      }

      button.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${loadingText}`;
    });
    
    // Safety timeout: If for some reason the page doesn't reload after 10 seconds, re-enable buttons
    // This handles cases like triggered downloads or interrupted submissions
    setTimeout(() => {
      if (document.body.contains(form)) {
        form.dataset.submitting = 'false';
        allButtons.forEach(button => {
          button.disabled = false;
          if (button.dataset.originalContent) {
            button.innerHTML = button.dataset.originalContent;
          }
          button.style.minWidth = '';
        });
      }
    }, 10000);
  });
  /* Form submission throttle and loading state */

  /* header dropdowns scroll */
  var myHeadernotification = document.getElementById("header-notification-scroll");
  if (myHeadernotification) {
    new SimpleBar(myHeadernotification, { autoHide: true });
  }

  var myHeaderCart = document.getElementById("header-cart-items-scroll");
  if (myHeaderCart) {
    new SimpleBar(myHeaderCart, { autoHide: true });
  }
  /* header dropdowns scroll */

  if (document.querySelector("#header-search")) {
    const autoCompleteJS = new autoComplete({
      selector: "#header-search",
      data: {
        src: [
          "How do plants adapt to different environments?",
          "What makes the ocean's tides rise and fall?",
          "How do our brains process emotions?",
          "What factors contribute to the creation of a rainbow?",
          "Who invented the telephone?",
          "What role does the moon play in Earth's ecosystem?",
          "How do animals communicate with each other?",
          "What causes earthquakes to happen?",
          "What is the significance of the Great Barrier Reef?",
          "How do human bones regenerate after an injury?"
        ],
        cache: true,
      },
      resultItem: {
        highlight: true
      },
      events: {
        input: {
          selection: (event) => {
            const selection = event.detail.selection.value;
            autoCompleteJS.input.value = selection;
          }
        }
      }
    });
  }
})();

/* full screen */
var elem = document.documentElement;
window.openFullscreen = function() {
  let open = document.querySelector(".full-screen-open");
  let close = document.querySelector(".full-screen-close");

  if (
    !document.fullscreenElement &&
    !document.webkitFullscreenElement &&
    !document.msFullscreenElement
  ) {
    if (elem.requestFullscreen) {
      elem.requestFullscreen();
    } else if (elem.webkitRequestFullscreen) {
      /* Safari */
      elem.webkitRequestFullscreen();
    } else if (elem.msRequestFullscreen) {
      /* IE11 */
      elem.msRequestFullscreen();
    }
    close.classList.add("d-block");
    close.classList.remove("d-none");
    open.classList.add("d-none");
  } else {
    if (document.exitFullscreen) {
      document.exitFullscreen();
    } else if (document.webkitExitFullscreen) {
      /* Safari */
      document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) {
      /* IE11 */
      document.msExitFullscreen();
    }
    close.classList.remove("d-block");
    open.classList.remove("d-none");
    close.classList.add("d-none");
    open.classList.add("d-block");
  }
}
/* full screen */

/* toggle switches */
let customSwitch = document.querySelectorAll(".toggle");
customSwitch.forEach((e) =>
  e.addEventListener("click", () => {
    e.classList.toggle("on");
  })
);
/* toggle switches */

/* header dropdown close button */

/* for cart dropdown */
const headerbtn = document.querySelectorAll(".dropdown-item-close");
headerbtn.forEach((button) => {
  button.addEventListener("click", (e) => {
    e.preventDefault();
    e.stopPropagation();
    button.parentNode.parentNode.parentNode.parentNode.parentNode.remove();
    const cartData = document.getElementById("cart-data");
    if (cartData) {
      cartData.innerText = `${document.querySelectorAll(".dropdown-item-close").length} `;
    }
    const cartBadge = document.getElementById("cart-icon-badge");
    if (cartBadge) {
      cartBadge.innerText = `${document.querySelectorAll(".dropdown-item-close").length}`;
    }
    const headerCart = document.getElementById("header-cart-items-scroll");
    if (headerCart) {
      console.log(headerCart.children.length);
    }
    if (document.querySelectorAll(".dropdown-item-close").length == 0) {
      let elementHide = document.querySelector(".empty-header-item");
      let elementShow = document.querySelector(".empty-item");
      if (elementHide) elementHide.classList.add("d-none");
      if (elementShow) elementShow.classList.remove("d-none");
    }
  });
});
/* for cart dropdown */

/* for notifications dropdown */
const headerbtn1 = document.querySelectorAll(".dropdown-item-close1");
headerbtn1.forEach((button) => {
  button.addEventListener("click", (e) => {
    e.preventDefault();
    e.stopPropagation();
    button.parentNode.parentNode.parentNode.parentNode.remove();
    const notifData = document.getElementById("notifiation-data");
    if (notifData) {
      notifData.innerText = `${document.querySelectorAll(".dropdown-item-close1").length} Unread`;
    }
    if (document.querySelectorAll(".dropdown-item-close1").length == 0) {
      let elementHide1 = document.querySelector(".empty-header-item1");
      let elementShow1 = document.querySelector(".empty-item1");
      if (elementHide1) elementHide1.classList.add("d-none");
      if (elementShow1) elementShow1.classList.remove("d-none");
    }
  });
});
/* for notifications dropdown */

/* for cart items quantity */
var value = 1,
  minValue = 0,
  maxValue = 30;

let productMinusBtn = document.querySelectorAll(".product-quantity-minus")
let productPlusBtn = document.querySelectorAll(".product-quantity-plus")
productMinusBtn.forEach((element) => {
  element.onclick = () => {
    value = Number(element.parentElement.childNodes[3].value)
    if (value > minValue) {
      value = Number(element.parentElement.childNodes[3].value) - 1;
      element.parentElement.childNodes[3].value = value;
    }
  }
})
productPlusBtn.forEach((element) => {
  element.onclick = () => {
    if (value < maxValue) {
      value = Number(element.parentElement.childNodes[3].value) + 1;
      element.parentElement.childNodes[3].value = value;
    }
  }
})
/* for cart items quantity */
