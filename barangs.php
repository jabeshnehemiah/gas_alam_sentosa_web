<?php include './redirect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<?php include './head.php'; ?>
<style>
  #kode-input {
    text-transform: uppercase;
  }
</style>

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
  const selects = ['kategori_barangs', 'satuans'];
  let selectValues = {};
  let fillables;
  const numbers = ['harga_beli', 'kode_acc'];
  const radios = {
    'tipe': ['Persediaan', 'Jasa'],
  }
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
      url: './api/barang_get.php',
      success: (response) => {
        response = JSON.parse(response);
        let html;

        fillables = response.fillables;

        // Add heading
        $('#heading').html(`
          <h1>BARANG</h1>
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
                if (key == 'gambar') {
                  row += `<td><img class="p-0" src="${datum[key]}" style="width: 10rem;"/></td>`;
                } else {
                  row += `<td>${datum[key]}</td>`;
                }
              } else {
                row += `<td>-</td>`;
              }
            });
            row += `
            <td>
              <button type="button" class="btn btn-secondary btn-sm m-0 px-3 edit-button" onClick="editModal('${datum[keys[0]]}','${datum[keys[1]]}')"><i class="fas fa-edit"></i></button>
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
              <h5 class="modal-title">Tambah Data Barang</h5>
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
    numbers.forEach(number => {
      modalAdd += `
      <div class="mb-4">
        <label for="${number}-input">${number.replace('_',' ')}</label>
        <input type="number" id="${number}-input" name="${number}" class="form-control validate" required>
      </div>
      `;
    })
    selects.forEach(table => {
      if (table == 'users') {
        table = 'atasans';
      }
      modalAdd += `
      <div class="mb-4">
        <label for="${table}-input">${table.slice(0,-1).replace('_',' ')}</label>
        <select class="browser-default custom-select" id="${table}-input"
      `;
      if (table == 'roles') {
        modalAdd += 'required>';
      } else {
        modalAdd += '>';
      }
      modalAdd += `<option value="" selected hidden>--- PILIH ${table.toUpperCase().slice(0,-1).replace('_',' ')} ---</option>`;

      if (table == 'atasans') {
        table = 'users';
      }
      selectValues[table].forEach(value => {
        modalAdd += `<option value="${value.id}">${value.nama}</option>`;
      });

      modalAdd += `
        </select>
      </div>
      `;
    });

    for (let radio in radios) {
      modalAdd += `
      <div class="mb-4">
        <p>${radio}
      `;

      radios[radio].forEach(value => {
        modalAdd += `
        <div class="custom-control custom-radio custom-control-inline">
          <input type="radio" class="custom-control-input" id="${value}" name="${radio}" required>
          <label class="custom-control-label" for="${value}">${value}</label>
        </div>
        `;
      });

      modalAdd += `</div>`;
    }

    modalAdd += `
    <div class="mb-4">
      <label for="file_gambar-input">file gambar</label><br/>
      <input type="file" id="file_gambar-input" name="file_gambar" accept="image/*" required>
    </div>
    `;

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
      numbers.forEach(key=>{
        inputs[key]=$(`#${key}-input`).val();
      });
      selects.forEach(key => {
        if (!$(`#${key}-input`).val() == "") {
          inputs[key.slice(0, -1).concat('_id')] = $(`#${key}-input`).val();
        }
      });
      for (let radio in radios) {
        inputs[radio] = $(`input[name=${radio}]:checked`).attr('id');
      }

      console.log(inputs)

      // Get the form data
      const formData = {
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
          loadPage();
        },
        error: (jqXHR, textStatus, errorThrown) => {
          console.log(textStatus, errorThrown);
        }
      });
    });
  }

  const editModal = (kode, nama) => {
    // Send the AJAX request
    $.ajax({
      type: 'POST',
      url: './api/user_get_one.php',
      data: {
        'kode': kode,
      },
      success: response => {
        response = JSON.parse(response);
        if (response.success) {
          let modalEdit = `
          <div class="modal fade" id="modalUbah" tabindex="-1" role="dialog" aria-labelledby="modalUbahTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <form id="edit-form">
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Data ${nama}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
          `;
          fillables.forEach(fillable => {
            modalEdit += `
            <div class="mb-4">
              <label for="${fillable}-input">${fillable}</label>
              <input type="text" id="${fillable}-input" name="${fillable}" class="form-control validate" value="${response.data[fillable]}" required>
            </div>
            `;
          });
          selects.forEach(table => {
            if (table == 'users') {
              table = 'atasans';
            }
            modalEdit += `
            <div class="mb-4">
              <label for="${table}-input">${table.slice(0,-1)}</label>
              <select class="browser-default custom-select" id="${table}-input"
            `;
            if (table == 'roles') {
              modalEdit += ' required>';
            } else {
              modalEdit += '>';
            }

            if (table == 'atasans') {
              table = 'users';
            }

            selectValues[table].forEach(value => {
              if (table != 'users' || value.nama != nama) {
                if (response.data[table.slice(0, -1)] == value.nama) {
                  modalEdit += `<option value="${value.id}" selected>${value.nama}</option>`;
                } else {
                  modalEdit += `<option value="${value.id}">${value.nama}</option>`;
                }
              }
            });

            if (table == 'users') {
              table = 'atasans';
            }

            if (table != 'roles') {
              if (response.data[table.slice(0, -1)] == null) {
                modalEdit += `<option value="" selected>Tidak ada</option>`;
              } else {
                modalEdit += `<option value="">Tidak ada</option>`;

              }
            }

            modalEdit += `
              </select>
            </div>
            `;
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
          $('.modal-container').html(modalEdit);
          response.fillables.forEach(key => {
            $(`#${key}-edit`).trigger('change');
          });
          $('#modalUbah').modal('show');

          $('#edit-form').submit(event => {
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
              } else {
                inputs[key.slice(0, -1).concat('_id')] = null;
              }
            });

            // Get the form data
            const formData = {
              'kode': kode,
              'inputs': inputs
            };

            // Send the AJAX request
            $.ajax({
              type: 'POST',
              url: './api/user_edit.php',
              data: formData,
              success: response => {
                response = JSON.parse(response);
                $('#modalUbah').modal('hide');
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
          })
        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        console.log(textStatus, errorThrown);
      }
    });
  }
</script>

</html>