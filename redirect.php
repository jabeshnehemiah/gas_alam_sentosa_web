<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
}
$links = [
  'index.php' => [
    'minRole' => 4,
    'divisi' => [
      'Marketing'
    ]
  ],
  'change_password.php' => [
    'minRole' => 4,
    'divisi' => [
      'Marketing'
    ]
  ],
  'detail_pelanggans.php' => [
    'minRole' => 4,
    'divisi' => [
      'Marketing'
    ]
  ],
  'users.php' => [
    'text' => 'User',
    'minRole' => 3,
    'isNav' => true,
    'dropdown' => 'Master',
    'divisi' => [
      'Marketing'
    ]
  ],
  'pelanggans.php' => [
    'text' => 'Pelanggan',
    'minRole' => 4,
    'isNav' => true,
    'dropdown' => 'Master',
    'divisi' => [
      'Marketing'
    ]
  ],
  'barangs.php' => [
    'text' => 'Barang',
    'minRole' => 3,
    'isNav' => true,
    'dropdown' => 'Master',
    'divisi' => [
      'Marketing'
    ]
  ],
  'kategori_barangs.php' => [
    'text' => 'Kategori Barang',
    'minRole' => 3,
    'isNav' => true,
    'dropdown' => 'Master',
    'divisi' => [
      'Marketing'
    ]
  ],
  'satuans.php' => [
    'text' => 'Satuan',
    'minRole' => 3,
    'isNav' => true,
    'dropdown' => 'Master',
    'divisi' => [
      'Marketing'
    ]
  ],

  'pipeline_marketings_followup.php' => [
    'text' => 'Follow Up',
    'minRole' => 4,
    'isNav' => true,
    'dropdown' => 'Pipeline',
    'divisi' => [
      'Marketing'
    ]
  ],
  'pipeline_marketings.php' => [
    'text' => 'History',
    'minRole' => 4,
    'isNav' => true,
    'dropdown' => 'Pipeline',
    'divisi' => [
      'Marketing'
    ]
  ],
  'penawaran_barangs.php' => [
    'text' => 'Penawaran',
    'minRole' => 4,
    'isNav' => true,
    'divisi' => [
      'Marketing'
    ],
  ],
  'request_orders.php' => [
    'text' => 'Request Order',
    'minRole' => 4,
    'isNav' => true,
    'divisi' => [
      'Marketing'
    ],
  ],
  'surat_jalans.php' => [
    'text' => 'Surat Jalan',
    'minRole' => 4,
    'isNav' => true,
    'divisi' => [
      'Marketing'
    ],
  ],
];
$url = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
if (!((in_array($_SESSION['divisi'], $links[$url]['divisi']) || is_null($_SESSION['divisi'])) && $_SESSION['role'] <= $links[$url]['minRole'])) {
  echo "
  <script>
  alert('Anda tidak mempunyai akses ke halaman ini.')
  window.location.href = './index.php';
  </script>
  ";
}
