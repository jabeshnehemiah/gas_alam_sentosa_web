<?php include './redirect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<?php include './head.php'; ?>

<body>
  <?php include './navbar.php'; ?>
  <div class="container py-4">
    <div class="alert-container"></div>
    <div class="d-flex justify-content-between" id="heading"></div>
    <div class="table-container"></div>
    <div class="modal-container"></div>
  </div>
</body>
<script type="text/javascript">
  let fillables;
  $(document).ready(function() {
    loadPage();

    $('.alert').alert();
  });

  // Function to load page
  const loadPage = () => {
    // Send the AJAX request
    $.ajax({
      type: 'POST',
      url: './api/divisi_get.php',
      success: (response) => {
        console.log(response)
        response = JSON.parse(response);
        let html;

        fillables = response.fillables;

        // Add heading
        $('#heading').html(`
          <h1>DIVISI</h1>
          <button type="button" class="btn btn-primary" onClick="addModal()"><i class="fas fa-plus mr-2"></i>Tambah</button>
          `);

        if (response.data.length > 0) {
          // Initialize datatable
          html = `
          <div class="container-fluid">
            <table id="datatable" class="table table-sm table-striped table-bordered table-hover text-nowrap" cellspacing="0" width="100%">
          `;

          const data = response.data;
          const keys = Object.keys(data[0]);

          // Set table head and foot
          let head = `
          <thead>
            <tr>
          `;
          let foot = `
          <tfoot>
            <tr>
          `;

          // Set head, foot
          keys.forEach(key => {
            head += `<th>${key.toUpperCase()}</th>`;
            foot += `<th>${key.toUpperCase()}</th>`;
          });

          // Set table body and modal form
          let body = `<tbody>`;
          data.forEach(datum => {
            // Set row data
            let row = `<tr>`;
            keys.forEach(key => {
              if (datum[key] != null) {
                row += `<td>${datum[key]}</td>`;
              } else {
                row += `<td>-</td>`;
              }
            });
            row += `</tr>`;
            body += row;
          });
          body += `</tbody>`;

          // Append to html
          html += head + body + foot;
          html += `
            </table>
          </div>
          `;

          // Set table
          $('.table-container').html(html);

          // Set datatable
          $('#datatable').dataTable({
            initComplete: function() {
              this.api().columns().every(function() {
                var column = this;
                var search = $(`<input class="form-control form-control-sm" type="text" placeholder="Search">`)
                  .appendTo($(column.footer()).empty())
                  .on('change input', function() {
                    var val = $(this).val()
                    column
                      .search(val ? val : '', true, false)
                      .draw();
                  });
              });
            },
            scrollX: true,
            scrollCollapse: true,
            paging: true,
          });
          $('.dataTables_length').addClass('bs-select');
          $('#datatable').parent().addClass('table-responsive');
        } else {
          html = '<p class="h3 red-text text-center">No data available</p>';
          $('.table-container').html(html);
        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        console.log(textStatus, errorThrown);
      }
    });
  }

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

  const addModal = () => {
    // Initialize modal
    let modalAdd = `
    <div class="modal fade" id="modalTambah" tabindex="-1" data-focus="false" role="dialog" aria-labelledby="modalTambahTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <form id="input-form">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Data Divisi</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
    `;
    fillables.forEach(fillable => {
      modalAdd += `
      <div class="mb-4">
        <label for="${fillable}-input">${fillable}</label>
        <input type="text" id="${fillable}-input" name="${fillable}" class="form-control validate" required>
      </div>
      `;
    });

    // Append modal
    modalAdd += `
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-ban mr-2"></i>Batal</button>
              <button type="submit" class="btn btn-primary" id="simpan-button"><i class="fas fa-save mr-2"></i>Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    `;

    $('.modal-container').html(modalAdd);
    $('#modalTambah').modal('show');


    // Add event listener for save button
    $('#input-form').submit((event) => {
      event.preventDefault();

      // Get inputs
      let inputs = {};
      fillables.forEach(key => {
        inputs[key] = $(`#${key}-input`).val();
      });

      // Get the form data
      const formData = {
        'inputs': inputs
      };

      // Send the AJAX request
      $.ajax({
        type: 'POST',
        url: './api/divisi_add.php',
        data: formData,
        success: response => {
          console.log(response);
          response = JSON.parse(response);
          $('#modalTambah').modal('hide');
          $(".modal-backdrop").remove();
          if (response.success) {
            showAlert('success', response.message);
          } else {
            showAlert('danger', response.message);
          }
          loadPage();
        },
        error: (jqXHR, textStatus, errorThrown) => {
          console.log(textStatus, errorThrown);
        }
      });
    });
  }
</script>

</html>