<?php include './redirect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<?php
include './head.php';
?>
<style>
  #kode-input {
    text-transform: uppercase;
  }
</style>

<body>
  <?php include './navbar.php'; ?>
  <div class="container py-2">
    <div class="alert-container"></div>
    <h1 class="h1-responsive pb-2">PIPELINE HISTORY</h1>
    <div class="param-container py-3">
      <div class="py-2">
        <h6>tanggal awal</h6>
        <input type="date" id="param-awal" class="form-control param" value="<?php echo date('Y-m-d', strtotime('-1 month')); ?>">
      </div>
      <div class="py-2">
        <h6>tanggal akhir</h6>
        <input type="date" id="param-akhir" class="form-control param" value="<?php echo date('Y-m-d'); ?>">
      </div>
      <div class="py-2">
        <h6>pelanggan</h6>
        <select class="param" id="param-pelanggan">
        </select>
      </div>
    </div>
    <div class="table-container"></div>
    <div class="modal-container"></div>
    <button type="button" class="btn btn-primary px-3 fab" aria-hidden="true" onClick="addModal()"><i class="fas fa-plus fa-2x"></i></button>
  </div>
</body>
<script type="text/javascript">
  let formInputs = {
    'pelanggan_id': {
      'type': 'select',
      'data': [],
      'required': true
    },
    'detail_pelanggan_id': {
      'type': 'select',
      'data': [],
      'required': true,
      'disabled': true
    },
    'detail_pipeline_marketings': {
      'type': 'detail_pipeline_marketings',
      'data': []
    },
    'tanggal_survey': {
      'type': 'date',
    },
    'tanggal_instalasi': {
      'type': 'date',
      'required': true
    },
    'status_pelanggan': {
      'type': 'radio',
      'data': statusPelanggans,
      'required': true
    },
  };

  const tanggal = '<?php echo date('Y-m-d'); ?>'

  $(document).ready(async()=> {
    await loadPelanggans();

    $('.param').change(async () => {
      await loadPage();
    });

    $('#param-pelanggan').select2({
      theme: 'bootstrap4',
      width: 'style',
      placeholder: 'PILIH SALAH SATU'
    });

    $('.alert').alert();
  });

  const loadPelanggans = async () => {
    // Send the AJAX request
    await $.ajax({
      type: 'POST',
      url: './api/pelanggan_get.php',
      success: async response => {
        response = JSON.parse(response);
        if (response.success) {
          formInputs['pelanggan_id']['data'] = response.data;
        }
        let first = true;
        response.data.forEach(datum => {
          $('#param-pelanggan').append(`<option value="${datum.id}" ${first?'selected':''}>${datum.badan_usaha} ${datum.nama_perusahaan} - ${datum.kota}</option>}`);
          first = false;
        });
        await loadPage();
      },
      error: (jqXHR, textStatus, errorThrown) => {
        console.log(textStatus, errorThrown);
      }
    });
  }

  const loadBarangs = async (id) => {
    // Send the AJAX request
    await $.ajax({
      type: 'POST',
      url: './api/barang_get.php',
      data: {
        'detail_pelanggan_id': id
      },
      success: response => {
        response = JSON.parse(response);
        if (response.success) {
          formInputs['detail_pipeline_marketings']['data'] = response.data;
        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        console.log(textStatus, errorThrown);
      }
    });
  }

  // Function to load page
  const loadPage = async () => {
    // Send the AJAX request
    await $.ajax({
      type: 'POST',
      url: './api/pipeline_marketing_get.php',
      data: {
        'awal': $('#param-awal').val(),
        'akhir': $('#param-akhir').val(),
        'pelanggan': $('#param-pelanggan').val(),
      },
      success: (response) => {
        response = JSON.parse(response);
        let html;

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
            if (key != 'id' && key != 'kode_pelanggan') {
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
              if (key != 'id' && key != 'kode_pelanggan') {
                if (datum[key] != null) {
                  row += `<td>${datum[key]}</td>`;
                } else {
                  row += `<td>-</td>`;
                }
              }
            });
            row += '<td>';
            if (tanggal == datum['tanggal_dibuat']) {
              row += `<button type="button" class="btn btn-secondary btn-sm m-0 px-3 edit-button" onClick="editModal('${datum['id']}','${datum['kode_pelanggan']}')"><i class="fas fa-edit"></i></button>`;
            }
            row += '</td>'
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

  let counter = 0;

  const addModal = () => {
    // Initialize modal
    let modalAdd = `
    <div class="modal fade" id="modalTambah" tabindex="-1" data-focus="false" role="dialog" aria-labelledby="modalTambahTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <form id="input-form">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Pipeline Marketing</h5>
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
      } else if (formInputs[key]['type'] == 'date') {
        modalAdd += `
        <div class="mb-4">
          <label for="${key}-input">${key.replace(/_/g,' ')}</label> ${formInputs[key]['required']?'<span class="red-text">*</span>':''}
          <input type="date" id="${key}-input" name="${key}" class="form-control validate" ${formInputs[key]['required']?'required':''} ${formInputs[key]['disabled']?'disabled':''}>
        </div>
        `;
      } else if (formInputs[key]['type'] == 'detail_pipeline_marketings') {
        modalAdd += `
        <div class="mb-4">
          <label>daftar barang</label><button class="btn btn-primary px-2 py-1" onClick="tambahBarang(event)"><i class="fas fa-plus"></i></button>
          <div class="table-responsive">
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th class="th-lg">Barang</th>
                  <th>Kuantitas</th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="tbBarang">
              </tbody>
            </table>
          </div>
        </div>
        `;
      } else if (formInputs[key]['type'] == 'select') {
        modalAdd += `
        <div class="mb-4">
          <label for="${key}-input">${key.replace(/_/g,' ')}</label> ${formInputs[key]['required']?'<span class="red-text">*</span>':''}
          <select class="browser-default custom-select modal-select" name="${key}" id="${key}-input" ${formInputs[key]['required']?'required':''} ${formInputs[key]['disabled']?'disabled':''}>
          <option></option>
        `;
        if (Array.isArray(formInputs[key]['data'])) {
          if (typeof formInputs[key]['data'][0] == 'object') {
            formInputs[key]['data'].forEach(datum => {
              modalAdd += `<option value="${datum.kode}">${datum.nama_perusahaan}</option>`;
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
              <input type="radio" class="custom-control-input" id="${datum}" name="${key}" value="${datum}" ${formInputs[key]['required']?'required':''} ${formInputs[key]['disabled']?'disabled':''}>
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

    $('.modal-select').select2({
      theme: 'bootstrap4',
      width: 'element',
      placeholder: 'PILIH SALAH SATU'
    });

    $('#pelanggan_id-input').change(() => {
      const selDetail = $('#detail_pelanggan_id-input');
      let pelanggan = $('#pelanggan_id-input').find(':selected').val();
      selDetail.empty();
      selDetail.append('<option></option>');
      $.ajax({
        type: 'POST',
        url: './api/detail_pelanggan_get.php',
        data: {
          'kode': pelanggan
        },
        success: response => {
          response = JSON.parse(response);
          response.data.forEach(datum => {
            selDetail.append(`<option value="${datum.id}">${datum.alamat}</option>`);
          });
        },
        error: (jqXHR, textStatus, errorThrown) => {
          console.log(textStatus, errorThrown);
        }
      });
      selDetail.removeAttr('disabled');
    });

    $('#detail_pelanggan_id-input').change(async () => {
      let detail = $('#detail_pelanggan_id-input').find(':selected').val();
      await loadBarangs(detail);
      $.ajax({
        type: 'POST',
        url: './api/pipeline_marketing_get_one.php',
        data: {
          'detail_pelanggan_id': detail
        },
        success: response => {
          console.log(response);
          response = JSON.parse(response);

          for (const key in formInputs) {
            if (key == 'detail_pipeline_marketings') {
              $('#tbBarang').html('');
              response.barangs.forEach(barang => {
                let table = `
                    <tr id="row${counter}">
                      <td>
                        <select class="browser-default custom-select modal-select" name="detail_pipeline_marketings[${counter}][barang_id]" required>
                          <option></option>
                    `;
                formInputs['detail_pipeline_marketings']['data'].forEach(datum => {
                  table += `<option value="${datum.id}" ${datum.id==barang.barang_id?'selected':''}>${datum.nama}</option>`;
                })
                table += `
                        </select>
                      </td>
                      <td><input type="number" name="detail_pipeline_marketings[${counter}][kuantitas]" class="form-control validate" value="${barang.kuantitas}" required></td>
                      <td><button class="btn btn-danger px-2 py-1" onClick="hapusBarang(event, 'row${counter}')"><i class="fas fa-minus"></i></button></td>
                    </tr>
                    `;
                $()
                counter++;
                $('#tbBarang').append(table);

                $('.modal-select').select2({
                  theme: 'bootstrap4',
                  width: 'element',
                  placeholder: 'PILIH SALAH SATU'
                });
              })
            } else if (key == 'status_pelanggan') {
              $('input:radio').prop('checked', false);
              $(`#${response.data['status_pelanggan']}`).prop('checked', true);
            } else if (formInputs[key]['type'] != 'select') {
              $(`#${key}-input`).val(response.data[key]);
            }
          }
        },
        error: (jqXHR, textStatus, errorThrown) => {
          console.log(textStatus, errorThrown);
        }
      });
    });


    // Add event listener for save button
    $('#input-form').submit((event) => {
      event.preventDefault();

      // Get the form data
      const form = document.getElementById('input-form')
      const formData = new FormData(form);
      formData.delete('pelanggan_id');
      formData.append('marketing_id', <?php echo $_SESSION['id']; ?>);
      const inputs = form.querySelectorAll('input, textarea, select');
      inputs.forEach(input => {
        if (input.type === 'checkbox' || input.type === 'radio') {
          if (!input.checked) {
            formData.set(input.name, '');
          }
        } else if (input.value === '') {
          formData.set(input.name, '');
        }
      });
      formData.set('status_pelanggan', document.querySelector('input[name="status_pelanggan"]:checked').value);

      // Send the AJAX request
      $.ajax({
        type: 'POST',
        url: './api/pipeline_marketing_add.php',
        data: formData,
        contentType: false,
        processData: false,
        success: async response => {
          console.log(response);
          response = JSON.parse(response);
          $('#modalTambah').modal('hide');
          $(".modal-backdrop").remove();
          if (response.success) {
            showAlert('success', response.message);
          } else {
            showAlert('danger', response.message);
          }
          await loadPage();
        },
        error: (jqXHR, textStatus, errorThrown) => {
          console.log(textStatus, errorThrown);
        }
      });
    });
  }

  const tambahBarang = (e) => {
    e.preventDefault();
    let table = `
      <tr id="row${counter}">
        <td>
          <select class="browser-default custom-select modal-select" name="detail_pipeline_marketings[${counter}][barang_id]" required>
            <option></option>
      `;
    formInputs['detail_pipeline_marketings']['data'].forEach(datum => {
      table += `<option value="${datum.id}">${datum.nama}</option>`;
    })
    table += `
          </select>
        </td>
        <td><input type="number" name="detail_pipeline_marketings[${counter}][kuantitas]" class="form-control validate" required></td>
        <td><button class="btn btn-danger px-2 py-1" onClick="hapusBarang(event, 'row${counter}')"><i class="fas fa-minus"></i></button></td>
      </tr>
      `;
    $('#tbBarang').append(table);

    $('.modal-select').select2({
      theme: 'bootstrap4',
      width: 'element',
      placeholder: 'PILIH SALAH SATU'
    });

    counter++;
  }

  const hapusBarang = (e, id) => {
    e.preventDefault()
    $(`#${id}`).remove()
  }

  const editModal = (id, pelanggan) => {
    // Send the AJAX request
    $.ajax({
      type: 'POST',
      url: './api/pipeline_marketing_get_one.php',
      data: {
        'id': id,
      },
      success: async response => {
        response = JSON.parse(response);
        if (response.success) {
          await loadBarangs(response.data.detail_pelanggan_id)
          $.ajax({
            type: 'POST',
            url: './api/detail_pelanggan_get.php',
            data: {
              'kode': pelanggan
            },
            success: response1 => {
              response1 = JSON.parse(response1);

              let modalEdit = `
          <div class="modal fade" id="modalUbah" tabindex="-1" data-focus="false" role="dialog" aria-labelledby="modalUbahTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <form id="edit-form">
                  <div class="modal-header">
                    <h5 class="modal-title">Ubah Pipeline Marketing</h5>
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
                } else if (formInputs[key]['type'] == 'date') {
                  modalEdit += `
              <div class="mb-4">
                <label for="${key}-input">${key.replace(/_/g,' ')}</label> ${formInputs[key]['required']?'<span class="red-text">*</span>':''}
                <input type="date" id="${key}-input" name="${key}" class="form-control validate" ${formInputs[key]['required']?'required':''} value="${response.data[key]}">
              </div>
              `;
                } else if (formInputs[key]['type'] == 'detail_pipeline_marketings') {
                  modalEdit += `
                  <div class="mb-4">
                    <label>daftar barang</label><button class="btn btn-primary px-2 py-1" onClick="tambahBarang(event)"><i class="fas fa-plus"></i></button>
                    <div class="table-responsive">
                      <table class="table table-bordered table-sm">
                        <thead>
                          <tr>
                            <th class="th-lg">Barang</th>
                            <th>Kuantitas</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody id="tbBarang">
                  `;
                  response.barangs.forEach(barang => {
                    let table = `
                    <tr id="row${counter}">
                      <td>
                        <select class="browser-default custom-select modal-select" name="detail_pipeline_marketings[${counter}][barang_id]" required>
                          <option></option>
                    `;
                    formInputs['detail_pipeline_marketings']['data'].forEach(datum => {
                      table += `<option value="${datum.id}" ${datum.id==barang.barang_id?'selected':''}>${datum.nama}</option>`;
                    })
                    table += `
                        </select>
                      </td>
                      <td><input type="number" name="detail_pipeline_marketings[${counter}][kuantitas]" class="form-control validate" value="${barang.kuantitas}" required></td>
                      <td><button class="btn btn-danger px-2 py-1" onClick="hapusBarang(event, 'row${counter}')"><i class="fas fa-minus"></i></button></td>
                    </tr>
                    `;
                    modalEdit += table;
                    counter++;

                  })
                  modalEdit += `
                        </tbody>
                      </table>
                    </div>
                  </div>
                  `;
                } else if (formInputs[key]['type'] == 'select') {
                  modalEdit += `
              <div class="mb-4">
                <label for="${key}-input">${key.replace(/_/g,' ')}</label> ${formInputs[key]['required']?'<span class="red-text">*</span>':''}
                <select class="browser-default custom-select modal-select" name="${key}" id="${key}-input" ${formInputs[key]['required']?'required':''} >
              `;
                  if (Array.isArray(formInputs[key]['data'])) {
                    if (typeof formInputs[key]['data'][0] == 'object') {
                      formInputs[key]['data'].forEach(datum => {
                        if (response.data[key] == datum) {
                          modalEdit += `<option value="${datum.kode}" selected>${datum.nama_perusahaan}</option>`;
                        } else {
                          modalEdit += `<option value="${datum.kode}">${datum.nama_perusahaan}</option>`;
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
                    <input type="radio" class="custom-control-input" id="${datum}" name="${key}" value="${datum}" ${formInputs[key]['required']?'required':''}  ${response.data[key]==datum?'checked':''}>
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

              $('.modal-select').select2({
                theme: 'bootstrap4',
                width: 'element',
                placeholder: 'PILIH SALAH SATU'
              });

              const selDetail = $('#detail_pelanggan_id-input');
              selDetail.empty();
              response1.data.forEach(datum => {
                if (response.data['detail_pelanggan_id'] == datum.id) {
                  selDetail.append(`<option value="${datum.id}" selected>${datum.alamat}</option>`);
                } else {
                  selDetail.append(`<option value="${datum.id}">${datum.alamat}</option>`);

                }
              });

              $('#pelanggan_id-input').change(() => {
                const selDetail = $('#detail_pelanggan_id-input');
                let pelanggan = $('#pelanggan_id-input').find(':selected').val();
                selDetail.empty();
                selDetail.append('<option></option>');
                $.ajax({
                  type: 'POST',
                  url: './api/detail_pelanggan_get.php',
                  data: {
                    'kode': pelanggan
                  },
                  success: response => {
                    console.log(response);
                    response = JSON.parse(response);
                    response.data.forEach(datum => {
                      selDetail.append(`<option value="${datum.id}">${datum.alamat}</option>`);
                    });
                  },
                  error: (jqXHR, textStatus, errorThrown) => {
                    console.log(textStatus, errorThrown);
                  }
                });
                selDetail.removeAttr('disabled');
              });

              $('#edit-form').submit(event => {
                event.preventDefault();

                // Get the form data
                const form = document.getElementById('edit-form')
                const formData = new FormData(form);
                formData.delete('pelanggan_id');
                formData.append('id', response.data.id);
                const inputs = form.querySelectorAll('input, textarea, select');
                inputs.forEach(input => {
                  if (input.type === 'checkbox' || input.type === 'radio') {
                    if (!input.checked) {
                      formData.set(input.name, '');
                    }
                  } else if (input.value === '') {
                    formData.set(input.name, '');
                  }
                });
                formData.set('status_pelanggan', document.querySelector('input[name="status_pelanggan"]:checked').value);

                // Send the AJAX request
                $.ajax({
                  type: 'POST',
                  url: './api/pipeline_marketing_edit.php',
                  data: formData,
                  contentType: false,
                  processData: false,
                  success: async response => {
                    console.log(response)
                    response = JSON.parse(response);
                    $('#modalUbah').modal('hide');
                    $(".modal-backdrop").remove();
                    if (response.success) {
                      showAlert('success', response.message);
                    } else {
                      showAlert('danger', response.message);
                    }
                    await loadPage();
                  },
                  error: (jqXHR, textStatus, errorThrown) => {
                    console.log(textStatus, errorThrown);
                  }
                });
              })
            },
            error: (jqXHR, textStatus, errorThrown) => {
              console.log(textStatus, errorThrown);
            }
          });
        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        console.log(textStatus, errorThrown);
      }
    });
  }
</script>

</html>