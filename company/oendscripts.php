<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Perfect Scrollbar -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.2/perfect-scrollbar.min.js"></script>

<!-- Bootstrap and Popper.js (Bootstrap bundle includes Popper.js) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.2.3/js/bootstrap.bundle.min.js"></script>

<!-- Vendors JS -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>

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