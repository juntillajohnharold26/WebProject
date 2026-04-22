<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevBuy</title>
</head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" id="homepage" href="Introduction.html">DevBuy</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-center">
          <li class="nav-item"><a class="nav-link" href="Introduction.html">Home</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle about-btn" href="#" id="aboutDropdown" role="button"
              data-bs-toggle="dropdown" aria-expanded="false">About</a>
            <ul class="dropdown-menu" aria-labelledby="aboutDropdown">
              <li><a class="dropdown-item" href="aboutweb.html#background">Website's Background</a></li>
              <li><a class="dropdown-item" href="aboutweb.html#process">Running Process</a></li>
              <li><a class="dropdown-item" href="aboutweb.html#skills">Skills & Tools</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle services-btn" href="#" id="servicesDropdown" role="button"
              data-bs-toggle="dropdown" aria-expanded="false">
              Services
            </a>
            <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
              <li><a class="dropdown-item" href="aboutweb.html#web-dev">Website Development</a></li>
              <li><a class="dropdown-item" href="aboutweb.html#ecommerce">E-commerce Websites</a></li>
              <li><a class="dropdown-item" href="aboutweb.html#uiux">UI/UX Designs</a></li>
              <li><a class="dropdown-item" href="aboutweb.html#backend">Backend Basics</a></li>
              <li><a class="dropdown-item" href="aboutweb.html#maintenance">Website Maintenance</a></li>
              
            </ul>
          </li>
          <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
        </ul>
        <div class="d-flex ms-auto align-items-center">
          <a href="sign-up.html" class="btn btn-dark btn-sm me-3 sign-btn">Sign up</a>
          <span class="me-2 account-text">Already have an account?</span>
          <a href="login.html" class="btn btn-dark btn-sm login-btn">Log in</a>
        </div>
      </div>
    </div>
  </nav>
<div>
    {{ $slot }}
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>
</body>
</html>