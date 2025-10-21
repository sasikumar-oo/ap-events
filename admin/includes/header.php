<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Admin Panel</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="../assets/css/admin.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    
    <!-- Custom styles for this page -->
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
        }

        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: .5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .sidebar .nav-link {
            font-weight: 500;
            color: #333;
            padding: 0.5rem 1rem;
            margin: 0.2rem 1rem;
            border-radius: 0.25rem;
        }

        .sidebar .nav-link:hover {
            color: #800020;
            background-color: rgba(128, 0, 32, 0.1);
        }

        .sidebar .nav-link.active {
            color: #fff;
            background-color: #800020;
        }

        .sidebar .nav-link i {
            margin-right: 0.5rem;
            color: #6c757d;
        }

        .sidebar .nav-link.active i {
            color: #fff;
        }

        .sidebar-heading {
            font-size: .75rem;
            text-transform: uppercase;
            padding: 0 1.5rem;
            margin-top: 1.5rem;
            color: #6c757d;
            font-weight: 600;
        }

        .navbar-brand {
            padding-top: .75rem;
            padding-bottom: .75rem;
            background-color: rgba(0, 0, 0, .25);
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
        }

        .navbar .navbar-toggler {
            top: .25rem;
            right: 1rem;
        }

        .navbar .form-control {
            padding: .75rem 1rem;
            border-width: 0;
            border-radius: 0;
        }

        .form-control-dark {
            color: #fff;
            background-color: rgba(255, 255, 255, .1);
            border-color: rgba(255, 255, 255, .1);
        }

        .form-control-dark:focus {
            border-color: transparent;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, .25);
        }

        .bg-dark {
            background-color: #343a40 !important;
        }

        .nav-divider {
            height: 1px;
            margin: 1rem 0;
            overflow: hidden;
            background-color: rgba(0, 0, 0, .1);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-home me-2"></i>Admin Panel
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="../" target="_blank">
                            <i class="fas fa-external-link-alt me-1"></i>View Site
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i>
                            <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'pages/') !== false) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#pagesCollapse" role="button" aria-expanded="false" aria-controls="pagesCollapse">
                                <i class="fas fa-file-alt me-2"></i>
                                Pages
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </a>
                            <div class="collapse <?php echo (strpos($_SERVER['PHP_SELF'], 'pages/') !== false) ? 'show' : ''; ?>" id="pagesCollapse">
                                <ul class="nav flex-column ms-4">
                                    <li class="nav-item">
                                        <a class="nav-link" href="pages.php">All Pages</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="pages.php?action=add">Add New</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="menus.php">Menus</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'services/') !== false) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#servicesCollapse" role="button" aria-expanded="false" aria-controls="servicesCollapse">
                                <i class="fas fa-cogs me-2"></i>
                                Services
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </a>
                            <div class="collapse <?php echo (strpos($_SERVER['PHP_SELF'], 'services/') !== false) ? 'show' : ''; ?>" id="servicesCollapse">
                                <ul class="nav flex-column ms-4">
                                    <li class="nav-item">
                                        <a class="nav-link" href="services.php">All Services</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="services.php?action=add">Add New</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="service-categories.php">Categories</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'gallery/') !== false) ? 'active' : ''; ?>" href="gallery.php">
                                <i class="fas fa-images me-2"></i>
                                Gallery
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'testimonials/') !== false) ? 'active' : ''; ?>" href="testimonials.php">
                                <i class="fas fa-quote-left me-2"></i>
                                Testimonials
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'users/') !== false) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#usersCollapse" role="button" aria-expanded="false" aria-controls="usersCollapse">
                                <i class="fas fa-users me-2"></i>
                                Users
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </a>
                            <div class="collapse <?php echo (strpos($_SERVER['PHP_SELF'], 'users/') !== false) ? 'show' : ''; ?>" id="usersCollapse">
                                <ul class="nav flex-column ms-4">
                                    <li class="nav-item">
                                        <a class="nav-link" href="users.php">All Users</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="users.php?action=add">Add New</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="user-roles.php">Roles & Permissions</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'settings.php') !== false) ? 'active' : ''; ?>" href="settings.php">
                                <i class="fas fa-cog me-2"></i>
                                Settings
                            </a>
                        </li>
                    </ul>
                    
                    <div class="nav-divider"></div>
                    
                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Quick Links</span>
                    </h6>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="../" target="_blank">
                                <i class="fas fa-external-link-alt me-2"></i>
                                View Site
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">
                                <i class="fas fa-user me-2"></i>
                                Your Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
