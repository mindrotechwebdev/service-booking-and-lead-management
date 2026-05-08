/* ========================================
   script.js — Global JavaScript
   ======================================== */

/* ----------------------------------------
   1. BOOK NOW — Show alert on click
   ----------------------------------------
   All "Book Now" buttons have class .btn-book
   We listen for click and show a friendly alert.
   ---------------------------------------- */
document.addEventListener('DOMContentLoaded', function () {

  // Select all Book Now buttons
  const bookBtns = document.querySelectorAll('.btn-book');

  bookBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      // Get the service name from the card title above the button
      const card = btn.closest('.service-card');
      let serviceName = 'this service';

      if (card) {
        const titleEl = card.querySelector('.card-title');
        if (titleEl) serviceName = titleEl.textContent.trim();
      }

      alert('✅ ' + serviceName + ' booked successfully! We will contact you shortly.');
    });
  });

  /* ----------------------------------------
     2. SEARCH FILTER — Services page
     ----------------------------------------
     Listens to the search input and hides cards
     whose title doesn't match the query.
     ---------------------------------------- */
  const searchInput = document.getElementById('searchInput');

  if (searchInput) {
    searchInput.addEventListener('input', function () {
      const query = searchInput.value.toLowerCase().trim();
      const cards = document.querySelectorAll('.service-card-wrap');

      cards.forEach(function (wrap) {
        const title = wrap.querySelector('.card-title').textContent.toLowerCase();
        // Show card if title includes the search query, hide otherwise
        if (title.includes(query)) {
          wrap.style.display = '';
        } else {
          wrap.style.display = 'none';
        }
      });
    });
  }

  /* ----------------------------------------
     3. LOGIN — Navigate to login.html
     ----------------------------------------
     The Login button in the navbar redirects
     to the login page using window.location.
     ---------------------------------------- */
  const loginBtn = document.getElementById('navLoginBtn');
  if (loginBtn) {
    loginBtn.addEventListener('click', function () {
      window.location.href = 'login.html';
    });
  }

  /* ----------------------------------------
     4. LOGIN FORM — Basic validation
     ---------------------------------------- */
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', function (e) {
      e.preventDefault(); // Stop page reload

      const email    = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value.trim();

      if (!email || !password) {
        alert('⚠️ Please fill in both email and password.');
        return;
      }

      // In a real app you'd send to a server.
      // For now, redirect to home page on success.
      alert('👋 Welcome back! Login successful.');
      window.location.href = 'index.html';
    });
  }

  /* ----------------------------------------
     5. REGISTER LINK — Navigate to register
     ---------------------------------------- */
  const registerLink = document.getElementById('registerLink');
  if (registerLink) {
    registerLink.addEventListener('click', function (e) {
      e.preventDefault();
      alert('📝 Register page coming soon!');
      // Replace with: window.location.href = 'register.html';
    });
  }

}); // end DOMContentLoaded
