<?php include './redirect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<?php include './head.php'; ?>

<body>
  <?php include './navbar.php'; ?>
  <div class="container py-3">
    <div class="alert-container"></div>
    <h1 class="h1-responsive pb-2">BARANG</h1>
    <div class="table-container"></div>
    <div class="modal-container"></div>
    <button type="button" class="btn btn-primary px-3 fab" aria-hidden="true" onClick="addModal()"><i class="fas fa-plus fa-2x"></i></button>
  </div>
</body>
<script type="text/javascript">
  let formInputs = {
    'nama': {
      'type': 'text',
      'required': true
    },
    'tipe': {
      'type': 'radio',
      'data': tipeBarangs,
      'required': true
    },
    'alur': {
      'type': 'radio',
      'data': alurBarangs,
      'required': true
    },
    'harga_beli': {
      'type': 'number',
      'required': true
    },
    'kode_acc': {
      'type': 'number',
      'required': true
    },
    'kategori_barang_id': {
      'type': 'select',
      'data': [],
      'required': true
    },
    'satuan_id': {
      'type': 'select',
      'data': [],
      'required': true
    },
    'file_gambar': {
      'type': 'file'
    }
  };
  const relations = ['kategori_barangs', 'satuans'];
  $(document).ready(async () => {
    relations.forEach(async table => {
      await loadSelect(table);
    });

    bsCustomFileInput.init();

    await loadPage();

    $('.alert').alert();
  });

  const loadSelect = async table => {
    // Send the AJAX request
    await $.ajax({
      type: 'POST',
      url: './api/umum_get.php',
      data: {
        'table': table
      },
      success: response => {
        response = JSON.parse(response);
        if (response.success) {
          formInputs[table.slice(0, -1).concat('_id')]['data'] = response.data;
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
      url: './api/barang_get.php',
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
              if (datum[key] != null) {
                if (key == 'gambar') {
                  row += `<td><button class="btn btn-link p-0 text-primary" onClick="imgModal('${datum[key]}')">${datum[key]}</button></td>`;
                } else if (key != 'id') {
                  row += `<td>${datum[key]}</td>`;
                }
              } else {
                row += `<td>-</td>`;
              }
            });
            row += `
            <td>
              <button type="button" class="btn btn-secondary btn-sm m-0 px-3 edit-button" onClick="editModal('${datum['kode']}','${datum['nama']}')"><i class="fas fa-edit"></i></button>
            `;
            if (datum['gambar'] != null) {
              row += `
              <button type="button" class="btn btn-danger btn-sm m-0 px-3 delete-button" onClick="deleteModal(${datum['id']},'${datum['kode']}')"><i class="fas fa-trash-alt"></i></button>
            `;
            }
            row += '</td>';
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

  const addModal = () => {
    // Initialize modal
    let modalAdd = `
    <div class="modal fade" id="modalTambah" tabindex="-1" data-focus="false" role="dialog" aria-labelledby="modalTambahTitle" aria-hidden="true">
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
      } else if (formInputs[key]['type'] == 'file') {
        modalAdd += `
        <div class="mb-4">
          <p class="mb-2">${key.replace(/_/g,' ')} ${formInputs[key]['required']?'<span class="red-text">*</span>':''}</p>
          <input type="file" id="${key}-input" accept="image/*" name="${key}" ${formInputs[key]['required']?'required':''} ${formInputs[key]['disabled']?'disabled':''}>
        </div>
        `;
      } else if (formInputs[key]['type'] == 'select') {
        modalAdd += `
        <div class="mb-4">
          <label for="${key}-input">${key.replace(/_/g,' ')}</label> ${formInputs[key]['required']?'<span class="red-text">*</span>':''}
          <select class="browser-default custom-select modal-select" name=${key} id="${key}-input" ${formInputs[key]['required']?'required':''} ${formInputs[key]['disabled']?'disabled':''}>
          <option></option>
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
            <p class="mb-2">${key.replace(/_/g,' ')} ${formInputs[key]['required']?'<span class="red-text">*</span>':''}</p>
          `;

        if (Array.isArray(formInputs[key]['data'])) {
          formInputs[key]['data'].forEach(datum => {
            modalAdd += `
            <div class="custom-control custom-radio custom-control-inline">
              <input type="radio" value="${datum}" class="custom-control-input" id="${datum}" name="${key}" ${formInputs[key]['required']?'required':''} ${formInputs[key]['disabled']?'disabled':''}>
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

    // Add event listener for save button
    $('#input-form').submit((event) => {
      event.preventDefault();

      // Get the form data
      const formData = new FormData(document.getElementById('input-form'));

      // Send the AJAX request
      $.ajax({
        type: 'POST',
        url: './api/barang_add.php',
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

  const editModal = (kode, nama) => {
    // Send the AJAX request
    $.ajax({
      type: 'POST',
      url: './api/barang_get_one.php',
      data: {
        'kode': kode,
      },
      success: response => {
        console.log(response)
        response = JSON.parse(response);
        if (response.success) {
          let modalEdit = `
          <div class="modal fade" id="modalUbah" tabindex="-1" data-focus="false" role="dialog" aria-labelledby="modalUbahTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <form id="edit-form">
                  <div class="modal-header">
                    <h5 class="modal-title">Ubah Data ${nama}</h5>
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
            } else if (formInputs[key]['type'] == 'file') {
              modalEdit += `
              <div class="mb-4">
                <p class="mb-2">${key.replace(/_/g,' ')} ${formInputs[key]['required']?'<span class="red-text">*</span>':''}</p>
                <input type="file" id="${key}-input" accept="image/*"  name="${key}" ${formInputs[key]['required']?'required':''} ${formInputs[key]['disabled']?'disabled':''}>
              </div>
              `;
            } else if (formInputs[key]['type'] == 'select') {
              modalEdit += `
              <div class="mb-4">
                <label for="${key}-input">${key.replace(/_/g,' ')}</label> ${formInputs[key]['required']?'<span class="red-text">*</span>':''}
                <select class="browser-default custom-select modal-select" name=${key} id="${key}-input" ${formInputs[key]['required']?'required':''} >
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
                    <input type="radio" value="${datum}" class="custom-control-input" id="${datum}" name="${key}" ${formInputs[key]['required']?'required':''}  ${response.data[key]==datum?'checked':''}>
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

            // Get the form data
            const formData = new FormData(document.getElementById('edit-form'));
            formData.append('kode', kode);

            // Send the AJAX request
            $.ajax({
              type: 'POST',
              url: './api/barang_edit.php',
              data: formData,
              contentType: false,
              processData: false,
              success: async response => {
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
        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        console.log(textStatus, errorThrown);
      }
    });
  }

  const imgModal = id => {
    // Initialize modal
    let modalImg = `
    <div class="modal fade" id="modalImg" tabindex="-1" role="dialog" aria-labelledby="modalImgTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <form id="input-form">
            <div class="modal-header">
              <h5 class="modal-title">${id}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="m-auto">
                <img src="./files/barang/${id}?t=${Date.now()}" alt="${id}" style="width: 100%;" />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    `;

    $('.modal-container').html(modalImg);
    $('#modalImg').modal('show');
  }

  const deleteModal = (id, kode) => {
    let modalDelete = `
          <div class="modal fade" id="modalHapus" tabindex="-1" data-focus="false" role="dialog" aria-labelledby="modalHapusTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <form id="delete-form">
                  <div class="modal-header">
                    <h5 class="modal-title">Hapus File Gambar ${kode}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                  <h2>Apakah Anda yakin akan menghapus file gambar ${kode}?</h2>
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
    $('#modalHapus').modal('show');

    $('#delete-form').submit(event => {
      event.preventDefault();

      // Get the form data
      const formData = {
        'id': id,
      };

      // Send the AJAX request
      $.ajax({
        type: 'POST',
        url: './api/barang_delete_file.php',
        data: formData,
        success: async response => {
          response = JSON.parse(response);
          $('#modalHapus').modal('hide');
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

  }
</script>

</html>