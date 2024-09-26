<?php
require_once __DIR__ . '/../classes/user.php';

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $user = new User($conn);
    $userdetails = $user->getUserDetails($username);
    $userID = $userdetails->getUserID();
}
?>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <!-- Logo -->
  <div class="app-brand demo">
        <div class="app-brand-link gap-2">
        <span class="logotext">HireMe-App</span>
          <!-- <img src="../assets/img/icons/websiteicons/hiremeappnewlogo.png" alt="HireMeLogo" style="max-height: 100px;"> -->
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
    <li class="menu-item open">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-building"></i>
        <div data-i18n="Company">Company</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="./viewcompanies.php" class="menu-link">
            <div data-i18n="Verified Companies">Verified Companies</div> <!--all verified companies-->
          </a>
        </li>
        <li class="menu-item">
          <a href="./unverifiedcompanies.php" class="menu-link">
            <div data-i18n="Unverified Companies">Unverified Companies</div> <!--all unverified companies-->
          </a>
        </li>
        <li class="menu-item">
          <a href="./rejectedcompanies.php" class="menu-link">
            <div data-i18n="Rejected Companies">Rejected Companies</div> <!--all rejected companies-->
          </a>
        </li>
      </ul>
    </li>
    <li class="menu-item">
      <a href="./hiree.php" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user-check"></i>
        <div data-i18n="Hired Applicants">Hired Applicants</div>
      </a>
    </li>    
  </ul>
</aside>