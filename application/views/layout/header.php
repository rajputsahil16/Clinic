  <!DOCTYPE html>
  <html lang="en">

  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>AdminLTE 3 | Fixed Sidebar</title>

      <!-- Google Font: Source Sans Pro -->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/fontawesome-free/css/all.min.css">
      <!-- overlayScrollbars -->
      <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
      <!-- Theme style -->
      <link rel="stylesheet" href="<?= base_url('assets') ?>/dist/css/adminlte.min.css"> <!-- Select2 -->
      <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/select2/css/select2.min.css">
      <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
      <!-- Bootstrap4 Duallistbox -->
      <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
      <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/sweetalert/sweetalert2.min.css">


      <!-- DataTables -->
      <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
      <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
      <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <style>
          .select2-container--default .select2-selection--single {
              height: 38px !important;
              padding: 5px 10px !important;
          }

          .select2-container--default .select2-selection--single .select2-selection__rendered {
              line-height: 28px !important;
          }

          .select2-container--default .select2-selection--single .select2-selection__arrow {
              height: 36px !important;
          }

          .form-group>label {
              display: block;
              margin-bottom: 0.5rem;
              font-weight: 500;
          }

          #importModal table th,
          #importModal table td {
              padding: 4px 8px !important;
              /* reduce vertical spacing */
              line-height: 1.2 !important;
              /* tighten text */
              font-size: 13px;
              /* slightly smaller text */
              vertical-align: middle;
          }

          #importModal table {
              margin-bottom: 0;
              /* remove extra space below table */
          }

          #importModal .card-title {
              line-height: 1.5 !important;
          }

          .bg-pink {
              background-color: red !important;
          }

          .medicineListItem:hover {
              font-weight: bold;
              background-color: #e6e66bff;
          }

          .ajax-loader {
              display: none;
              position: fixed;
              z-index: 1000;
              top: 0;
              left: 0;
              height: 100%;
              width: 100%;
              background: rgba(255, 255, 255, .8) 50% 50% no-repeat;
              /* Optional: Add a custom loading image if not using AdminLTE's built-in spinner classes */
          }

          .ajax-loader .overlay {
              position: absolute;
              top: 50%;
              left: 50%;
              transform: translate(-50%, -50%);
          }
      </style>

  </head>

  <body class="hold-transition sidebar-mini layout-fixed bg-default">
      <!-- Site wrapper -->
      <nav class="main-header navbar navbar-expand navbar-white navbar-light bg-white">
          <ul class="navbar-nav">
              <li class="nav-item">
                  <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
              </li>
              <li class="nav-item d-none d-sm-inline-block">
                  <a href="../../index3.html" class="nav-link">Home</a>
              </li>
              <li class="nav-item d-none d-sm-inline-block">
                  <a href="#" class="nav-link">Contact</a>
              </li>
          </ul>
      </nav>
      <!-- /.navbar -->

      <?php $this->load->view('layout/sidebar') ?>