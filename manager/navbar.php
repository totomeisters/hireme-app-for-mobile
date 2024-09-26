<?php
require_once '../classes/job.php';
$job = new Job($conn);
$notifications = $job->getNotifications(0);
?>

<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
      <i class="bx bx-menu bx-sm"></i>
    </a>
  </div>

  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    <!-- Logo -->
    <div class="navbar-nav align-items-center">
      <span class="logotext">HireMe-App</span>
      <!-- <img src="../assets/img/icons/websiteicons/hiremeappnewlogo.png" alt="HireMeLogo" style="max-height: 30px;"> -->
    </div>
    <!-- /Logo -->

    <ul class="navbar-nav flex-row align-items-center ms-auto"><?php
        $unreadCount = 0;
        foreach ($notifications as $notification) {
          if ($notification['is_read'] == 0) {
            $unreadCount++;
          }
        }
      ?>
      <li class="nav-item dropdown notifications">
        <a class="nav-link dropdown-toggle hide-arrow" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-toggle="tooltip" data-bs-placement="left" title='Click on a notification to mark them as "read".'>
          <span class="notification-circle">
            <i class='bx bx-bell'></i>
            <?php
            if ($unreadCount != 0){
              echo '<span class="notification-badge">'.$unreadCount.'</span>';
            }
            ?>
          </span>
        </a>
        <ul class="px-1 dropdown-menu dropdown-menu-end border border-secondary">
          <h4>Notifications</h4>
          <?php if (count($notifications) > 0) : ?>
              <div style="max-height: 500px; overflow-y: auto;">
            <?php foreach ($notifications as $notification) : ?>
              <li>
                <div class="rounded-2 mb-1 dropdown-item text-wrap mark-as-read <?php echo $notification['is_read'] ? 'bg-light text-dark border border-secondary' : 'bg-dark text-white'; ?>" data-notification-id="<?= $notification['id']; ?>" style="width: 25rem;">
                  <?php echo $notification['content'];?>
                </div>
              </li>
            <?php endforeach; ?>
            </div>
          <?php else : ?>
            <li>
              <div class="rounded-2 mb-1 dropdown-item bg-dark text-white text-wrap border border-secondary" style="width: 25rem;"></div>
            </li>
          <?php endif; ?>
        </ul>
      </li>

      <!-- UserNav -->
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar avatar-online">
            <span class="flex-shrink-0 badge badge-center rounded-pill bg-warning w-px-40 h-px-40 rounded-circle">
              <?php
              $username = $_SESSION['username'];

              $usernameinitial = ucfirst(substr($username, 0, 1));

              echo $usernameinitial;
              ?>
            </span>
            <!-- <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" /> -->
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="#">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar avatar-online">
                    <span class="flex-shrink-0 badge badge-center rounded-pill bg-warning w-px-40 h-px-40 rounded-circle">

                      <?php
                      $username = $_SESSION['username'];

                      $usernameinitial = ucfirst(substr($username, 0, 1));

                      echo $usernameinitial;
                      ?>

                    </span>
                  </div>
                </div>
                <div class="flex-grow-1">
                  <span class="fw-semibold d-block">
                    <?php
                    echo ucfirst($_SESSION['username']);
                    ?>
                  </span>
                  <small class="text-muted">
                    <?php
                    if (isset($_SESSION['username'])) {
                      $username = $_SESSION['username'];
                      $user = new User($conn);
                      $userdetails = $user->getUserDetails($username);
                      if ($userdetails) {
                        $role = $userdetails->getRole();
                        echo ucfirst($role);
                      }
                    } else {
                      echo "Username is not set. How'd you get in here kid?";
                    }
                    ?>
                  </small>
                </div>
              </div>
            </a>
          </li>
          <li>
            <div class="dropdown-divider"></div>
          </li>
          <li>
            <a class="dropdown-item" href="../functions/logout.php">
              <i class="bx bx-power-off me-2"></i>
              <span class="align-middle">Log Out</span>
            </a>
          </li>
        </ul>
      </li>
      <!--/ UserNav -->
    </ul>
  </div>
</nav>