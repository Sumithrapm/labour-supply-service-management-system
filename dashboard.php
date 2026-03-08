<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');

if(strlen($_SESSION['lssemsuid']) == 0) {
    header('location:index.php');
    exit();
}

$userid = $_SESSION['lssemsuid'];

// Get user details
$sql = "SELECT * FROM tbluser WHERE ID=:uid";
$query = $dbh->prepare($sql);
$query->bindParam(':uid', $userid, PDO::PARAM_STR);
$query->execute();
$user = $query->fetch(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - LSSEMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include('../includes/header.php'); ?>

    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-tachometer-alt"></i> My Dashboard</h1>
            <p class="text-white">Welcome, <?php echo htmlentities($user->FullName); ?>!</p>
        </div>
    </div>

    <div class="container py-5">
        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff;">
                    <i class="fas fa-calendar-check fa-3x mb-3"></i>
                    <?php
                    $sql = "SELECT COUNT(*) as total FROM tblbooking WHERE UserID=:uid";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':uid', $userid, PDO::PARAM_STR);
                    $query->execute();
                    $result = $query->fetch(PDO::FETCH_OBJ);
                    ?>
                    <h2><?php echo $result->total; ?></h2>
                    <p>Total Bookings</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card text-center" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: #fff;">
                    <i class="fas fa-hourglass-half fa-3x mb-3"></i>
                    <?php
                    $sql = "SELECT COUNT(*) as total FROM tblbooking WHERE UserID=:uid AND Status='Pending'";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':uid', $userid, PDO::PARAM_STR);
                    $query->execute();
                    $result = $query->fetch(PDO::FETCH_OBJ);
                    ?>
                    <h2><?php echo $result->total; ?></h2>
                    <p>Pending Bookings</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card text-center" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: #fff;">
                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                    <?php
                    $sql = "SELECT COUNT(*) as total FROM tblbooking WHERE UserID=:uid AND Status='Completed'";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':uid', $userid, PDO::PARAM_STR);
                    $query->execute();
                    $result = $query->fetch(PDO::FETCH_OBJ);
                    ?>
                    <h2><?php echo $result->total; ?></h2>
                    <p>Completed Bookings</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <a href="search-workers.php" class="btn btn-lg btn-outline-primary btn-block">
                                    <i class="fas fa-search fa-2x mb-2"></i><br>
                                    Search Workers
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="my-bookings.php" class="btn btn-lg btn-outline-success btn-block">
                                    <i class="fas fa-calendar fa-2x mb-2"></i><br>
                                    My Bookings
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="profile.php" class="btn btn-lg btn-outline-warning btn-block">
                                    <i class="fas fa-user fa-2x mb-2"></i><br>
                                    My Profile
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="../category.php" class="btn btn-lg btn-outline-info btn-block">
                                    <i class="fas fa-list fa-2x mb-2"></i><br>
                                    Categories
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-history"></i> Recent Bookings</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Booking #</th>
                                        <th>Worker</th>
                                        <th>Service Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT b.*, w.FullName as WorkerName, w.Category 
                                            FROM tblbooking b 
                                            LEFT JOIN tblworker w ON b.WorkerID = w.ID 
                                            WHERE b.UserID=:uid 
                                            ORDER BY b.BookingDate DESC 
                                            LIMIT 10";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':uid', $userid, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    
                                    if($query->rowCount() > 0) {
                                        foreach($results as $row) { ?>
                                            <tr>
                                                <td><strong><?php echo htmlentities($row->BookingNumber); ?></strong></td>
                                                <td>
                                                    <?php echo htmlentities($row->WorkerName); ?><br>
                                                    <small class="text-muted"><?php echo htmlentities($row->Category); ?></small>
                                                </td>
                                                <td><?php echo htmlentities($row->ServiceDate); ?></td>
                                                <td>₹<?php echo htmlentities($row->Amount); ?></td>
                                                <td>
                                                    <span class="badge badge-<?php 
                                                        echo $row->Status == 'Completed' ? 'success' : 
                                                            ($row->Status == 'Pending' ? 'warning' : 
                                                            ($row->Status == 'Cancelled' ? 'danger' : 'info')); 
                                                    ?>">
                                                        <?php echo htmlentities($row->Status); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="my-bookings.php" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                    <?php }} else { ?>
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <p class="mb-3">No bookings yet</p>
                                                <a href="search-workers.php" class="btn btn-primary">
                                                    <i class="fas fa-search"></i> Search Workers
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>