<?php include './redirect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<?php
include './head.php';
if (isset($_GET['kode'])) {
  $kode = $_GET['kode'];
?>
  <script src="./js/constants.js"></script>
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
    let formInputs = {
      'provinsi': {
        'type': 'select',
        'data': Object.keys(alamats),
        'required': true
      },
      'kota': {
        'type': 'select',
        'data': [],
        'required': true,
        'disabled': true
      },
      'alamat': {
        'type': 'text',
        'required': true
      },
      'kode_pos': {
        'type': 'number',
      },
      'nama_purchasing': {
        'type': 'text',
        'required': true
      },
      'kontak_purchasing': {
        'type': 'number',
        'required': true
      },
      'email_purchasing': {
        'type': 'email',
      },
      'nama_finance': {
        'type': 'text',
        'required': true
      },
      'kontak_finance': {
        'type': 'number',
        'required': true
      },
      'email_finance': {
        'type': 'email',
      },
      'harga_jual': {
        'type': 'number',
      },
      'top': {
        'type': 'number',
      },
    };
    let pelanggan_id;

    $(document).ready(function() {
      loadPage();

      $('.alert').alert();
    });

    // Function to load page
    const loadPage = () => {
      // Send the AJAX request
      $.ajax({
        type: 'POST',
        url: './api/detail_pelanggan_get.php',
        data: {
          'kode': '<?php echo $kode; ?>'
        },
        success: (response) => {
          console.log(response)
          response = JSON.parse(response);
          pelanggan_id = response.id;
          let html;

          // Add heading
          $('#heading').html(`
          <h1><?php echo $kode; ?></h1>
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
              if (key != 'id' && key != 'pelanggan_id') {
                head += `<th>${key.replace(/_/g,' ').toUpperCase()}</th>`;
                foot += `<th>${key.replace(/_/g,' ').toUpperCase()}</th>`;
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
                if (key != 'id' && key != 'pelanggan_id') {
                  if (datum[key] != null) {
                    row += `<td>${datum[key]}</td>`;
                  } else {
                    row += `<td>-</td>`;
                  }
                }
              });
              row += `
            <td>
              <button type="button" class="btn btn-secondary btn-sm m-0 px-3 edit-button" onClick="editModal('${datum['id']}')"><i class="fas fa-edit"></i></button>
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
              <h5 class="modal-title">Tambah Detail Pelanggan</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
    `;

      for (const key in formInputs) {
        if (formInputs[key]['type'] == 'text') {
          modalAdd += `
        <div class="mb-4">
          <label for="${key}-input">${key.replace(/_/g,' ')}</label> ${formInputs[key]['required']?'<span class="red-text">*</span>':''}
          <input type="text" id="${key}-input" name="${key}" class="form-control validate" ${formInputs[key]['required']?'required':''} ${formInputs[key]['disabled']?'disabled':''} ${formInputs[key]['maxlength']?'maxlength='+formInputs[key]['maxlength']:''} ${formInputs[key]['minlength']?'minlength='+formInputs[key]['minlength']:''}>
        </div>
        `;
        } else if (formInputs[key]['type'] == 'number') {
          modalAdd += `
        <div class="mb-4">
          <label for="${key}-input">${key.replace(/_/g,' ')}</label> ${formInputs[key]['required']?'<span class="red-text">*</span>':''}
          <input type="number" id="${key}-input" name="${key}" class="form-control validate" ${formInputs[key]['required']?'required':''} ${formInputs[key]['disabled']?'disabled':''} ${formInputs[key]['maxlength']?'maxlength='+formInputs[key]['maxlength']:''} ${formInputs[key]['minlength']?'minlength='+formInputs[key]['minlength']:''}>
        </div>
        `;
        } else if (formInputs[key]['type'] == 'email') {
          modalAdd += `
        <div class="mb-4">
          <label for="${key}-input">${key.replace(/_/g,' ')}</label> ${formInputs[key]['required']?'<span class="red-text">*</span>':''}
          <input type="email" id="${key}-input" name="${key}" class="form-control validate" ${formInputs[key]['required']?'required':''} ${formInputs[key]['disabled']?'disabled':''}>
        </div>
        `;
        } else if (formInputs[key]['type'] == 'select') {
          modalAdd += `
        <div class="mb-4">
          <label for="${key}-input">${key.replace(/_/g,' ')}</label> ${formInputs[key]['required']?'<span class="red-text">*</span>':''}
          <select class="browser-default custom-select" id="${key}-input" ${formInputs[key]['required']?'required':''} ${formInputs[key]['disabled']?'disabled':''}>
          <option value="" selected hidden>--- PILIH ${key.replace(/_/g,' ').toUpperCase()} ---</option>
        `;
          if (Array.isArray(formInputs[key]['data'])) {
            if (typeof formInputs[key]['data'][0] == 'object') {
              formInputs[key]['data'].forEach(datum => {
                modalAdd += `<option value="${datum.id}">${datum.nama}</option>`;
              });
            } else {
              formInputs[key]['data'].forEach(datum => {
                modalAdd += `<option value="${datum}">${datum}</option>`;
              });
            }
          }
          modalAdd += `
          </select>
        </div>
        `;
        } else if (formInputs[key]['type'] == 'radio') {
          modalAdd += `
          <div class="mb-4">
            <p>${key.replace(/_/g,' ')} ${formInputs[key]['required']?'<span class="red-text">*</span>':''}</p>
          `;

          if (Array.isArray(formInputs[key]['data'])) {
            formInputs[key]['data'].forEach(datum => {
              modalAdd += `
            <div class="custom-control custom-radio custom-control-inline">
              <input type="radio" class="custom-control-input" id="${datum}" name="${key}" ${formInputs[key]['required']?'required':''} ${formInputs[key]['disabled']?'disabled':''}>
              <label class="custom-control-label" for="${datum}">${datum}</label>
            </div>
            `;
            });
          }
          modalAdd += `</div>`;
        }
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
      $('#modalTambah').modal('show');

      $('#ktp-input').keyup(() => {
        $('#npwp-input').val($('#ktp-input').val());
      })

      $('#provinsi-input').change(() => {
        const selKota = $('#kota-input');
        let provinsi = $('#provinsi-input').find(':selected').val();
        console.log(provinsi)
        selKota.empty();
        selKota.append('<option value="" selected hidden>--- PILIH KOTA ---</option>');
        alamats[provinsi].forEach(kota => {
          selKota.append(`<option value="${kota}">${kota}</option>`);
        });
        selKota.removeAttr('disabled');
      });


      // Add event listener for save button
      $('#input-form').submit((event) => {
        event.preventDefault();

        // Get inputs
        let inputs = {};
        for (const key in formInputs) {
          if (key == 'kode') {
            inputs[key] = $(`#${key}-input`).val().toUpperCase();
          } else if (formInputs[key]['type'] == 'radio') {
            inputs[key] = $(`input[name=${key}]:checked`).attr('id');
          } else {
            inputs[key] = $(`#${key}-input`).val();
          }
        }
        inputs['pelanggan_id'] = pelanggan_id;

        console.log(inputs)

        // Get the form data
        const formData = {
          'inputs': inputs
        };

        // Send the AJAX request
        $.ajax({
          type: 'POST',
          url: './api/detail_pelanggan_add.php',
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

    const editModal = (id) => {
      // Send the AJAX request
      $.ajax({
        type: 'POST',
        url: './api/detail_pelanggan_get_one.php',
        data: {
          'id': id,
        },
        success: response => {
          response = JSON.parse(response);
          if (response.success) {
            formInputs['kota']['data'] = alamats[response.data['provinsi']];

            let modalEdit = `
          <div class="modal fade" id="modalUbah" tabindex="-1" role="dialog" aria-labelledby="modalUbahTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <form id="edit-form">
                  <div class="modal-header">
                    <h5 class="modal-title">Ubah Detail Pelanggan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
          `;

            for (const key in formInputs) {
              if (formInputs[key]['type'] == 'text') {
                modalEdit += `
              <div class="mb-4">
                <label for="${key}-input">${key.replace(/_/g,' ')}</label> ${formInputs[key]['required']?'<span class="red-text">*</span>':''}
                <input type="text" id="${key}-input" name="${key}" class="form-control validate" ${formInputs[key]['required']?'required':''} value="${response.data[key]}" ${formInputs[key]['maxlength']?'maxlength='+formInputs[key]['maxlength']:''} ${formInputs[key]['minlength']?'minlength='+formInputs[key]['minlength']:''}>
              </div>
              `;
              } else if (formInputs[key]['type'] == 'number') {
                modalEdit += `
              <div class="mb-4">
                <label for="${key}-input">${key.replace(/_/g,' ')}</label> ${formInputs[key]['required']?'<span class="red-text">*</span>':''}
                <input type="number" id="${key}-input" name="${key}" class="form-control validate" ${formInputs[key]['required']?'required':''} value="${response.data[key]}" ${formInputs[key]['maxlength']?'maxlength='+formInputs[key]['maxlength']:''} ${formInputs[key]['minlength']?'minlength='+formInputs[key]['minlength']:''}>
              </div>
              `;
              } else if (formInputs[key]['type'] == 'email') {
                modalEdit += `
              <div class="mb-4">
                <label for="${key}-input">${key.replace(/_/g,' ')}</label> ${formInputs[key]['required']?'<span class="red-text">*</span>':''}
                <input type="email" id="${key}-input" name="${key}" class="form-control validate" ${formInputs[key]['required']?'required':''} value="${response.data[key]}">
              </div>
              `;
              } else if (formInputs[key]['type'] == 'select') {
                modalEdit += `
              <div class="mb-4">
                <label for="${key}-input">${key.replace(/_/g,' ')}</label> ${formInputs[key]['required']?'<span class="red-text">*</span>':''}
                <select class="browser-default custom-select" id="${key}-input" ${formInputs[key]['required']?'required':''} >
              `;
                if (Array.isArray(formInputs[key]['data'])) {
                  if (typeof formInputs[key]['data'][0] == 'object') {
                    formInputs[key]['data'].forEach(datum => {
                      if (response.data[key] == datum) {
                        modalEdit += `<option value="${datum.id}" selected>${datum.nama}</option>`;
                      } else {
                        modalEdit += `<option value="${datum.id}">${datum.nama}</option>`;
                      }
                    });
                  } else {
                    formInputs[key]['data'].forEach(datum => {
                      modalEdit += `<option value="${datum}" ${response.data[key]==datum? 'selected':''}>${datum}</option>`;
                      if (!formInputs[key]['required']) {
                        if (response.data[key]) {
                          modalEdit += `<option value="" selected>Tidak ada</option>`;
                        } else {
                          modalEdit += `<option value="">Tidak ada</option>`;
                        }
                      }
                    });
                  }
                }
                modalEdit += `
                </select>
              </div>
              `;
              } else if (formInputs[key]['type'] == 'radio') {
                modalEdit += `
              <div class="mb-4">
                <p>${key.replace(/_/g,' ')} ${formInputs[key]['required']?'<span class="red-text">*</span>':''}</p>
              `;

                if (Array.isArray(formInputs[key]['data'])) {
                  formInputs[key]['data'].forEach(datum => {
                    modalEdit += `
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" class="custom-control-input" id="${datum}" name="${key}" ${formInputs[key]['required']?'required':''}  ${response.data[key]==datum?'checked':''}>
                    <label class="custom-control-label" for="${datum}">${datum}</label>
                  </div>
                  `;
                  });
                }
                modalEdit += `</div>`;
              }
            }

            // Append modal
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
            $('#modalUbah').modal('show');

            $('#provinsi-input').change(() => {
              const selKota = $('#kota-input');
              let provinsi = $('#provinsi-input').find(':selected').val();
              console.log(provinsi)
              selKota.empty();
              selKota.append('<option value="" selected hidden>--- PILIH KOTA ---</option>');
              alamats[provinsi].forEach(kota => {
                selKota.append(`<option value="${kota}">${kota}</option>`);
              });
              selKota.removeAttr('disabled');
            });

            $('#edit-form').submit(event => {
              event.preventDefault();

              // Get inputs
              let inputs = {};
              for (const key in formInputs) {
                if (key == 'kode') {
                  inputs[key] = $(`#${key}-input`).val().toUpperCase();
                } else if (formInputs[key]['type'] == 'radio') {
                  inputs[key] = $(`input[name=${key}]:checked`).attr('id');
                } else {
                  inputs[key] = $(`#${key}-input`).val();
                }
              }
              console.log(inputs)

              // Get the form data
              const formData = {
                'id': id,
                'inputs': inputs
              };

              // Send the AJAX request
              $.ajax({
                type: 'POST',
                url: './api/detail_pelanggan_edit.php',
                data: formData,
                success: response => {
                  console.log(response)
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
<?php } ?>

</html>