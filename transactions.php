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
      <h5>Range</h5>
      <input type="date" class="datePicker" id="startDate" value="<?php echo date('Y-m-d', strtotime('-7 days')); ?>">
      -
      <input type="date" class="datePicker" id="endDate" value="<?php echo date('Y-m-d'); ?>">
    </div>
    <div class="table-container"></div>
    <div class="modal-container"></div>
  </div>
</body>
<script type="text/javascript">
  let jsonData;
  let table = '';
  $(document).ready(function() {
    table = 'transactions';

    loadPage(table);

    $('.alert').alert();

    $('.datePicker').change(() => {
      loadPage(table);
    })
  });


  // Function to load page
  const loadPage = table => {
    // Send the AJAX request
    $.ajax({
      type: 'POST',
      url: './api/transactions_get.php',
      data: {
        'start': $('#startDate').val(),
        'end': $('#endDate').val()
      },
      success: (response) => {
        response = JSON.parse(response);
        jsonData = response.data;
        let html;

        // Add heading
        $('#heading').html(`
          <h1>${table.toUpperCase()}</h1>
          `);

        if (response.data.length > 0) {
          // Initialize datatable
          html = `
          <div class="table-responsive">
            <table id="datatable" class="table table-striped table-bordered table-hover text-nowrap" cellspacing="0" width="100%">
          `;

          const data = response['data'];
          const keys = Object.keys(data[0]);

          // Set table head and foot
          let head = `
          <thead class="green white-text">
            <tr>
              <th>NO</th>
          `;
          let foot = `
          <tfoot>
            <tr>
              <th>NO</th>
          `;

          // Set head, foot
          keys.forEach(key => {
            head += `<th>${key.toUpperCase()}</th>`;
            foot += `<th>${key.toUpperCase()}</th>`;
          });

          // Set table body and modal form
          let body = `<tbody>`;
          let count = 1;
          data.forEach(datum => {
            // Set row data
            let row = `
            <tr>
              <td>${count}</td>
            `;
            keys.forEach(key => {
              if (key == 'image') {
                row += `<td><img class="btn p-0" src="${datum[key]}" style="width: 10rem; cursor: pointer;" onClick="imgModal('${datum[keys[0]]}')" /></td>`;
              } else {
                row += `<td>${datum[key]}</td>`;
              }
            });
            row += `</tr>`;
            body += row;
            count++;
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

  const imgModal = id => {
    const imgData = $.grep(jsonData, x => x.id == id)[0];
    console.log(imgData)
    // Initialize modal
    let modalImg = `
    <div class="modal fade" id="modalImg" tabindex="-1" role="dialog" aria-labelledby="modalImgTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <form id="input-form">
            <div class="modal-header">
              <h5 class="modal-title">Image ${id}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="m-auto">
                <img src="${imgData.image}" alt="${id}.jpg" style="width: 100%;" />
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" onClick="saveImg('${id}')"><i class="fas fa-save mr-2"></i>Simpan</button>     
            </div>
          </form>
        </div>
      </div>
    </div>
    `;

    $('.modal-container').html(modalImg);
    $('#modalImg').modal('show');
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
</script>

</html>