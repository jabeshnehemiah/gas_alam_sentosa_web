<?php include './redirect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<?php
include './head.php';
?>

<body>
  <?php include './navbar.php'; ?>
  <div class="container py-2">
    <div class="alert-container"></div>
    <h1 class="h1-responsive pb-2">SURAT JALAN</h1>
    <div class="param-container py-3 d-flex">
      <div class="mr-2">
        <h6>tanggal awal</h6>
        <input type="date" id="param-awal" class="form-control param" value="<?php echo date('Y-m-d', strtotime('-6 month')); ?>">
      </div>
      <div>
        <h6>tanggal akhir</h6>
        <input type="date" id="param-akhir" class="form-control param" value="<?php echo date('Y-m-d'); ?>">
      </div>
    </div>
    <div class="table-container"></div>
    <div class="modal-container"></div>
    <button type="button" class="btn btn-primary px-3 fab" aria-hidden="true" onClick="addModal()"><i class="fas fa-plus fa-2x"></i></button>
    <div class="print-container">
      <div id="print" class="p-5">
        <div class="d-flex justify-content-between pb-3">
          <div>
            <h4>PT Gas Alam Sentosa</h4>
            <h6>Ruko CBD Puncak 7F Toll</h6>
            <h6>Jl. Keramat I, Surabaya, Jawa Timur 60229</h6>
          </div>
          <div class="text-right">
            <h2>SURAT JALAN</h2>
            <h4 id="print-kode"></h4>
          </div>
        </div>
        <hr class="border-dark">
        <div class="d-flex justify-content-between pt-3 pb-3">
          <div class="w-50">
            <h5>Kepada Yth.</h5>
            <div class="d-flex">
              <div class="w-25">
                <h6>Nama</h6>
                <h6>Alamat</h6>
              </div>
              <div>
                <h6>:</h6>
                <h6>:</h6>
              </div>
              <div class="ml-1">
                <h6 id="print-nama"></h6>
                <h6 id="print-alamat"></h6>
              </div>
            </div>
          </div>
          <div class="w-25">
            <h5>&nbsp;</h5>
            <div class="d-flex">
              <div class="w-50">
                <h6>No. PO</h6>
                <h6>Tanggal</h6>
              </div>
              <div>
                <h6>:</h6>
                <h6>:</h6>
              </div>
              <div class="ml-1">
                <h6 id="print-po"></h6>
                <h6 id="print-tanggal"></h6>
              </div>
            </div>
          </div>
        </div>
        <p>Dikirimkan barang-barang sebagai berikut: </p>
        <table class="table table-sm">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">BARANG</th>
              <th scope="col">KUANTITAS</th>
              <th scope="col">SATUAN</th>
            </tr>
          </thead>
          <tbody id="print-barangs">
          </tbody>
        </table>
        <div class="d-flex justify-content-between pt-3">
          <div class="w-25">
            <h6 class="text-center pb-5 mb-5">Pelanggan</h6>
            <hr class="border-dark">
          </div>
          <div class="w-25">
            <h6 class="text-center pb-5 mb-5">Driver</h6>
            <hr class="border-dark">
          </div>
          <div class="w-25">
            <h6 class="text-center pb-5 mb-5">Admin</h6>
            <hr class="border-dark">
          </div>
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
    'detail_surat_jalans': {
      'type': 'detail_surat_jalans',
      'data': [],
      'required': true
    },
    'diskon': {
      'type': 'number',
    },
    'biaya_tambahan': {
      'type': 'number',
    },
    'tanggal_kirim': {
      'type': 'date',
      'required': true
    },
    'nama_driver': {
      'type': 'text',
    },
  };

  let ppn = 0;

  $(document).ready(async () => {
    await loadRequests();
    await loadPpn();

    await loadPage();
    $('.param').change(async () => {
      await loadPage();
    });

    $('.alert').alert();
  });

  const loadRequests = async () => {
    // Send the AJAX request
    await $.ajax({
      type: 'POST',
      url: './api/request_order_get.php',
      data: {
        'dari': 'surat_jalan'
      },
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
          formInputs['detail_surat_jalans']['data'] = response.data;
        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        console.log(textStatus, errorThrown);
      }
    });
  }

  const loadPpn = async () => {
    // Send the AJAX request
    await $.ajax({
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
  const loadPage = async () => {
    // Send the AJAX request
    await $.ajax({
      type: 'POST',
      url: './api/surat_jalan_get.php',
      data: {
        'awal': $('#param-awal').val(),
        'akhir': $('#param-akhir').val(),
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
            row += `
            <td>
              <button type="button" class="btn btn-secondary btn-sm m-0 px-3 edit-button" onClick="editModal('${datum['kode']}')"><i class="fas fa-edit"></i></button>
              <button type="button" class="btn btn-default btn-sm m-0 px-3 print-button" onClick="print('${datum['kode']}')"><i class="fas fa-print"></i></button>
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
          const datatable = $('#datatable').DataTable({
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
            fixedColumns: {
              left: $(window).width() >= 768 ? 2 : 0,
            }
          });

          window.onresize = event => {
            datatable.fixedColumns().left($(window).width() >= 768 ? 2 : 0);
          }
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
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
      } else if (formInputs[key]['type'] == 'detail_surat_jalans') {
        modalAdd += `
        <div class="mb-4">
          <label>daftar barang</label><button class="btn btn-primary px-2 py-1" onClick="tambahBarang(event)"><i class="fas fa-plus"></i></button>
          <div class="table-responsive">
            <table class="table table-bordered table-sm text-nowrap">
              <thead>
                <tr>
                  <th class="th-lg">Barang</th>
                  <th class="th-lg">Harga Jual</th>
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
              modalAdd += `<option value="${datum.id}">${datum.kode} ${datum.pelanggan} (${datum.alamat})</option>`;
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

    $('.modal-select').select2({
      theme: 'bootstrap4',
      width: 'element',
      placeholder: 'PILIH SALAH SATU'
    });

    $('#request_order_id-input').change(() => {
      let order = $('#request_order_id-input').find(':selected').val();
      $.ajax({
        type: 'POST',
        url: './api/request_order_get_one.php',
        data: {
          'id': order,
        },
        success: async response => {
          response = JSON.parse(response);

          await loadBarangs(response.data.detail_pelanggan_id);
          for (const key in formInputs) {
            if (key == 'detail_surat_jalans') {
              $('#tbBarang').html('');
              response.barangs.forEach(barang => {
                if (barang.kuantitas > 0) {
                  let table = `
                    <tr id="row${counter}">
                      <td>
                        <select class="browser-default custom-select modal-select" name="detail_surat_jalans[${counter}][barang_id]" id="barang${counter}" onChange="showHarga(${counter})" required>
                          <option></option>
                    `;
                  formInputs['detail_surat_jalans']['data'].forEach(datum => {
                    console.log()
                    table += `<option value="${datum.id}" ${datum.id==barang.barang_id?'selected':''}>${datum.nama}</option>`;
                  })
                  table += `
                        </select>
                      </td>
                      <td>
                        <input type="number" id="harga-disabled${counter}" class="form-control validate" value="${barang.harga_jual}" disabled>
                        <input type="number" id="harga-jual${counter}" name="detail_surat_jalans[${counter}][harga_jual]" class="form-control validate" value="${barang.harga_jual}" hidden required>
                        <input type="number" id="harga-beli${counter}" name="detail_surat_jalans[${counter}][harga_beli]" class="form-control validate" value="${barang.harga_beli}" hidden required>
                      </td>
                      <td><input type="number" name="detail_surat_jalans[${counter}][kuantitas]" class="form-control validate" value="${barang.kuantitas}" required></td>
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="chk${counter}" name="detail_surat_jalans[${counter}][ppn]" value="${barang.ppn!=0?barang.ppn:ppn.jumlah}" ${barang.ppn!=0?'checked':''}>
                          <label class="custom-control-label" for="chk${counter}">${barang.ppn!=0?barang.ppn:ppn.jumlah}%</label>
                        </div>
                      </td>
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
                }
              });
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

      if (!$.trim($("#tbBarang").html())) {
        alert('Daftar barang tidak boleh kosong.');
      } else {
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
      }
    });
  }

  const tambahBarang = (e) => {
    e.preventDefault();
    let table = `
      <tr id="row${counter}">
        <td>
          <select class="browser-default custom-select modal-select" name="detail_surat_jalans[${counter}][barang_id]" id="barang${counter}" onChange="showHarga(${counter})" required>
            <option></option>
      `;
    formInputs['detail_surat_jalans']['data'].forEach(datum => {
      table += `<option value="${datum.id}">${datum.nama}</option>`;
    })
    table += `
          </select>
        </td>
        <td>
          <input type="number" id="harga-disabled${counter}" class="form-control validate" disabled>
          <input type="number" id="harga-jual${counter}" name="detail_surat_jalans[${counter}][harga_jual]" class="form-control validate" hidden required>
          <input type="number" id="harga-beli${counter}" name="detail_surat_jalans[${counter}][harga_beli]" class="form-control validate" hidden required>
        </td>
        <td><input type="number" name="detail_surat_jalans[${counter}][kuantitas]" class="form-control validate" required></td>
        <td>
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="chk${counter}" name="detail_surat_jalans[${counter}][ppn]" value="${ppn.jumlah}" checked>
            <label class="custom-control-label" for="chk${counter}">${ppn.jumlah}%</label>
          </div>
        </td>
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

  const showHarga = id => {
    const barang = formInputs['detail_surat_jalans']['data'].find(obj => {
      return obj.id == $(`#barang${id} option:selected`).val()
    })
    $(`#harga-disabled${id}`).val(barang.harga_jual);
    $(`#harga-jual${id}`).val(barang.harga_jual);
    $(`#harga-beli${id}`).val(barang.harga_beli);
  }

  const editModal = (kode) => {
    // Send the AJAX request
    $.ajax({
      type: 'POST',
      url: './api/surat_jalan_get_one.php',
      data: {
        'kode': kode,
      },
      success: async response => {
        response = JSON.parse(response);
        await loadBarangs(response.data.detail_pelanggan_id)
        if (response.success) {
          let modalEdit = `
          <div class="modal fade" id="modalUbah" tabindex="-1" data-focus="false" role="dialog" aria-labelledby="modalUbahTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
            } else if (formInputs[key]['type'] == 'detail_surat_jalans') {
              modalEdit += `
                  <div class="mb-4">
                    <label>daftar barang</label><button class="btn btn-primary px-2 py-1" onClick="tambahBarang(event)"><i class="fas fa-plus"></i></button>
                    <div class="table-responsive">
                      <table class="table table-bordered table-sm">
                        <thead>
                          <tr>
                            <th class="th-lg">Barang</th>
                            <th class="th-lg">Harga Jual</th>
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
                        <select class="browser-default custom-select modal-select" name="detail_surat_jalans[${counter}][barang_id]" id="barang${counter}" onChange="showHarga(${counter})" required>
                          <option></option>
                    `;
                formInputs['detail_surat_jalans']['data'].forEach(datum => {
                  table += `<option value="${datum.id}" ${datum.id==barang.barang_id?'selected':''}>${datum.nama}</option>`;
                })
                table += `
                        </select>
                      </td>
                      <td>
                        <input type="number" id="harga-disabled${counter}" class="form-control validate" value="${barang.harga_jual}" disabled>
                        <input type="number" id="harga-jual${counter}" name="detail_surat_jalans[${counter}][harga_jual]" class="form-control validate" value="${barang.harga_jual}" hidden required>
                        <input type="number" id="harga-beli${counter}" name="detail_surat_jalans[${counter}][harga_beli]" class="form-control validate" value="${barang.harga_beli}" hidden required>
                      </td>
                      <td><input type="number" name="detail_surat_jalans[${counter}][kuantitas]" class="form-control validate" value="${barang.kuantitas}" required></td>
                      <td>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="chk${counter}" name="detail_surat_jalans[${counter}][ppn]" value="${barang.ppn!=0?barang.ppn:ppn.jumlah}" ${barang.ppn!=0?'checked':''}>
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
                <select class="browser-default custom-select modal-select" name="${key}" id="${key}-input" ${formInputs[key]['required']?'required':''} >
              `;
              if (Array.isArray(formInputs[key]['data'])) {
                if (typeof formInputs[key]['data'][0] == 'object') {
                  formInputs[key]['data'].forEach(datum => {
                    if (response.data[key] == datum) {
                      modalEdit += `<option value="${datum.id}" selected>${datum.kode}  ${datum.pelanggan}</option>`;
                    } else {
                      modalEdit += `<option value="${datum.id}">${datum.kode} ${datum.pelanggan}</option>`;
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

          $('.modal-select').select2({
            theme: 'bootstrap4',
            width: 'element',
            placeholder: 'PILIH SALAH SATU'
          });

          $('#edit-form').submit(event => {
            event.preventDefault();

            if (!$.trim($("#tbBarang").html())) {
              alert('Daftar barang tidak boleh kosong.');
            } else {
              // Get the form data
              const form = document.getElementById('edit-form')
              const formData = new FormData(form);
              formData.append('kode', kode);
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
                url: './api/surat_jalan_edit.php',
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
            }
          })

        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        console.log(textStatus, errorThrown);
      }
    });
  }

  const print = (kode) => {
    $.ajax({
      type: 'POST',
      url: './api/surat_jalan_get_one.php',
      data: {
        'kode': kode,
      },
      success: response => {
        response = JSON.parse(response);
        console.log(response)
        if (response.success) {
          const date = new Date(response.data.tanggal_dibuat)
          $('#print-kode').text(response.data.kode);
          $('#print-nama').text(response.data.pelanggan);
          $('#print-alamat').text(response.data.alamat);
          $('#print-po').text(response.data.no_po);
          $('#print-tanggal').text(date.toLocaleDateString('id', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
          }));
        }
        let table = '';
        let num = 1;
        response.barangs.forEach(barang => {
          table += `
          <tr>
            <td>${num}</td>
            <td>${barang.barang}</td>
            <td>${barang.kuantitas}</td>
            <td>${barang.satuan}</td>
          </tr>
          `;
          num++
        });
        $('#print-barangs').html(table);

        document.title = response.data.kode.replace('/', '_');
        window.print();
        document.title = 'GAS';
      },
      error: (jqXHR, textStatus, errorThrown) => {
        console.log(textStatus, errorThrown);
      }
    });
  }
</script>

</html>