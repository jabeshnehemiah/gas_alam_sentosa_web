<nav class="navbar navbar-expand-lg navbar-dark primary-color-dark">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbar">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="./index.php">Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./settings.php">Settings
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./transactions.php">Transactions
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="dropdown-master" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          Master
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-default"
          aria-labelledby="dropdown-master">
          <a class="dropdown-item" href="./users.php">User</a>
          <a class="dropdown-item" href="./pelanggans.php">Pelanggan</a>
          <a class="dropdown-item" href="./barangs.php">Barang</a>
          <a class="dropdown-item" href="./satuans.php">Satuan</a>
        </div>
      </li>
    </ul>
    <a class="btn btn-danger btn-md my-2 my-sm-0" id="keluar-button">Keluar</a>
  </div>
</nav>

<script>
  $(document).ready(function() {
    // Handle the form submission
    $("#keluar-button").click(function(event) {
      // Send the AJAX request
      $.ajax({
          type: 'POST',
          url: './api/logout.php',
          encode: true
        })
        .done(function(data) {
          // Redirect to the login page
          window.location.href = './login.php';
        });
    });
  });
</script>