<?php
require_once 'config.php';
requireLogin();

$page_title = "Dashboard";

// Get counts for dashboard
$counts = [
    'pages' => 0,
    'services' => 0,
    'gallery' => 0,
    'testimonials' => 0
];

// Get page count
$result = $conn->query("SELECT COUNT(*) as count FROM pages");
if ($result) {
    $counts['pages'] = $result->fetch_assoc()['count'];
}

// Get services count
$result = $conn->query("SELECT COUNT(*) as count FROM services");
if ($result) {
    $counts['services'] = $result->fetch_assoc()['count'];
}

// Get gallery count
$result = $conn->query("SELECT COUNT(*) as count FROM gallery");
if ($result) {
    $counts['gallery'] = $result->fetch_assoc()['count'];
}

// Get testimonials count
$result = $conn->query("SELECT COUNT(*) as count FROM testimonials");
if ($result) {
    $counts['testimonials'] = $result->fetch_assoc()['count'];
}

// Get recent activities
$recent_activities = [];
$result = $conn->query("
    (SELECT 'page' as type, id, title, 'Created' as action, created_at as date FROM pages ORDER BY created_at DESC LIMIT 3)
    UNION
    (SELECT 'service' as type, id, title, 'Created' as action, created_at as date FROM services ORDER BY created_at DESC LIMIT 3)
    UNION
    (SELECT 'testimonial' as type, id, client_name as title, 'Added' as action, created_at as date FROM testimonials ORDER BY created_at DESC LIMIT 3)
    ORDER BY date DESC LIMIT 5
");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recent_activities[] = $row;
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>
        
        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                        <span data-feather="calendar"></span>
                        This week
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Pages</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $counts['pages']; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Total Services</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $counts['services']; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-cogs fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Gallery Items</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $counts['gallery']; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-images fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Testimonials</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $counts['testimonials']; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-comments fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Title</th>
                                    <th>Action</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recent_activities)): ?>
                                    <?php foreach ($recent_activities as $activity): ?>
                                        <tr>
                                            <td><?php echo ucfirst($activity['type']); ?></td>
                                            <td><?php echo htmlspecialchars($activity['title']); ?></td>
                                            <td><?php echo $activity['action']; ?></td>
                                            <td><?php echo date('M d, Y', strtotime($activity['date'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No recent activities found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
