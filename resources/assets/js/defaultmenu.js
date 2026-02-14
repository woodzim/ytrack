"use strict";

const ANIMATION_DURATION = 300;
const sidebar = document.getElementById("sidebar");
let mainContentDiv = document.querySelector(".main-content");

function setStorage(key, value) {
    try { localStorage.setItem(key, value); } catch (e) {}
}
function getStorage(key) {
    try { return localStorage.getItem(key); } catch (e) { return null; }
}

const slideHasSub = document.querySelectorAll(".nav > ul > .slide.has-sub");
const firstLevelItems = document.querySelectorAll(".nav > ul > .slide.has-sub > a");
const innerLevelItems = document.querySelectorAll(".nav > ul > .slide.has-sub .slide.has-sub > a");

// --- Exported functions for custom-switcher.js ---
export function ResizeMenu() {}
export function closedSidemenuFn() {}
export function detachedFn() {}
export function iconTextFn() {}
export function doubletFn() {}
export function menuClickFn() {}
export function menuhoverFn() {}
export function iconClickFn() {}
export function iconHoverFn() {}
export function setNavActive() {}
export function clearNavDropdown() {}
export function checkHoriMenu() {}
export function iconOverayFn() {}

// Fungsi Hover untuk membuka sidebar sementara
function mouseEntered() {
  const html = document.querySelector("html");
  const toggled = html.getAttribute("data-toggled");
  if (toggled && (toggled.includes("close") || toggled.includes("double-menu-close"))) {
    clearTimeout(window.hoverCloseTimeout);
    html.setAttribute("data-icon-overlay", "open");
  }
}

function mouseLeave() {
  const html = document.querySelector("html");
  window.hoverCloseTimeout = setTimeout(() => {
    html.removeAttribute("data-icon-overlay");
  }, 300);
}

const slideUp = (target, duration = ANIMATION_DURATION) => {
  const { parentElement } = target;
  if (!parentElement) return;
  parentElement.classList.remove("open");
  target.style.transitionProperty = "height, margin, padding";
  target.style.transitionDuration = `${duration}ms`;
  target.style.boxSizing = "border-box";
  target.style.height = `${target.offsetHeight}px`;
  target.offsetHeight;
  target.style.overflow = "hidden";
  target.style.height = 0;
  target.style.paddingTop = 0;
  target.style.paddingBottom = 0;
  target.style.marginTop = 0;
  target.style.marginBottom = 0;
  window.setTimeout(() => {
    target.style.display = "none";
    target.style.removeProperty("height");
    target.style.removeProperty("padding-top");
    target.style.removeProperty("padding-bottom");
    target.style.removeProperty("margin-top");
    target.style.removeProperty("margin-bottom");
    target.style.removeProperty("overflow");
    target.style.removeProperty("transition-duration");
    target.style.removeProperty("transition-property");
  }, duration);
};

const slideDown = (target, duration = ANIMATION_DURATION) => {
  const { parentElement } = target;
  if (!parentElement) return;
  parentElement.classList.add("open");
  target.style.removeProperty("display");
  let { display } = window.getComputedStyle(target);
  if (display === "none") display = "block";
  target.style.display = display;
  const height = target.offsetHeight;
  target.style.overflow = "hidden";
  target.style.height = 0;
  target.style.paddingTop = 0;
  target.style.paddingBottom = 0;
  target.style.marginTop = 0;
  target.style.marginBottom = 0;
  target.offsetHeight;
  target.style.boxSizing = "border-box";
  target.style.transitionProperty = "height, margin, padding";
  target.style.transitionDuration = `${duration}ms`;
  target.style.height = `${height}px`;
  target.style.removeProperty("padding-top");
  target.style.removeProperty("padding-bottom");
  target.style.removeProperty("margin-top");
  target.style.removeProperty("margin-bottom");
  window.setTimeout(() => {
    target.style.removeProperty("height");
    target.style.removeProperty("overflow");
    target.style.removeProperty("transition-property");
    target.style.removeProperty("transition-duration");
  }, duration);
};

const slideToggle = (target, duration = ANIMATION_DURATION) => {
  if (window.getComputedStyle(target).display === "none") return slideDown(target, duration);
  return slideUp(target, duration);
};

export function toggleSidemenu() {
  let html = document.querySelector("html");
  let sidemenuType = html.getAttribute("data-nav-layout");

  if (window.innerWidth >= 992 && sidemenuType === "vertical") {
    let verticalStyle = html.getAttribute("data-vertical-style");
    let currentToggled = html.getAttribute("data-toggled");
    let newState = "";

    if (currentToggled) {
        html.removeAttribute("data-toggled");
        newState = "open";
    } else {
        let toggleVal = "icon-overlay-close";
        if (verticalStyle === "closed") toggleVal = "close-menu-close";
        if (verticalStyle === "icontext") toggleVal = "icon-text-close";
        if (verticalStyle === "detached") toggleVal = "detached-close";
        if (verticalStyle === "doublemenu") toggleVal = "double-menu-close";
        
        html.setAttribute("data-toggled", toggleVal);
        newState = toggleVal;
    }
    setStorage("sidebar-toggled", newState);
  } else if (window.innerWidth < 992) {
    if (html.getAttribute("data-toggled") === "open") {
      html.setAttribute("data-toggled", "close");
    } else {
      html.setAttribute("data-toggled", "open");
    }
  }
}

(() => {
  const headerToggleBtn = document.querySelector(".sidemenu-toggle");
  if (headerToggleBtn) headerToggleBtn.addEventListener("click", toggleSidemenu);

  if (sidebar) {
    sidebar.addEventListener("mouseenter", mouseEntered);
    sidebar.addEventListener("mouseleave", mouseLeave);
  }

  // Handle first level submenus
  firstLevelItems.forEach((element) => {
    element.addEventListener("click", (e) => {
      const parent = element.parentElement;
      const next = element.nextElementSibling;
      if (next && next.classList.contains('slide-menu')) {
        parent.parentElement.querySelectorAll(':scope > .slide.has-sub.open').forEach(openSlide => {
            if (openSlide !== parent) {
                slideUp(openSlide.querySelector('.slide-menu'));
            }
        });
        slideToggle(next);
      }
    });
  });

  // Handle inner level submenus
  innerLevelItems.forEach((element) => {
    element.addEventListener("click", (e) => {
      const parent = element.parentElement;
      const next = element.nextElementSibling;
      if (next && next.classList.contains('slide-menu')) {
        parent.parentElement.querySelectorAll(':scope > .slide.has-sub.open').forEach(openSlide => {
            if (openSlide !== parent) {
                slideUp(openSlide.querySelector('.slide-menu'));
            }
        });
        slideToggle(next);
      }
    });
  });

  const html = document.querySelector("html");
  const savedState = getStorage("sidebar-toggled");
  if (window.innerWidth >= 992 && savedState && savedState !== "open") {
      html.setAttribute("data-toggled", savedState);
  }
})();
