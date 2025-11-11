 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
     <!-- Brand Logo -->
     <a href="../../index3.html" class="brand-link">
         <img src="<?= base_url('assets') ?>/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3">
         <span class="brand-text font-weight-light">AdminLTE 3</span>
     </a>

     <!-- Sidebar -->
     <div class="sidebar">
         <!-- Sidebar user (optional) -->
         <div class="user-panel mt-3 pb-3 mb-3 d-flex">
             <div class="image">
                 <img src="<?= base_url('assets') ?>/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
             </div>
             <div class="info">
                 <a href="#" class="d-block">Clinic</a>
             </div>
         </div>

         <!-- SidebarSearch Form -->
         <div class="form-inline">
             <div class="input-group" data-widget="sidebar-search">
                 <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                 <div class="input-group-append">
                     <button class="btn btn-sidebar">
                         <i class="fas fa-search fa-fw"></i>
                     </button>
                 </div>
             </div>
         </div>

         <nav class="mt-2">
             <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                 <li class="nav-item">
                     <a href="<?= base_url('patient/index') ?>" class="nav-link">
                         <i class="fas fa-user-injured mr-2 text-pink"></i>
                         <p>
                             Patient
                         </p>
                     </a>
                 </li>

                 <li class="nav-item">
                     <a href="<?= base_url('pharmacy/index') ?>" class="nav-link">
                        <i class="fas fa-prescription-bottle-alt mr-2" style="color:yellow;"></i>
                         <p>
                             Pharmacy
                         </p>
                     </a>
                 </li>

                 <li class="nav-item">
                     <a href="<?= base_url('Patient_records/index') ?>" class="nav-link">
                         <i class="fas fa-user-injured mr-2" style="color:red;"></i>
                         <p>
                             Patient Records
                         </p>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="<?= base_url('Supplier/index') ?>" class="nav-link">
                         <i class="fas fa-parachute-box mr-2" style="color:green;"></i>
                         <p>
                             Supplier
                         </p>
                     </a>
                 </li>

                 <li class="nav-item">
                     <a href="<?= base_url('Purchase/index') ?>" class="nav-link">
                         <i class="fas fa-shopping-cart mr-2" style="color:orange;"></i>
                         <p>
                             Purchase
                         </p>
                     </a>
                 </li>

                 <li class="nav-item">
                     <a href="#" class="nav-link">
                         <i class="fas fa-cog mr-2 text-primary"></i>
                         <p>
                             Settings
                             <i class="right fas fa-angle-left"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href="<?= base_url('settings/default_settings') ?>" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Default Settings</p>
                             </a>
                         </li>
                     </ul>
                 </li>
             </ul>
         </nav>
     </div>
     <!-- /.sidebar -->
 </aside>