<?php include './redirect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<?php
include './head.php';
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
    'detail_penawaran_barangs': {
      'type': 'detail_penawaran_barangs',
      'data': []
    },
    'diskon': {
      'type': 'number',
    },
    'biaya_tambahan': {
      'type': 'number',
    },
  };

  let ppn = {};

  $(document).ready(function() {
    loadPelanggans();
    loadBarangs();
    loadPpn();

    loadPage();

    $('.alert').alert();
  });

  const loadPelanggans = () => {
    // Send the AJAX request
    $.ajax({
      type: 'POST',
      url: './api/pelanggan_get.php',
      success: response => {
        response = JSON.parse(response);
        if (response.success) {
          formInputs['pelanggan_id']['data'] = response.data;
        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        console.log(textStatus, errorThrown);
      }
    });
  }

  const loadBarangs = () => {
    // Send the AJAX request
    $.ajax({
      type: 'POST',
      url: './api/barang_get.php',
      success: response => {
        response = JSON.parse(response);
        if (response.success) {
          formInputs['detail_penawaran_barangs']['data'] = response.data;
        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        console.log(textStatus, errorThrown);
      }
    });
  }

  const loadPpn = () => {
    // Send the AJAX request
    $.ajax({
      type: 'POST',
      url: './api/ppn_get.php',
      success: response => {
        response = JSON.parse(response);
        if (response.success) {
          ppn = response.data[0];
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
      url: './api/penawaran_barang_get.php',
      success: (response) => {
        response = JSON.parse(response);
        let html;

        // Add heading
        $('#heading').html(`
          <h1>PENAWARAN BARANG</h1>
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
            if (key != 'id') {
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
              if (key != 'id') {
                if (datum[key] != null) {
                  row += `<td>${datum[key]}</td>`;
                } else {
                  row += `<td>-</td>`;
                }
              }
            });
            row += `
            <td>
              <button type="button" class="btn btn-secondary btn-sm m-0 px-3 edit-button" onClick="editModal('${datum['kode']}','${datum['pelanggan']}')"><i class="fas fa-edit"></i></button>
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
      <div class="modal-dialog modal-dialog-centered modal-lg modal-lg" role="document">
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
      } else if (formInputs[key]['type'] == 'detail_penawaran_barangs') {
        modalAdd += `
        <div class="mb-4">
          <label>daftar barang</label><button class="btn btn-primary px-2 py-1" onClick="tambahBarang(event)"><i class="fas fa-plus"></i></button>
          <div class="table-responsive">
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th>Barang</th>
                  <th>Harga Jual</th>
                  <th>Kuantitas</th>
                  <th>PPN</th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="tbBarang">
              </tbody>
            </table>
          </div>
        </div>
        `;
      } else if (formInputs[key]['type'] == 'checkbox') {
        modalAdd += `
        <div class="mb-4">
          <p>${key.replace(/_/g,' ')} ${formInputs[key]['required']?'<span class="red-text">*</span>':''}</p>
        `;
        if (Array.isArray(formInputs[key]['data'])) {
          if (typeof formInputs[key]['data'][0] == 'object') {
            formInputs[key]['data'].forEach(datum => {
              modalAdd += `
              <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="${datum.id}" name="${key}" value="${datum.id}">
                  <label class="custom-control-label" for="${datum.id}">${datum.jumlah}%</label>
              </div>
              `;
            });
          }
        }
        modalAdd += '</div>';
      } else if (formInputs[key]['type'] == 'select') {
        modalAdd += `
        <div class="mb-4">
          <label for="${key}-input">${key.replace(/_/g,' ')}</label> ${formInputs[key]['required']?'<span class="red-text">*</span>':''}
          <select class="browser-default custom-select" name="${key}" id="${key}-input" ${formInputs[key]['required']?'required':''} ${formInputs[key]['disabled']?'disabled':''}>
          <option value="" selected hidden>--- PILIH ${key.replace(/_/g,' ').toUpperCase()} ---</option>
        `;
        if (Array.isArray(formInputs[key]['data'])) {
          if (typeof formInputs[key]['data'][0] == 'object') {
            if (key == 'pelanggan_id') {
              formInputs[key]['data'].forEach(datum => {
                modalAdd += `<option value="${datum.kode}">${datum.nama_perusahaan}</option>`;
              });
            } else {
              formInputs[key]['data'].forEach(datum => {
                modalAdd += `<option value="${datum.id}">${datum.nama}</option>`;
              });
            }
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
              <label class="custom-control-label" for="${datum}">${datum}%</label>
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

    $('#pelanggan_id-input').change(() => {
      const selDetail = $('#detail_pelanggan_id-input');
      let pelanggan = $('#pelanggan_id-input').find(':selected').val();
      selDetail.empty();
      selDetail.append('<option value="" selected hidden>--- PILIH DETAIL PELANGGAN ID ---</option>');
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

    // Add event listener for save button
    $('#input-form').submit((event) => {
      event.preventDefault();

      // Get the form data
      const form = document.getElementById('input-form')
      const formData = new FormData(form);
      formData.delete('pelanggan_id');
      formData.append('marketing_id', <?php echo $_SESSION['id'] ?>);
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


      // Send the AJAX request
      $.ajax({
        type: 'POST',
        url: './api/penawaran_barang_add.php',
        data: formData,
        contentType: false,
        processData: false,
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

  let counter = 0;
  const tambahBarang = (e) => {
    e.preventDefault();
    let table = `
      <tr id="row${counter}">
        <td>
          <select class="browser-default custom-select" name="detail_penawaran_barangs[${counter}][barang_id]" required>
            <option value="" selected hidden>PILIH BARANG</option>
      `;
    formInputs['detail_penawaran_barangs']['data'].forEach(datum => {
      table += `<option value="${datum.id}">${datum.nama}</option>`;
    })
    table += `
          </select>
        </td>
        <td><input type="number" name="detail_penawaran_barangs[${counter}][harga_jual]" class="form-control validate" required></td>
        <td><input type="text" name="detail_penawaran_barangs[${counter}][kuantitas]" class="form-control validate" required></td>
        <td>
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="chk${counter}" name="detail_penawaran_barangs[${counter}][ppn]" value="${ppn.jumlah}" checked>
            <label class="custom-control-label" for="chk${counter}">${ppn.jumlah}%</label>
          </div>
        </td>
        <td><button class="btn btn-danger px-2 py-1" onClick="hapusBarang(event, 'row${counter}')"><i class="fas fa-minus"></i></button></td>
      </tr>
      `;
    $('#tbBarang').append(table);
    counter++;
  }

  const hapusBarang = (e, id) => {
    e.preventDefault()
    $(`#${id}`).remove()
  }

  const editModal = (kode, pelanggan) => {
    // Send the AJAX request
    $.ajax({
      type: 'POST',
      url: './api/penawaran_barang_get_one.php',
      data: {
        'kode': kode,
      },
      success: response => {
        response = JSON.parse(response);
        if (response.success) {
          $.ajax({
            type: 'POST',
            url: './api/detail_pelanggan_get.php',
            data: {
              'kode': pelanggan
            },
            success: response1 => {
              response1 = JSON.parse(response1);

              let modalEdit = `
          <div class="modal fade" id="modalUbah" tabindex="-1" role="dialog" aria-labelledby="modalUbahTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
              <div class="modal-content">
                <form id="edit-form">
                  <div class="modal-header">
                    <h5 class="modal-title">Ubah ${kode}</h5>
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
                } else if (formInputs[key]['type'] == 'detail_penawaran_barangs') {
                  modalEdit += `
                  <div class="mb-4">
                    <label>daftar barang</label><button class="btn btn-primary px-2 py-1" onClick="tambahBarang(event)"><i class="fas fa-plus"></i></button>
                    <div class="table-responsive">
                      <table class="table table-bordered table-sm">
                        <thead>
                          <tr>
                            <th>Barang</th>
                            <th>Harga Jual</th>
                            <th>Kuantitas</th>
                            <th>PPN</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody id="tbBarang">
                  `;
                  response.barangs.forEach(barang => {
                    let table = `
                    <tr id="row${counter}">
                      <td>
                        <select class="browser-default custom-select" name="detail_penawaran_barangs[${counter}][barang_id]" required>
                          <option value="" selected hidden>PILIH BARANG</option>
                    `;
                    formInputs['detail_penawaran_barangs']['data'].forEach(datum => {
                      table += `<option value="${datum.id}" ${datum.id==barang.barang_id?'selected':''}>${datum.nama}</option>`;
                    })
                    table += `
                        </select>
                      </td>
                      <td><input type="number" name="detail_penawaran_barangs[${counter}][harga_jual]" class="form-control validate" value="${barang.harga_jual}" required></td>
                      <td><input type="text" name="detail_penawaran_barangs[${counter}][kuantitas]" class="form-control validate" value="${barang.kuantitas}" required></td>
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="chk${counter}" name="detail_penawaran_barangs[${counter}][ppn]" value="${barang.ppn!=0?barang.ppn:ppn.jumlah}" ${barang.ppn!=0?'checked':''}>
                          <label class="custom-control-label" for="chk${counter}">${barang.ppn!=0?barang.ppn:ppn.jumlah}%</label>
                        </div>
                      </td>
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
                } else if (formInputs[key]['type'] == 'checkbox') {
                  modalEdit += `
                  <div class="mb-4">
                    <p>${key.replace(/_/g,' ')} ${formInputs[key]['required']?'<span class="red-text">*</span>':''}</p>
                  `;
                  if (Array.isArray(formInputs[key]['data'])) {
                    if (typeof formInputs[key]['data'][0] == 'object') {
                      formInputs[key]['data'].forEach(datum => {
                        console.log("check " + response.data[key])
                        modalEdit += `
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="${datum.id}" name="${key}" value="${datum.id}" ${response.data[key]==datum.id?'checked':''}>
                            <label class="custom-control-label" for="${datum.id}">${datum.jumlah}%</label>
                        </div>
                        `;
                      });
                    }
                  }
                  modalEdit += '</div>';
                } else if (formInputs[key]['type'] == 'select') {
                  modalEdit += `
              <div class="mb-4">
                <label for="${key}-input">${key.replace(/_/g,' ')}</label> ${formInputs[key]['required']?'<span class="red-text">*</span>':''}
                <select class="browser-default custom-select" name="${key}" id="${key}-input" ${formInputs[key]['required']?'required':''} >
              `;
                  if (Array.isArray(formInputs[key]['data'])) {
                    if (typeof formInputs[key]['data'][0] == 'object') {
                      formInputs[key]['data'].forEach(datum => {
                        if (key == 'pelanggan_id') {
                          if (response.data[key] == datum) {
                            modalEdit += `<option value="${datum.kode}" selected>${datum.nama_perusahaan}</option>`;
                          } else {
                            modalEdit += `<option value="${datum.kode}">${datum.nama_perusahaan}</option>`;
                          }
                        } else {
                          if (response.data[key] == datum) {
                            modalEdit += `<option value="${datum.id}" selected>${datum.nama}</option>`;
                          } else {
                            modalEdit += `<option value="${datum.id}">${datum.nama}</option>`;
                          }
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
                selDetail.append('<option value="" selected hidden>--- PILIH DETAIL PELANGGAN ID ---</option>');
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
                formData.append('marketing_id', <?php echo $_SESSION['id'] ?>);
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

                // Send the AJAX request
                $.ajax({
                  type: 'POST',
                  url: './api/penawaran_barang_edit.php',
                  data: formData,
                  contentType: false,
                  processData: false,
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