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
    <div class="row">
    <div class="col-lg-2 sidebar d-none d-lg-block">
      <h5 class="text-white mb-3">Menu</h5>
      <a href="accinfo.html">Account Info</a>
      <a href="#">Purchased</a>
      <a href="#">Favorites</a>
      <hr class="text-secondary">
      <a href="#">Payment</a>
      <a href="#">Help</a>
      <a href="#">Feedback</a>
    </div>
<div>
    {{ $slot }}
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>
</body>
</html>