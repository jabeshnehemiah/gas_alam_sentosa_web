<?php include './redirect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<?php
include './head.php';
?>
<script src="./js/constants.js"></script>
<script src="./js/html2pdf.min.js"></script>
<style>
  #kode-input {
    text-transform: uppercase;
  }
  #pdf table, th, tr,td{
    border:1px solid black !important;
  }
</style>

<body>
  <?php include './navbar.php'; ?>
  <div class="container py-4">
    <div class="alert-container"></div>
    <div class="d-flex justify-content-between" id="heading"></div>
    <div class="table-container"></div>
    <div class="modal-container"></div>
    <div class="pdf-container">
      <div id="pdf" class="p-5 border" style="font-family: monospace" ;>
        <div class="d-flex justify-content-between pb-3">
          <div>
            <h4>PT Gas Alam Sentosa</h4>
            <h6>Ruko CBD Puncak 7F Toll</h6>
            <h6>Jl. Keramat I, Surabaya, Jawa Timur 60229</h6>
          </div>
          <div class="text-right">
            <h2>SURAT JALAN</h2>
            <h4 id="pdf-kode">SJ/IT/2023/04/0001</h4>
          </div>
        </div>
        <hr class="border-dark">
        <div class="d-flex justify-content-between pt-3 pb-5">
          <div>
            <h5>Kepada Yth.</h5>
            <h6>PT Perusahaan Dummy</h6>
            <h6>Jl. Dummy Detail, Surabaya, Jawa Timur 60222</h6>
            <h6>08123456789</h6>
          </div>
          <div>
            <h5>&nbsp;</h5>
            <h6>Nomor PO : 12345</h6>
            <h6>Tanggal &nbsp;: 2023-04-10</h6>
          </div>
        </div>
        <div>
          <table class="table" style="border: 1px solid black;">
            <thead>
              <tr>
                <th scope="col">No</th>
                <th scope="col">Barang</th>
                <th scope="col">Jumlah</th>
                <th scope="col">Satuan</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row">1</th>
                <td>Sit</td>
                <td>Amet</td>
                <td>Consectetur</td>
              </tr>
              <tr>
                <th scope="row">2</th>
                <td>Adipisicing</td>
                <td>Elit</td>
                <td>Sint</td>
              </tr>
              <tr>
                <th scope="row">3</th>
                <td>Hic</td>
                <td>Fugiat</td>
                <td>Temporibus</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>
<script type="text/javascript">
  let formInputs = {
    'request_order_id': {
      'type': 'select',
      'data': [],
      'required': true
    },
    'kuantitas': {
      'type': 'number',
      'required': true
    },
    'tanggal_kirim': {
      'type': 'date',
      'required': true
    },
    'nama_driver': {
      'type': 'text',
    },
  };

  $(document).ready(function() {
    loadRequests();
    loadPage();

    $('.alert').alert();
  });

  const loadRequests = () => {
    // Send the AJAX request
    $.ajax({
      type: 'POST',
      url: './api/request_order_get.php',
      success: response => {
        response = JSON.parse(response);
        if (response.success) {
          formInputs['request_order_id']['data'] = response.data;
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
      url: './api/surat_jalan_get.php',
      success: (response) => {
        response = JSON.parse(response);
        let html;

        // Add heading
        $('#heading').html(`
          <h1>SURAT JALAN</h1>
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
              <button type="button" class="btn btn-secondary btn-sm m-0 px-3 edit-button" onClick="editModal('${datum['kode']}')"><i class="fas fa-edit"></i></button>
              <button type="button" class="btn btn-primary btn-sm m-0 px-3 print-button" onClick="printPdf('${datum['kode']}')"><i class="fas fa-file-pdf"></i></button>
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
              <h5 class="modal-title">Tambah Surat Jalan</h5>
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
            formInputs[key]['data'].forEach(datum => {
              modalAdd += `<option value="${datum.id}">${datum.kode}</option>`;
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
        url: './api/surat_jalan_add.php',
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

  const editModal = (kode) => {
    // Send the AJAX request
    $.ajax({
      type: 'POST',
      url: './api/surat_jalan_get_one.php',
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
                    <h5 class="modal-title">Ubah ${response.data.kode}</h5>
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
                    if (response.data[key] == datum) {
                      modalEdit += `<option value="${datum.id}" selected>${datum.kode}</option>`;
                    } else {
                      modalEdit += `<option value="${datum.id}">${datum.kode}</option>`;
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

          $('#edit-form').submit(event => {
            event.preventDefault();

            // Get the form data
            const form = document.getElementById('edit-form')
            const formData = new FormData(form);
            formData.append('kode', kode);
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
              url: './api/surat_jalan_edit.php',
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

        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        console.log(textStatus, errorThrown);
      }
    });
  }

  const printPdf = () => {
    const pdf = document.getElementById('pdf');
    html2pdf(pdf);
  }
</script>

</html>