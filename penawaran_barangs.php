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
    <h1 class="h1-responsive pb-2">PENAWARAN BARANG</h1>
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
            <h2>PENAWARAN BARANG</h2>
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
                <h6>Tanggal</h6>
              </div>
              <div>
                <h6>:</h6>
              </div>
              <div class="ml-1">
                <h6 id="print-tanggal"></h6>
              </div>
            </div>
          </div>
        </div>
        <p>Ditawarkan barang-barang sebagai berikut: </p>
        <table class="table table-sm">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">BARANG</th>
              <th scope="col">HARGA (Rp)</th>
              <th scope="col">PPN (Rp)</th>
              <th scope="col">JUMLAH</th>
              <th scope="col">SATUAN</th>
              <th scope="col">SUBTOTAL (Rp)</th>
            </tr>
          </thead>
          <tbody id="print-barangs">
          </tbody>
          <tfoot>
            <tr>
              <td colspan="6">Diskon</td>
              <td id="print-diskon"></td>
            </tr>
            <tr>
              <td colspan="6">Biaya Tambahan</td>
              <td id="print-biaya"></td>
            </tr>
            <tr>
              <th scope="row" colspan="6">TOTAL (Rp)</th>
              <th id="print-total"></th>
            </tr>
          </tfoot>
        </table>
        <div class="d-flex justify-content-end pt-3">
          <div class="w-25">
            <h6 class="text-center pb-5 mb-5">Marketing</h6>
            <hr class="border-dark">
          </div>
        </div>
      </div>
    </div>
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

  $(document).ready(async()=> {
    await loadPelanggans();
    await loadPpn();

    await loadPage();

    $('.alert').alert();
  });

  const loadPelanggans = async() => {
    // Send the AJAX request
    await $.ajax({
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

  const loadBarangs = async(id) => {
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
          formInputs['detail_penawaran_barangs']['data'] = response.data;
        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        console.log(textStatus, errorThrown);
      }
    });
  }

  const loadPpn = async() => {
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
  const loadPage = async() => {
    // Send the AJAX request
    await $.ajax({
      type: 'POST',
      url: './api/penawaran_barang_get.php',
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
              <button type="button" class="btn btn-secondary btn-sm m-0 px-3 edit-button" onClick="editModal('${datum['kode']}','${datum['kode_pelanggan']}')"><i class="fas fa-edit"></i></button>
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
          const datatable=$('#datatable').DataTable({
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

  const addModal = () => {
    // Initialize modal
    let modalAdd = `
    <div class="modal fade" id="modalTambah" tabindex="-1" data-focus="false" role="dialog" aria-labelledby="modalTambahTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg modal-lg" role="document">
        <div class="modal-content">
          <form id="input-form">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Penawaran Barang</h5>
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
                  <th class="th-lg">Harga Jual</th>
                  <th class="th-lg">Kuantitas</th>
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
          <select class="browser-default custom-select modal-select" name="${key}" id="${key}-input" ${formInputs[key]['required']?'required':''} ${formInputs[key]['disabled']?'disabled':''}>
          <option></option>
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

    $('#detail_pelanggan_id-input').change(async () => {
      const id = $('#detail_pelanggan_id-input').find(':selected').val();

      await loadBarangs(id);
      $('#tbBarang').empty();
    });

    // Add event listener for save button
    $('#input-form').submit((event) => {
      event.preventDefault();

      if (!$.trim($("#tbBarang").html())) {
        alert('Daftar barang tidak boleh kosong.');
      } else {
        // Get the form data
        const form = document.getElementById('input-form');
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
          success: async response => {
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

  let counter = 0;
  const tambahBarang = (e) => {
    e.preventDefault();
    let table = `
      <tr id="row${counter}">
        <td>
          <select class="browser-default custom-select modal-select" name="detail_penawaran_barangs[${counter}][barang_id]" id="barang${counter}" onChange="showHarga(${counter})" required>
            <option></option>
      `;
    formInputs['detail_penawaran_barangs']['data'].forEach(datum => {
      table += `<option value="${datum.id}">${datum.nama}</option>`;
    })
    table += `
          </select>
        </td>
        <td>
          <input type="number" id="harga-disabled${counter}" class="form-control validate" disabled>
          <input type="number" id="harga-jual${counter}" name="detail_penawaran_barangs[${counter}][harga_jual]" class="form-control validate" hidden required>
          <input type="number" id="harga-beli${counter}" name="detail_penawaran_barangs[${counter}][harga_beli]" class="form-control validate" hidden required>
        </td>
        <td><input type="number" name="detail_penawaran_barangs[${counter}][kuantitas]" class="form-control validate" required></td>
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
    const barang = formInputs['detail_penawaran_barangs']['data'].find(obj => {
      return obj.id == $(`#barang${id} option:selected`).val()
    })
    $(`#harga-disabled${id}`).val(barang.harga_jual);
    $(`#harga-jual${id}`).val(barang.harga_jual);
    $(`#harga-beli${id}`).val(barang.harga_beli);
  }

  const editModal = (kode, pelanggan) => {
    // Send the AJAX request
    $.ajax({
      type: 'POST',
      url: './api/penawaran_barang_get_one.php',
      data: {
        'kode': kode,
      },
      success: async response => {
        response = JSON.parse(response);
        await loadBarangs(response.data.detail_pelanggan_id);
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
          <div class="modal fade" id="modalUbah" tabindex="-1" data-focus="false" role="dialog" aria-labelledby="modalUbahTitle" aria-hidden="true">
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
                            <th class="th-lg">Harga Jual</th>
                            <th class="th-lg">Kuantitas</th>
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
                        <select class="browser-default custom-select modal-select" name="detail_penawaran_barangs[${counter}][barang_id]" id="barang${counter}" onChange="showHarga(${counter})" required>
                          <option></option>
                    `;
                    formInputs['detail_penawaran_barangs']['data'].forEach(datum => {
                      table += `<option value="${datum.id}" ${datum.id==barang.barang_id?'selected':''}>${datum.nama}</option>`;
                    })
                    table += `
                        </select>
                      </td>
                      <td>
                        <input type="number" id="harga-disabled${counter}" class="form-control validate" value="${barang.harga_jual}" disabled>
                        <input type="number" id="harga-jual${counter}" name="detail_penawaran_barangs[${counter}][harga_jual]" class="form-control validate" value="${barang.harga_jual}" hidden required>
                        <input type="number" id="harga-beli${counter}" name="detail_penawaran_barangs[${counter}][harga_beli]" class="form-control validate" value="${barang.harga_beli}" hidden required>
                      </td>
                      <td><input type="number" name="detail_penawaran_barangs[${counter}][kuantitas]" class="form-control validate" value="${barang.kuantitas}" required></td>
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
                <select class="browser-default custom-select modal-select" name="${key}" id="${key}-input" ${formInputs[key]['required']?'required':''} >
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

                if (!$.trim($("#tbBarang").html())) {
                  alert('Daftar barang tidak boleh kosong.');
                } else {
                  // Get the form data
                  const form = document.getElementById('edit-form');
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

                  // Send the AJAX request
                  $.ajax({
                    type: 'POST',
                    url: './api/penawaran_barang_edit.php',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success:async response => {
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

  const print = (kode) => {
    $.ajax({
      type: 'POST',
      url: './api/penawaran_barang_get_one.php',
      data: {
        'kode': kode,
      },
      success: response => {
        response = JSON.parse(response);
        console.log(response)
        if (response.success) {
          const date = new Date(response.data.tanggal_dibuat);
          $('#print-kode').text(response.data.kode);
          $('#print-nama').text(response.data.pelanggan);
          $('#print-alamat').text(response.data.alamat);
          $('#print-po').text(response.data.no_po);
          $('#print-diskon').text(response.data.diskon);
          $('#print-biaya').text(response.data.biaya_tambahan);
          $('#print-tanggal').text(date.toLocaleDateString('id', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
          }));
        }
        let total = response.data.biaya_tambahan - response.data.diskon
        let table = '';
        let num = 1;
        response.barangs.forEach(barang => {
          table += `
          <tr>
            <td>${num}</td>
            <td>${barang.barang}</td>
            <td>${barang.harga_jual}</td>
            <td>${barang.ppn*barang.harga_jual/100}</td>
            <td>${barang.kuantitas}</td>
            <td>${barang.satuan}</td>
            <td>${barang.subtotal}</td>
          </tr>
          `;
          num++
          total += barang.subtotal;
        });
        $('#print-barangs').html(table);
        $('#print-total').text(total);

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