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
    <div class="add-container"></div>
    <div class="other-container"></div>
  </div>
</body>
<script type="text/javascript">
  let table = '';
  $(document).ready(function() {
    // Create a new URLSearchParams object from the URL search string
    const urlParams = new URLSearchParams(window.location.search);

    // Get the value of the "id" parameter
    table = urlParams.get('table');

    loadPage(table);

    $('.alert').alert();
  });

  // Function to load page
  const loadPage = table => {
    // Send the AJAX request
    $.ajax({
      type: 'POST',
      url: './api/master_get.php',
      data: {
        'table': table
      },
      success: (response) => {
        response = JSON.parse(response);
        let html;

        if (response.data) {
          // Add heading
          $('#heading').html(`
          <h1>${table.toUpperCase()}</h1>
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahMaster"><i class="fas fa-plus mr-2"></i>Tambah</button>
          `);

          // Initialize modal
          let modalAdd = `
          <div class="modal fade" id="modalTambahMaster" tabindex="-1" role="dialog" aria-labelledby="modalTambahMasterTitle" aria-hidden="true">
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

          // Initialize datatable
          html = `
          <table id="datatable" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
          `;

          const data = response['data'];
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

          // Set head, foot, and modal form
          keys.forEach(key => {
            head += `<th>${key.toUpperCase()}</th>`;
            foot += `<th>${key.toUpperCase()}</th>`;

            // Add form if column is fillable
            if (response.fillables.includes(key)) {
              modalAdd += `
              <div class="md-form mb-4">
                <input type="text" id="${key}-input" name="${key}" class="form-control validate" required>
                <label for="${key}-input">${key}</label>
              </div>
              `;
            }
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
              row += `<td>${datum[key]}</td>`;
            });
            row += `
            <td>
              <button type="button" class="btn btn-secondary btn-sm m-0 px-3 edit-button" onClick="editModal('${datum[keys[0]]}')"><i class="fas fa-edit"></i></button>
              <button type="button" class="btn btn-danger btn-sm m-0 px-3 delete-button" onClick="deleteModal('${datum[keys[0]]}')"><i class="fas fa-trash-alt"></i></button>
            </td>
            `;
            row += `</tr>`;
            body += row;
          });
          body += `</tbody>`;

          // Append to html
          html += head + body + foot;
          html += `</table>`;

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

          // Set modal
          $('.add-container').html(modalAdd);

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

          // Add event listener for save button
          $('#input-form').submit((event) => {
            event.preventDefault();

            // Get inputs
            let inputs = {};
            response.fillables.forEach(key => {
              inputs[key] = $(`#${key}-input`).val();
            });

            // Get the form data
            const formData = {
              'table': table,
              'inputs': inputs
            };

            // Send the AJAX request
            $.ajax({
              type: 'POST',
              url: './api/master_add.php',
              data: formData,
              success: response => {
                response = JSON.parse(response);
                $('#modalTambahMaster').modal('hide');
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

  const editModal = id => {
    // Send the AJAX request
    $.ajax({
      type: 'POST',
      url: './api/master_get_one.php',
      data: {
        'id': id,
        'table': table
      },
      success: response => {
        response = JSON.parse(response);
        if (response.success) {
          let modalEdit = `
          <div class="modal fade" id="modalEditMaster" tabindex="-1" role="dialog" aria-labelledby="modalEditMasterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <form id="edit-form">
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Data ${id}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
          `;
          response.fillables.forEach(key => {
            modalEdit += `
            <div class="md-form mb-4">
              <input type="text" id="${key}-edit" name="${key}" class="form-control validate" value="${response.data[key]}" required>
              <label for="${key}-edit">${key}</label>
            </div>
            `;

            $(`#${key}-edit`).trigger('change');
          });
          modalEdit += `
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
          $('.other-container').html(modalEdit);
          response.fillables.forEach(key => {
            $(`#${key}-edit`).trigger('change');
          });
          $('#modalEditMaster').modal('show');

          $('#edit-form').submit(event => {
            event.preventDefault();

            // Get inputs
            let inputs = {};
            response.fillables.forEach(key => {
              inputs[key] = $(`#${key}-edit`).val();
            });

            // Get the form data
            const formData = {
              'id': id,
              'table': table,
              'inputs': inputs
            };

            // Send the AJAX request
            $.ajax({
              type: 'POST',
              url: './api/master_edit.php',
              data: formData,
              success: response => {
                response = JSON.parse(response);
                $('#modalEditMaster').modal('hide');
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
      },
      error: (jqXHR, textStatus, errorThrown) => {
        console.log(textStatus, errorThrown);
      }
    });
  }

  const deleteModal = id => {
    let modalDelete = `
          <div class="modal fade" id="modalDeleteMaster" tabindex="-1" role="dialog" aria-labelledby="modalDeleteMasterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <form id="delete-form">
                  <div class="modal-header">
                    <h5 class="modal-title">Hapus Data ${id}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                  <h2>Apakah Anda yakin akan menghapus data ${id}</h2>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fas fa-ban mr-2"></i>Batal</button>
                    <button type="submit" class="btn btn-danger" id="simpan-button"><i class="fas fa-trash mr-2"></i>Hapus</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          `;
    $('.other-container').html(modalDelete);
    $('#modalDeleteMaster').modal('show');

    $('#delete-form').submit(event => {
      event.preventDefault();

      // Get the form data
      const formData = {
        'id': id,
        'table': table
      };

      // Send the AJAX request
      $.ajax({
        type: 'POST',
        url: './api/master_delete.php',
        data: formData,
        success: response => {
          response = JSON.parse(response);
          $('#modalDeleteMaster').modal('hide');
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