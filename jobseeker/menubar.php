<?php
require_once __DIR__ . '/../classes/user.php';
require_once __DIR__ . '/../classes/company.php';

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $user = new User($conn);
    $userdetails = $user->getUserDetails($username);
    $userID = $userdetails->getUserID();
} 

else {
    echo "No user details found for user ID: $userID";
}
?>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <!-- Logo -->
  <div class="app-brand demo">
        <div class="app-brand-link gap-2">
        <span class="logotext">HireMe-App</span>
          <!-- <img src="/HireMe/assets/img/icons/websiteicons/hiremeappnewlogo.png" alt="HireMeLogo" style="max-height: 100px;"> -->
        </div>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>
  <!-- /Logo -->

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <!-- Dashboard -->
    <li class="menu-item">
      <a href="./dashboard.php" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div data-i18n="Dashboard">Dashboard</div>
      </a>
    </li>

    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Pages</span>
    </li>
    <li class="menu-item">
      <a href="./jobs.php" class="menu-link">
        <i class="menu-icon tf-icons bx bx-briefcase"></i>
        <div data-i18n="Jobs">Jobs</div>
      </a>
    </li>
    <li class="menu-item">
      <a href="./favejobs.php" class="menu-link">
        <i class="menu-icon tf-icons bx bx-heart"></i>
        <div data-i18n="Favorite Jobs">Favorite Jobs</div>
      </a>
    </li>
    <li class="menu-item">
      <a href="./appliedjobs.php" class="menu-link">
        <i class="menu-icon tf-icons bx bx-task"></i>
        <div data-i18n="Jobs Applied To">Jobs Applied To</div>
      </a>
    </li>
    <li class="menu-item">
      <a href="./companies.php" class="menu-link">
        <i class="menu-icon tf-icons bx bx-buildings"></i>
        <div data-i18n="Companies">Companies</div>
      </a>
    </li>
    
    
  </ul>
</aside>