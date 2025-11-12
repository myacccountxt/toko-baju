/**
 * assets/js/script.js
 * Enhanced JavaScript for Toko Baju - Interactive Features
 */

document.addEventListener("DOMContentLoaded", function () {
  console.log("Fashion Boutique Website Loaded Successfully!");

  // Variabel global untuk elemen penting
  const searchForm = document.querySelector(".search-form");
  const searchInput = document.querySelector(".search-input");
  const searchButton = document.querySelector(".search-button");

  // =======================================================
  // Helper Functions
  // =======================================================
  function showNotification(message, type = "info") {
    const notification = document.createElement("div");
    notification.className = `notification notification-${type}`;

    // Simplified content injection (requires FontAwesome to work)
    let iconClass = "info-circle";
    let bgColor = "#d1ecf1";
    let textColor = "#0c5460";

    if (type === "success") {
      iconClass = "check-circle";
      bgColor = "#d4edda";
      textColor = "#155724";
    } else if (type === "warning") {
      iconClass = "exclamation-triangle";
      bgColor = "#fff3cd";
      textColor = "#856404";
    }

    notification.innerHTML = `
            <i class="fas fa-${iconClass}"></i>
            <span>${message}</span>
            <button class="notification-close">&times;</button>
        `;

    notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${bgColor};
            color: ${textColor};
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            z-index: 10000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            max-width: 400px;
        `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
      notification.style.transform = "translateX(0)";
    }, 100);

    // Auto remove after 5 seconds
    setTimeout(() => {
      notification.style.transform = "translateX(100%)";
      setTimeout(() => {
        if (notification.parentNode) {
          notification.parentNode.removeChild(notification);
        }
      }, 300);
    }, 5000);

    // Close button logic
    notification
      .querySelector(".notification-close")
      .addEventListener("click", function () {
        notification.style.transform = "translateX(100%)";
        setTimeout(() => {
          if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
          }
        }, 300);
      });
  }

  function shakeElement(element) {
    element.style.animation = "shake 0.5s ease-in-out";
    setTimeout(() => {
      element.style.animation = "";
    }, 500);
  }

  // Add shake animation to CSS if not exists
  if (!document.querySelector("#shake-animation")) {
    const style = document.createElement("style");
    style.id = "shake-animation";
    style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }
        `;
    document.head.appendChild(style);
  }

  // =======================================================
  // 1. Enhanced Product Card Interactions
  // =======================================================
  const productCards = document.querySelectorAll(".product-card");

  productCards.forEach((card, index) => {
    // Add staggered animation delay
    card.style.animationDelay = `${index * 0.1}s`;

    // Handle hover/click effects (CSS handles most transform)
    card.addEventListener("click", function () {
      card.style.transform = "scale(0.98)";
      setTimeout(() => {
        card.style.transform = "";
      }, 150);
    });
  });

  // =======================================================
  // 2. Enhanced Search Form
  // =======================================================
  if (searchForm && searchInput) {
    searchForm.addEventListener("submit", function (e) {
      if (searchInput.value.trim() === "") {
        e.preventDefault();
        showNotification("Silakan masukkan kata kunci pencarian.", "warning");
        searchInput.focus();
        shakeElement(searchInput);
        return;
      }

      // Add loading state
      searchButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
      searchButton.disabled = true;

      // Simulate search delay (Hapus di production)
      setTimeout(() => {
        searchButton.innerHTML = '<i class="fas fa-search"></i>';
        searchButton.disabled = false;
      }, 1000);
    });

    searchInput.addEventListener("input", function () {
      this.style.borderColor =
        this.value.length > 0 ? "var(--primary-color)" : "#e1e5e9";
    });

    searchInput.addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        e.preventDefault();
        searchForm.dispatchEvent(new Event("submit"));
      }
    });
  }

  // =======================================================
  // 3. Smooth Scrolling & 4. Back to Top Button
  // =======================================================
  const navLinks = document.querySelectorAll('a[href^="#"]');
  navLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      const targetId = this.getAttribute("href");
      const targetElement = document.querySelector(targetId);

      if (targetElement) {
        e.preventDefault();
        targetElement.scrollIntoView({ behavior: "smooth", block: "start" });
      }
    });
  });

  const backToTopBtn = document.createElement("button");
  backToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
  backToTopBtn.className = "back-to-top";
  backToTopBtn.style.cssText = `
        position: fixed; bottom: 2rem; right: 2rem; background: var(--primary-color);
        color: white; border: none; border-radius: 50%; width: 50px; height: 50px;
        cursor: pointer; opacity: 0; visibility: hidden; transition: all 0.3s ease;
        z-index: 1000; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;

  document.body.appendChild(backToTopBtn);

  window.addEventListener("scroll", function () {
    const isVisible = window.pageYOffset > 300;
    backToTopBtn.style.opacity = isVisible ? "1" : "0";
    backToTopBtn.style.visibility = isVisible ? "visible" : "hidden";
  });

  backToTopBtn.addEventListener("click", function () {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });

  // =======================================================
  // 5. Image Lazy Loading Enhancement (Logic Dihapus karena tidak ada data-src di HTML)
  // =======================================================
  // Catatan: Fungsionalitas Lazy Loading di kode JS sebelumnya tidak berjalan
  // karena tidak ada atribut 'data-src' di kode PHP index.

  // =======================================================
  // 10. Loading Animation for Page Transitions
  // =======================================================
  const links = document.querySelectorAll(
    'a[href]:not([href^="#"]):not([target="_blank"])'
  );

  links.forEach((link) => {
    link.addEventListener("click", function (e) {
      if (!e.ctrlKey && !e.metaKey) {
        e.preventDefault();
        document.body.style.opacity = "0.7";

        setTimeout(() => {
          window.location.href = this.href;
        }, 200);
      }
    });
  });

  // Fade in body on load
  document.body.style.opacity = "0";
  setTimeout(() => {
    document.body.style.opacity = "1";
    document.body.style.transition = "opacity 0.5s ease";
  }, 100);

  // =======================================================
  // 12. Dynamic Copyright Year
  // =======================================================
  const copyrightYear = document.querySelector(".footer-bottom p");
  if (copyrightYear) {
    const currentYear = new Date().getFullYear();
    copyrightYear.innerHTML = copyrightYear.innerHTML.replace(
      /2023|2025/g,
      currentYear
    );
  }

  console.log("All interactive features initialized successfully!");
});
