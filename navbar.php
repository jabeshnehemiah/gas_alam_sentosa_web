<nav class="navbar navbar-expand-lg navbar-dark primary-color-dark">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbar">
    <ul class="navbar-nav mr-auto">
      <?php
      $navs = [];
      foreach ($links as $key => $link) {
        if (
          isset($link['isNav'])
          && $_SESSION['role'] <= $link['minRole']
          && ((in_array($_SESSION['divisi'], $link['divisi'])
            || is_null($_SESSION['divisi'])))
        ) {
          if (isset($link['dropdown'])) {
            $navs[$link['dropdown']][$key] = $link;
          } else {
            $navs[$key] = $link;
          }
        }
      }
      foreach ($navs as $key => $nav) {
        if (is_array(current($nav))) {
      ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="dropdown-<?php echo $key ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <?php echo $key; ?>
            </a>
            <div class="dropdown-menu dropdown-menu-left dropdown-default w-100" aria-labelledby="dropdown-<?php echo $key; ?>">
              <?php
              foreach ($nav as $key => $item) {
              ?>
                <a class="dropdown-item" href="./<?php echo $key; ?>"><?php echo $item['text']; ?></a>
              <?php
              }
              ?>
            </div>
          </li>
        <?php
        } else {
        ?>
          <li class="nav-item">
            <a class="nav-link" href="./<?php echo $key; ?>"><?php echo $nav['text']; ?></a>
          </li>
      <?php
        }
      }
      ?>
  </ul>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" id="dropdown-profile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-user"></i>
        <?php echo $_SESSION['username'] ?>
      </a>
      <div class="dropdown-menu dropdown-menu-right dropdown-default w-100" aria-labelledby="dropdown-profile">
        <a class="dropdown-item" href="./change_password.php">Ubah Password</a>
        <a class="dropdown-item red-text" id="keluar-button">Keluar</a>
      </div>
    </li>
  </ul>
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