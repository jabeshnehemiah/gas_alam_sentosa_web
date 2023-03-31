<?php include './redirect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<?php include './head.php'; ?>

<body>
  <?php include './navbar.php'; ?>
  <div class="container py-4">
    <div class="alert-container"></div>
    <div class="d-flex justify-content-between" id="heading"></div>
    <div class="py-3">
      <select class="browser-default custom-select" id="vesselDrop" required>
        <option value="" selected hidden>--- PILIH VESSEL ---</option>
      </select>
    </div>
    <div class="table-container"></div>
    <div class="modal-container"></div>
  </div>
</body>
<script src="./js/qrcode.min.js"></script>
<script type="text/javascript">
  const selects = ['vessels', 'meters', 'uoms', 'fueltypes'];
  let selectValues = {};
  const radios = {
    'measure_type': ['IN', 'OUT'],
    'active': ['Y', 'N']
  }
  let jsonData;
  let table = '';
  $(document).ready(function() {
    // Create a new URLSearchParams object from the URL search string
    const urlParams = new URLSearchParams(window.location.search);

    table = 'settings';

    selects.forEach(table => {
      loadSelect(table);
    })

    $('.alert').alert();
  });

  const loadSelect = table => {
    // Send the AJAX request
    $.ajax({
      type: 'POST',
      url: './api/master_get.php',
      data: {
        'table': table
      },
      success: response => {
        response = JSON.parse(response);
        if (response.success) {
          selectValues[table] = response.data;
        }
        if (response.table == 'vessels') {
          response.data.forEach(value => {
            $('#vesselDrop').append(`<option value="${value.id}">${value.name}</option>`);
          });

          $('#vesselDrop').change(() => {
            loadPage($('#vesselDrop').val());
          })
        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        console.log(textStatus, errorThrown);
      }
    });
  }


  // Function to load page
  const loadPage = vessel => {
    // Send the AJAX request
    $.ajax({
      type: 'POST',
      url: './api/settings_get.php',
      data: {
        'vessel': vessel
      },
      success: (response) => {
        response = JSON.parse(response);
        jsonData = response.data;
        let html;

        // Add heading
        $('#heading').html(`
          <h1>SETTINGS</h1>
          <button type="button" class="btn btn-primary" onClick="addModal()"><i class="fas fa-plus mr-2"></i>Tambah</button>
          `);

        if (response.data.length > 0) {
          // Initialize datatable
          html = `
          <div class="table-responsive">
            <table id="datatable" class="table table-striped table-bordered table-hover text-nowrap" cellspacing="0" width="100%">
          `;

          const data = response.data;
          const keys = Object.keys(data[0]);

          // Set table head and foot
          let head = `
          <thead class="green white-text">
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
              row += `<td>${datum[key]}</td>`;
            });
            row += `
            <td>
              <button type="button" class="btn btn-primary btn-sm m-0 px-3 edit-button" onClick="qrModal('${datum[keys[0]]}')"><i class="fas fa-qrcode"></i></button>
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

  const qrModal = id => {
    let qrData = $.grep(jsonData, x => x.id == id)[0];
    delete qrData.created_by;
    delete qrData.created_at;
    delete qrData.active;
    // Initialize modal
    let modalQr = `
    <div class="modal fade" id="modalQr" tabindex="-1" role="dialog" aria-labelledby="modalQrTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <form id="input-form">
            <div class="modal-header">
              <h5 class="modal-title">QR Data ${id}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div id="qrcode" class="m-auto" style="width: 256px;"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" onClick="saveImg('${id}')"><i class="fas fa-save mr-2"></i>Simpan</button>     
            </div>
          </form>
        </div>
      </div>
    </div>
    `;

    $('.modal-container').html(modalQr);
    $('#modalQr').modal('show');

    // Encode the JSON object as a string
    const jsonString = JSON.stringify(qrData);

    // Create the QR code using qrcodejs
    const qrcode = new QRCode(document.getElementById('qrcode'), {
      text: jsonString,
      width: 256,
      height: 256,
      colorDark: '#000000',
      colorLight: '#ffffff',
      correctLevel: QRCode.CorrectLevel.H
    });
  }

  const addModal = () => {
    // Initialize modal
    let modalAdd = `
    <div class="modal fade" id="modalTambahSettings" tabindex="-1" role="dialog" aria-labelledby="modalTambahSettingsTitle" aria-hidden="true">
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
    selects.forEach(table => {
      modalAdd += `
      <div class="md-form mb-4">
        <select class="browser-default custom-select" id="${table}-input" required>
          <option value="" selected hidden>--- PILIH ${table.toUpperCase()} ---</option>
      `;

      selectValues[table].forEach(value => {
        modalAdd += `<option value="${value.id}">${value.name}</option>`;
      })

      modalAdd += `
        </select>
      </div>
      `;
    });

    for (let column in radios) {
      modalAdd += `
      <div class="mb-4">
        <p>${column}
      `;

      radios[column].forEach(value => {
        modalAdd += `
        <div class="custom-control custom-radio custom-control-inline">
          <input type="radio" class="custom-control-input" id="${value}" name="${column}" required>
          <label class="custom-control-label" for="${value}">${value}</label>
        </div>
        `;
      });

      modalAdd += `</div>`;
    }

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
    $('#modalTambahSettings').modal('show');


    // Add event listener for save button
    $('#input-form').submit((event) => {
      event.preventDefault();

      // Get inputs
      let inputs = {};
      selects.forEach(key => {
        inputs[key.slice(0, -1).concat('_id')] = $(`#${key}-input`).val();
      });

      for (let column in radios) {
        inputs[column] = $(`input[name=${column}]:checked`).attr('id');
      }

      inputs['created_by'] = '<?php echo $_SESSION['username']; ?>';

      // Get the form data
      const formData = {
        'table': table,
        'inputs': inputs
      };

      // Send the AJAX request
      $.ajax({
        type: 'POST',
        url: './api/settings_add.php',
        data: formData,
        success: response => {
          response = JSON.parse(response);
          $('#modalTambahSettings').modal('hide');
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

  const editModal = id => {
    // Send the AJAX request
    $.ajax({
      type: 'POST',
      url: './api/settings_get_one.php',
      data: {
        'id': id,
        'table': table
      },
      success: response => {
        response = JSON.parse(response);
        if (response.success) {
          let modalEdit = `
          <div class="modal fade" id="modalEditSettings" tabindex="-1" role="dialog" aria-labelledby="modalEditSettingsTitle" aria-hidden="true">
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
          selects.forEach(table => {
            modalEdit += `
            <div class="md-form mb-4">
              <select class="browser-default custom-select" id="${table}-input" required>
                <option value="" selected hidden>--- PILIH ${table.toUpperCase()} ---</option>
            `;

            selectValues[table].forEach(value => {
              if (response.data[table.slice(0, -1).concat('_id')] == value.id) {
                modalEdit += `<option value="${value.id}" selected>${value.name}</option>`;
              } else {
                modalEdit += `<option value="${value.id}">${value.name}</option>`;
              }
            })

            modalEdit += `
              </select>
            </div>
            `;
          });

          for (let column in radios) {
            modalEdit += `
            <div class="mb-4">
              <p>${column}
            `;

            radios[column].forEach(value => {
              if (response.data[column] == value) {
                modalEdit += `
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input" id="${value}" name="${column}" checked required>
                  <label class="custom-control-label" for="${value}">${value}</label>
                </div>
                `;
              } else {
                modalEdit += `
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input" id="${value}" name="${column}" required>
                  <label class="custom-control-label" for="${value}">${value}</label>
                </div>
                `;
              }
            });

            modalEdit += `</div>`;
          }
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
          $('#modalEditSettings').modal('show');

          $('#edit-form').submit(event => {
            event.preventDefault();

            // Get inputs
            let inputs = {};
            selects.forEach(key => {
              inputs[key.slice(0, -1).concat('_id')] = $(`#${key}-input`).val();
            });

            for (let column in radios) {
              inputs[column] = $(`input[name=${column}]:checked`).attr('id');
            }

            // Get the form data
            const formData = {
              'id': id,
              'table': table,
              'inputs': inputs
            };

            // Send the AJAX request
            $.ajax({
              type: 'POST',
              url: './api/settings_edit.php',
              data: formData,
              success: response => {
                response = JSON.parse(response);
                $('#modalEditSettings').modal('hide');
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
          <div class="modal fade" id="modalDeleteSettings" tabindex="-1" role="dialog" aria-labelledby="modalDeleteSettingsTitle" aria-hidden="true">
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
    $('.modal-container').html(modalDelete);
    $('#modalDeleteSettings').modal('show');

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
        url: './api/settings_delete.php',
        data: formData,
        success: response => {
          response = JSON.parse(response);
          $('#modalDeleteSettings').modal('hide');
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

  const saveImg = id => {
    // Get the canvas element from the QR code
    const canvas = document.getElementById('qrcode').getElementsByTagName('canvas')[0];

    // Convert the canvas to a data URL
    const dataURL = canvas.toDataURL('image/png');

    // Create a link element with the data URL as the href attribute and the download attribute set to the file name
    const link = document.createElement('a');
    link.href = dataURL;
    link.download = `qr-code-${id}.png`;

    // Click the link to download the image
    link.click();
  }
</script>

</html>