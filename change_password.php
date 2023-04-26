<?php include './redirect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<?php
include './head.php';
?>

<body>
  <?php include './navbar.php'; ?>
  <div class="container py-4">
    <div class="alert-container"></div>
    <h1 class="h1-responsive">UBAH PASSWORD</h1>
    <form id="password-form">
      <div class="mb-4">
        <label for="password-lama">password lama</label><span class="red-text">*</span>
        <input type="password" id="password-lama" name="password-lama" class="form-control" required>
      </div>
      <div class="mb-4">
        <label for="password-baru">password baru</label><span class="red-text">*</span>
        <input type="password" id="password-baru" name="password-baru" class="form-control" required>
      </div>
      <div class="mb-4">
        <label for="re-password-baru">ulangi password</label><span class="red-text">*</span>
        <input type="password" id="re-password-baru" name="re-password-baru" class="form-control" required>
      </div>
      <div class="row justify-content-center"><button type="submit" class="btn btn-primary" id="simpan-button">Simpan</button></div>
    </form>
  </div>
</body>
<script>
  $(document).ready(function() {
    $('.alert').alert();

    // Handle the form submission
    $("#password-form").submit(function(event) {
      // Prevent the form from submitting normally
      event.preventDefault();

      if ($('#password-baru').val() != $('#re-password-baru').val()) {
        showAlert('danger', 'Password tidak sama.');
        return;
      }

      // Get the form data
      const formData = {
        'passwordLama': $('#password-lama').val(),
        'passwordBaru': $('#password-baru').val()
      };

      // Send the AJAX request
      $.ajax({
        type: 'POST',
        url: './api/user_change_password.php',
        data: formData,
        success: response => {
          response = JSON.parse(response);
          // Handle the response
          if (response.success) {
            showAlert('success', response.message);
          } else {
            showAlert('danger', response.message);
          }
        },
        error: (jqXHR, textStatus, errorThrown) => {
          console.log(textStatus, errorThrown);
        }
      });
    });

    const showAlert = (type, message) => {
      let alert = document.createElement('div');
      alert.className = `alert alert-${type}`;
      alert.role = 'alert';
      alert.innerHTML = `
    ${message}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    `;
      $('.alert-container').html(alert);
    }
  })
</script>

</html>