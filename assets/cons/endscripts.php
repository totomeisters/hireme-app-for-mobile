<!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    
    <script src="../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->
    
    <!-- Vendors JS -->
    <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>
    
    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>
    
    <!-- Page JS -->
    <script src="../assets/js/dashboards-analytics.js"></script>
    
    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
          var currentUrl = window.location.pathname;
          var menuLinks = document.querySelectorAll('.menu-link');
          menuLinks.forEach(function(menuLink) {
            var menuItem = menuLink.closest('.menu-item');
            var href = menuLink.getAttribute('href');
            href = href.replace('./', '').split('?')[0];
            if (currentUrl.includes(href)) {
              menuItem.classList.add('active');
              var submenu = menuItem.querySelector('.menu-sub');
              if (submenu) {
                menuItem.classList.add('open');
              }
            }
          });
        });
    </script>