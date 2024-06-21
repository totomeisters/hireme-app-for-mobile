  <script src="../assets/vendor/libs/popper/popper.js"></script>
  <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

  <script src="../assets/vendor/js/bootstrap.js"></script>
  <script src="../assets/vendor/js/menu.js"></script>

  <script src="../assets/js/main.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- <script src="https://cdn.datatables.net/2.0.5/js/dataTables.min.js"></script> -->

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

  <script>
    $(document).ready(function() {
      const notificationItems = $('.mark-as-read');

      notificationItems.click(function(event) {
        const notificationId = $(this).data('notificationId');

        $.ajax({
          url: '../functions/readnotifs.php',
          method: 'POST',
          data: {
            notificationId: notificationId
          },
          dataType: 'json',
          success: function(data) {
            if (data.success) {
              $(this).removeClass('bg-dark text-white');
              $(this).addClass('bg-light text-black');
            } else {
              console.error('Error marking notification as read:', data.error);
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error sending AJAX request:', textStatus, errorThrown);
          }
        });

        event.preventDefault();
      });
    });
  </script>