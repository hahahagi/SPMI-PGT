<nav id="sidebarMenu" class="sidebar col-md-3 col-lg-2 bg-dark offcanvas-md offcanvas-start" tabindex="-1" style="box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);">
    <div class="offcanvas-header d-md-none border-bottom border-secondary">
        <h5 class="offcanvas-title text-white" id="sidebarMenuLabel">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column pt-3 px-2">

        <div class="px-3 mb-2">
            <p class="text-uppercase text-light fw-bold mb-0" style="font-size: 0.65rem; letter-spacing: 1px;">Menu Utama</p>
            <hr class="border-secondary mt-1 mb-3">
        </div>

        <ul class="nav flex-column gap-1">
            <li class="nav-item">
                <?php $act = $act ?? 'dashboard';
                $isActive = ($act == 'dashboard'); ?>
                <a class="nav-link rounded-2 d-flex align-items-center <?= $isActive ? 'bg-warning text-dark fw-bold shadow-sm' : 'text-white-50' ?>"
                    href="<?= $base_path ?>dashboard" style="padding: 10px 15px; transition: all 0.2s;">
                    <i class="bi bi-grid-fill me-3 <?= $isActive ? '' : 'text-secondary' ?>"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>

        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin'): ?>
            <div class="px-3 mb-2 mt-4">
                <p class="text-uppercase text-light fw-bold mb-0" style="font-size: 0.65rem; letter-spacing: 1px;">Administrator</p>
                <hr class="border-secondary mt-1 mb-3">
            </div>

            <ul class="nav flex-column gap-1">
                <li class="nav-item">
                    <?php $isActive = (isset($act) && $act == 'data_user'); ?>
                    <a class="nav-link rounded-2 d-flex align-items-center <?= $isActive ? 'bg-warning text-dark fw-bold shadow-sm' : 'text-white-50' ?>"
                        href="<?= $base_path ?>data_user" style="padding: 10px 15px; transition: all 0.2s;">
                        <i class="bi bi-people-fill me-3 <?= $isActive ? '' : 'text-secondary' ?>"></i>
                        <span>Data Pengguna</span>
                    </a>
                </li>
                <li class="nav-item">
                    <?php $isActive = (isset($act) && $act == 'data_role'); ?>
                    <a class="nav-link rounded-2 d-flex align-items-center <?= $isActive ? 'bg-warning text-dark fw-bold shadow-sm' : 'text-white-50' ?>"
                        href="<?= $base_path ?>data_role" style="padding: 10px 15px; transition: all 0.2s;">
                        <i class="bi bi-shield-lock-fill me-3 <?= $isActive ? '' : 'text-secondary' ?>"></i>
                        <span>Manajemen Role</span>
                    </a>
                </li>
                <li class="nav-item">
                    <?php $isActive = (isset($act) && $act == 'laporan'); ?>
                    <a class="nav-link rounded-2 d-flex align-items-center <?= $isActive ? 'bg-warning text-dark fw-bold shadow-sm' : 'text-white-50' ?>"
                        href="<?= $base_path ?>laporan" style="padding: 10px 15px; transition: all 0.2s;">
                        <i class="bi bi-file-earmark-bar-graph-fill me-3 <?= $isActive ? '' : 'text-secondary' ?>"></i>
                        <span>Laporan Rekap</span>
                    </a>
                </li>
            </ul>
        <?php endif; ?>

        <div class="px-3 mb-2 mt-4">
            <p class="text-uppercase text-light fw-bold mb-0" style="font-size: 0.65rem; letter-spacing: 1px;">Sistem & Akun</p>
            <hr class="border-secondary mt-1 mb-3">
        </div>

        <ul class="nav flex-column mb-5">
            <li class="nav-item">
                <?php $isActive = (isset($act) && $act == 'profile'); ?>
                <a class="nav-link rounded-2 d-flex align-items-center <?= $isActive ? 'bg-warning text-dark fw-bold shadow-sm' : 'text-white-50' ?>"
                    href="<?= $base_path ?>profile" style="padding: 10px 15px; transition: all 0.2s;">
                    <i class="bi bi-person-circle me-3 <?= $isActive ? '' : 'text-secondary' ?>"></i>
                    <span>Profil Saya</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger rounded-2 d-flex align-items-center" href="<?= $base_path ?>logout" style="padding: 10px 15px; transition: 0.2s;" onclick="confirmLogout(event, this.href)">
                    <i class="bi bi-box-arrow-left me-3"></i>
                    <span>Keluar Aplikasi</span>
                </a>
            </li>
        </ul>

    </div>
</nav>

<style>
    .hover-light:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: #fff !important;
        transform: translateX(5px);
    }

    .hover-danger:hover {
        background-color: rgba(220, 53, 69, 0.15);
        color: #ff6b6b !important;
    }
</style>