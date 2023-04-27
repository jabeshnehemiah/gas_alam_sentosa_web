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
  <div class="container py-2">
    <div class="alert-container"></div>
    <h1 class="h1-responsive pb-2">USER</h1>
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
    'username': {
      'type': 'text',
      'required': true
    },
    'role_id': {
      'type': 'select',
      'data': [],
      'required': true
    },
    'divisi_id': {
      'type': 'select',
      'data': [],
      'required': true
    },
    'atasan_id': {
      'type': 'select',
      'data': [],
      'disabled': true,
    },
  };

  let users = [];

  $(document).ready(async () => {
    await loadRoles();
    await loadDivisis();

    await loadPage();

    $('.alert').alert();
  });

  const loadRoles = async () => {
    // Send the AJAX request
    await $.ajax({
      type: 'POST',
      url: './api/role_get.php',
      success: response => {
        response = JSON.parse(response);
        if (response.success) {
          formInputs['role_id']['data'] = response.data;
        }
      },
      error: (jqXHR, textStatus, errorThrown) => {
        console.log(textStatus, errorThrown);
      }
    });
  }

  const loadDivisis = async () => {
    // Send the AJAX request
    await $.ajax({
      type: 'POST',
      url: './api/divisi_get.php',
      success: response => {
        response = JSON.parse(response);
        if (response.success) {
          formInputs['divisi_id']['data'] = response.data;
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
      url: './api/user_get.php',
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
          users = data;
          console.log(users)
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
            if (key != 'aktif' && key != 'id') {
              head += `<th>${key.toUpperCase()}</th>`;
              foot += `<th>${key.toUpperCase()}</th>`;
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
              if (key != 'aktif' && key != 'id') {
                if (datum[key] != null) {
                  row += `<td>${datum[key]}</td>`;
                } else {
                  row += `<td>-</td>`;
                }
              }
            });
            row += `
            <td>
              <button type="button" class="btn btn-secondary btn-sm m-0 px-3 edit-button" onClick="editModal('${datum['kode']}','${datum['nama']}')"><i class="fas fa-edit"></i></button>
            `;
            if (datum['aktif'] == 1) {
              row += `<button type="button" class="btn btn-danger btn-sm m-0 px-3 delete-button" onClick="deleteModal('${datum['kode']}','${datum['nama']}')"><i class="fas fa-trash-alt"></i></button>`;
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
              <h5 class="modal-title">Tambah User</h5>
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
          <select class="browser-default custom-select modal-select" name="${key}" id="${key}-input" ${formInputs[key]['required']?'required':''} ${formInputs[key]['disabled']?'disabled':''}>
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

    $('#role_id-input, #divisi_id-input').change(() => {
      const role = $('#role_id-input option:selected').val();
      const divisi = $('#divisi_id-input option:selected').val();
      if (role != '' && divisi != '') {
        $('#atasan_id-input').removeAttr('disabled');
        $.ajax({
          type: 'POST',
          url: './api/user_get.php',
          data: {
            'role': role,
            'divisi': divisi
          },
          success: response => {
            response = JSON.parse(response);

            $('#atasan_id-input').empty();
            $('#atasan_id-input').append(`<option></option>`);

            response.data.forEach(datum => {
              $('#atasan_id-input').append(`<option value="${datum.id}">${datum.nama} - ${datum.role}</option>`);
            })
          },
          error: (jqXHR, textStatus, errorThrown) => {
            console.log(textStatus, errorThrown);
          }
        });
      }
    });

    // Add event listener for save button
    $('#input-form').submit((event) => {
      event.preventDefault();

      // Get the form data
      const form = document.getElementById('input-form')
      const formData = new FormData(form);

      // Send the AJAX request
      $.ajax({
        type: 'POST',
        url: './api/user_add.php',
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

  const editModal = (kode) => {
    // Send the AJAX request
    $.ajax({
      type: 'POST',
      url: './api/user_get_one.php',
      data: {
        'kode': kode,
      },
      success: response => {
        response = JSON.parse(response);
        $.ajax({
          type: 'POST',
          url: './api/user_get.php',
          data: {
            'role': response.data['role_id'],
            'divisi': response.data['divisi_id'],
          },
          success: response1 => {
            response1 = JSON.parse(response1);
            if (response.success) {
              formInputs['atasan_id']['data'] = response1.data;
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
                } else if (formInputs[key]['type'] == 'select') {
                  modalEdit += `
              <div class="mb-4">
                <label for="${key}-input">${key.replace(/_/g,' ')}</label> ${formInputs[key]['required']?'<span class="red-text">*</span>':''}
                <select class="browser-default custom-select modal-select" name="${key}" id="${key}-input" ${formInputs[key]['required']?'required':''} >
                  <option></option>
              `;
                  if (Array.isArray(formInputs[key]['data'])) {
                    if (typeof formInputs[key]['data'][0] == 'object') {
                      console.log(response.data[key])
                      formInputs[key]['data'].forEach(datum => {
                        if (response.data[key] == datum.id) {
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

              $('#role_id-input, #divisi_id-input').change(() => {
                const role = $('#role_id-input option:selected').val();
                const divisi = $('#divisi_id-input option:selected').val();
                if (role != '' && divisi != '') {
                  $.ajax({
                    type: 'POST',
                    url: './api/user_get.php',
                    data: {
                      'role': role,
                      'divisi': divisi
                    },
                    success: response => {
                      response = JSON.parse(response);

                      $('#atasan_id-input').empty();
                      $('#atasan_id-input').append(`<option></option>`);

                      response.data.forEach(datum => {
                        $('#atasan_id-input').append(`<option value="${datum.id}">${datum.nama} - ${datum.role}</option>`);
                      })
                    },
                    error: (jqXHR, textStatus, errorThrown) => {
                      console.log(textStatus, errorThrown);
                    }
                  });
                }
              });

              $('#edit-form').submit(event => {
                event.preventDefault();

                // Get the form data
                const form = document.getElementById('edit-form')
                const formData = new FormData(form);
                formData.append('id', response.data.id);

                // Send the AJAX request
                $.ajax({
                  type: 'POST',
                  url: './api/user_edit.php',
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

            }
          },
          error: (jqXHR, textStatus, errorThrown) => {
            console.log(textStatus, errorThrown);
          }
        })
      },
      error: (jqXHR, textStatus, errorThrown) => {
        console.log(textStatus, errorThrown);
      }
    });
  }

  let counter = 0;

  const deleteModal = (kode) => {
    // Send the AJAX request
    $.ajax({
      type: 'POST',
      url: './api/pelanggan_get.php',
      data: {
        'kode': kode,
      },
      success: response => {
        response = JSON.parse(response);
        console.log(response)
        if (response.success) {
          let modalHapus = `
          <div class="modal fade" id="modalHapus" tabindex="-1" data-focus="false" role="dialog" aria-labelledby="modalHapusTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
              <div class="modal-content">
                <form id="delete-form">
                  <div class="modal-header">
                    <h5 class="modal-title">Nonaktifkan User ${kode}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-4">
                      <label>daftar pelanggan</label>
                      <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                          <thead>
                            <tr>
                              <th>Pelanggan</th>
                              <th>Marketing</th>
                            </tr>
                          </thead>
                          <tbody id="tbPelanggan">
          `;

          response.data.forEach(datum => {
            let table = `
                    <tr id="row${counter}">
                      <td>
                        <input type="text" class="form-control validate" value="${datum.badan_usaha} ${datum.nama_perusahaan} - ${datum.kota}" disabled>
                        <input type="hidden" class="form-control validate" name="pelanggans[${counter}][id]" value="${datum.id}">
                      </td>
                      <td>
                        <select class="browser-default custom-select modal-select" name="pelanggans[${counter}][marketing_id]" required>
                          <option></option>
                    `;
            users.forEach(user => {
              if (user.kode != kode) {
                table += `<option value="${user.id}">${user.nama}</option>`;
              }
            })
            table += `
                        </select>
                      </td>
                    </tr>
                    `;
            modalHapus += table;
            counter++;

          })

          modalHapus += `
                        </tbody>
                      </table>
                    </div>
                  </div>
                  `;

          // Append modal
          modalHapus += `
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fas fa-ban mr-2"></i>Batal</button>
                    <button type="submit" class="btn btn-danger" id="simpan-button"><i class="fas fa-trash mr-2"></i>Nonaktifkan</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          `;

          $('.modal-container').html(modalHapus);
          $('#modalHapus').modal('show');

          $('.modal-select').select2({
            theme: 'bootstrap4',
            width: 'element',
            placeholder: 'PILIH SALAH SATU'
          });

          $('#delete-form').submit(event => {
            event.preventDefault();

            // Get the form data
            const form = document.getElementById('delete-form')
            const formData = new FormData(form);
            formData.append('kode', kode);

            // Send the AJAX request
            $.ajax({
              type: 'POST',
              url: './api/user_deactivate.php',
              data: formData,
              contentType: false,
              processData: false,
              success: async response => {
                console.log(response)
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
      },
      error: (jqXHR, textStatus, errorThrown) => {
        console.log(textStatus, errorThrown);
      }
    });
  }

  const deactivateModal = (kode, nama) => {
    let modalDelete = `
          <div class="modal fade" id="modalHapus" tabindex="-1" data-focus="false" role="dialog" aria-labelledby="modalHapusTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <form id="delete-form">
                  <div class="modal-header">
                    <h5 class="modal-title">Nonaktifkan ${nama}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                  <h2>Apakah Anda yakin akan menonaktifkan ${nama}?</h2>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fas fa-ban mr-2"></i>Batal</button>
                    <button type="submit" class="btn btn-danger" id="simpan-button"><i class="fas fa-trash mr-2"></i>Nonaktifkan</button>
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
        'kode': kode,
      };

      // Send the AJAX request
      $.ajax({
        type: 'POST',
        url: './api/user_deactivate.php',
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