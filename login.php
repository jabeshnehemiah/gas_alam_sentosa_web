<?php
session_start();

if (isset($_SESSION['username'])) {
  header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include './head.php'; ?>

<body class="primary-color-dark">
  <div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="card p-2">
      <form class="text-center p-5" id="login-form">
        <input type="text" id="username" class="form-control mb-4" placeholder="Username">
        <input type="password" id="password" class="form-control mb-4" placeholder="Password">
        <!-- Sign in button -->
        <button class="btn btn-indigo btn-block" type="submit">Masuk</button>
      </form>
    </div>
  </div>
</body>
<script>
  $(document).ready(function() {
    // Handle the form submission
    $("#login-form").submit(function(event) {
      // Prevent the form from submitting normally
      event.preventDefault();

      // Get the form data
      const formData = {
        'username': $('#username').val(),
        'password': $('#password').val()
      };

      // Send the AJAX request
      $.ajax({
        type: 'POST',
        url: './api/login.php',
        data: formData,
        success: response => {
          console.log(response);
          response = JSON.parse(response);
          // Handle the response
          if (response.success) {
            // Redirect to the home page
            window.location.href = './index.php';
          } else {
            // Display the error message
            alert(response.message);
          }
        },
        error: (jqXHR, textStatus, errorThrown) => {
          console.log(textStatus, errorThrown);
        }
      });
    });
  });
</script>

</html>