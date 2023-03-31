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
  const selects = ['roles', 'divisis', 'users'];
  let selectValues = {};
  let fillables;
  let table = '';
  $(document).ready(function() {
    selects.forEach(table => {
      loadSelect(table);
    });

    loadPage();

    $('.alert').alert();
  });

  const loadSelect = table => {
    // Send the AJAX request
    $.ajax({
      type: 'POST',
      url: './api/umum_get.php',
      data: {
        'table': table
      },
      success: response => {
        response = JSON.parse(response);
        if (response.success) {
          selectValues[table] = response.data;
        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        console.log(textStatus, errorThrown);
      }
    });
  }


  // Function to load page
  const loadPage = () => {
    // Send the AJAX request
    $.ajax({
      type: 'POST',
      url: './api/user_get.php',
      success: (response) => {
        console.log(response)
        response = JSON.parse(response);
        jsonData = response.data;
        let html;

        fillables = response.fillables;

        // Add heading
        $('#heading').html(`
          <h1>USERS</h1>
          <button type="button" class="btn btn-primary" onClick="addModal()"><i class="fas fa-plus mr-2"></i>Tambah</button>
          `);

        if (response.data.length > 0) {
          // Initialize datatable
          html = `
          <div class="container-fluid">
            <table id="datatable" class="table table-striped table-bordered table-hover text-nowrap" cellspacing="0" width="100%">
          `;

          const data = response.data;
          const keys = Object.keys(data[0]);

          // Set table head and foot
          let head = `
          <thead class="indigo white-text">
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

          head += `
              <th>ACTION</th>
            </tr>
          </thead>
          `;
          foot += `
              <th>ACTION</th>
            </tr>
          </tfoot>
          `;

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
            row += `
            <td>
              <button type="button" class="btn btn-danger btn-sm m-0 px-3 delete-button" onClick="deleteModal('${datum[keys[0]]}','${datum[keys[1]]}')"><i class="fas fa-trash-alt"></i></button>
            </td>
            `;
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
            }
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
    <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="modalTambahTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <form id="input-form">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Data ${table.toUpperCase()}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
    `;
    fillables.forEach(fillable => {
      modalAdd += `
      <div class="md-form mb-4">
        <input type="text" id="${fillable}-input" name="${fillable}" class="form-control validate" required>
        <label for="${fillable}-input">${fillable}</label>
      </div>
      `;
    });
    selects.forEach(table => {
      if (table == 'users') {
        modalAdd += `
        <div class="md-form mb-4">
          <select class="browser-default custom-select" id="atasans-input">
          <option value="" selected hidden>--- PILIH ATASAN ---</option>
        `;
      } else {
        modalAdd += `
        <div class="md-form mb-4">
          <select class="browser-default custom-select" id="${table}-input"`;
        if (table == 'roles') {
          modalAdd += ' required>';
        } else {
          modalAdd += '>';
        }
        modalAdd += `<option value="" selected hidden>--- PILIH ${table.toUpperCase().slice(0,-1)} ---</option>`;
      }

      selectValues[table].forEach(value => {
        modalAdd += `<option value="${value.id}">${value.nama}</option>`;
      })

      modalAdd += `
        </select>
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
      selects.forEach(key => {
        if (key == 'users') {
          key = 'atasans';
        }
        if (!$(`#${key}-input`).val() == "") {
          inputs[key.slice(0, -1).concat('_id')] = $(`#${key}-input`).val();
        }
      });
      console.log(selects)
      console.log(inputs)

      // Get the form data
      const formData = {
        'table': table,
        'inputs': inputs
      };

      // Send the AJAX request
      $.ajax({
        type: 'POST',
        url: './api/user_add.php',
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
          loadPage(table);
        },
        error: (jqXHR, textStatus, errorThrown) => {
          console.log(textStatus, errorThrown);
        }
      });
    });
  }

  const deleteModal = (kode, nama) => {
    let modalDelete = `
          <div class="modal fade" id="modalHapus" tabindex="-1" role="dialog" aria-labelledby="modalHapusTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <form id="delete-form">
                  <div class="modal-header">
                    <h5 class="modal-title">Nonaktifkan ${nama}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                  <h2>Apakah Anda yakin akan menonaktifkan ${nama}?</h2>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fas fa-ban mr-2"></i>Batal</button>
                    <button type="submit" class="btn btn-danger" id="simpan-button"><i class="fas fa-trash mr-2"></i>Nonaktifkan</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          `;
    $('.modal-container').html(modalDelete);
    $('#modalHapus').modal('show');

    $('#delete-form').submit(event => {
      event.preventDefault();

      // Get the form data
      const formData = {
        'kode': kode,
      };

      // Send the AJAX request
      $.ajax({
        type: 'POST',
        url: './api/user_deactivate.php',
        data: formData,
        success: response => {
          response = JSON.parse(response);
          $('#modalHapus').modal('hide');
          $(".modal-backdrop").remove();
          if (response.success) {
            showAlert('success', response.message);
          } else {
            showAlert('danger', response.message);
          }
          loadPage(table);
        },
        error: (jqXHR, textStatus, errorThrown) => {
          console.log(textStatus, errorThrown);
        }
      });
    })

  }
</script>

</html>