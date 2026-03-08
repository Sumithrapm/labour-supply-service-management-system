<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');

if(strlen($_SESSION['lssemsuid']) == 0) {
    header('location:index.php');
    exit();
}

$userid = $_SESSION['lssemsuid'];

// Cancel Booking
if(isset($_GET['cancel'])) {
    $bookingid = $_GET['cancel'];
    $sql = "UPDATE tblbooking SET Status='Cancelled' WHERE ID=:bid AND UserID=:uid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':bid', $bookingid, PDO::PARAM_STR);
    $query->bindParam(':uid', $userid, PDO::PARAM_STR);
    $query->execute();
    echo "<script>alert('Booking cancelled successfully');</script>";
    echo "<script>window.location.href='my-bookings.php'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - LSSEMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include('../includes/header.php'); ?>

    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-calendar-alt"></i> My Bookings</h1>
            <p class="text-white">View and manage your service bookings</p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">
                <?php
                $sql = "SELECT b.*, w.FullName as WorkerName, w.Category, w.MobileNumber as WorkerMobile, w.Picture 
                        FROM tblbooking b 
                        LEFT JOIN tblworker w ON b.WorkerID = w.ID 
                        WHERE b.UserID=:uid 
                        ORDER BY b.BookingDate DESC";
                $query = $dbh->prepare($sql);
                $query->bindParam(':uid', $userid, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                
                if($query->rowCount() > 0) {
                    foreach($results as $row) { ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2 text-center">
                                        <img src="../images/<?php echo htmlentities($row->Picture); ?>" 
                                             alt="Worker" 
                                             class="img-fluid rounded-circle" 
                                             style="width: 100px; height: 100px; object-fit: cover;"
                                             onerror="this.src='https://via.placeholder.com/100'">
                                    </div>
                                    <div class="col-md-7">
                                        <h5>
                                            Booking #<?php echo htmlentities($row->BookingNumber); ?>
                                            <span class="badge badge-<?php 
                                                echo $row->Status == 'Completed' ? 'success' : 
                                                    ($row->Status == 'Pending' ? 'warning' : 
                                                    ($row->Status == 'Cancelled' ? 'danger' : 'info')); 
                                            ?>">
                                                <?php echo htmlentities($row->Status); ?>
                                            </span>
                                        </h5>
                                        <p class="mb-1">
                                            <strong><i class="fas fa-user-tie"></i> Worker:</strong> 
                                            <?php echo htmlentities($row->WorkerName); ?>
                                        </p>
                                        <p class="mb-1">
                                            <strong><i class="fas fa-briefcase"></i> Category:</strong> 
                                            <?php echo htmlentities($row->Category); ?>
                                        </p>
                                        <p class="mb-1">
                                            <strong><i class="fas fa-phone"></i> Contact:</strong> 
                                            <?php echo htmlentities($row->WorkerMobile); ?>
                                        </p>
                                        <p class="mb-1">
                                            <strong><i class="fas fa-calendar"></i> Service Date:</strong> 
                                            <?php echo htmlentities($row->ServiceDate); ?> at <?php echo htmlentities($row->ServiceTime); ?>
                                        </p>
                                        <p class="mb-1">
                                            <strong><i class="fas fa-map-marker-alt"></i> Location:</strong> 
                                            <?php echo htmlentities($row->Address); ?>, <?php echo htmlentities($row->City); ?>
                                        </p>
                                        <p class="mb-1">
                                            <strong><i class="fas fa-clipboard"></i> Work Description:</strong> 
                                            <?php echo htmlentities($row->WorkDescription); ?>
                                        </p>
                                        <p class="mb-1">
                                            <strong><i class="fas fa-user-check"></i> Worker Status:</strong> 
                                            <span class="badge badge-<?php 
                                                echo $row->WorkerStatus == 'Completed' ? 'success' : 
                                                    ($row->WorkerStatus == 'Pending' ? 'warning' : 
                                                    ($row->WorkerStatus == 'Rejected' ? 'danger' : 'info')); 
                                            ?>">
                                                <?php echo htmlentities($row->WorkerStatus); ?>
                                            </span>
                                        </p>
                                        <?php if($row->Remark) { ?>
                                            <p class="mb-1">
                                                <strong><i class="fas fa-comment"></i> Remark:</strong> 
                                                <?php echo htmlentities($row->Remark); ?>
                                            </p>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <h3 class="text-success">₹<?php echo htmlentities($row->Amount); ?></h3>
                                        <p class="mb-2">
                                            <span class="badge badge-<?php echo $row->PaymentStatus == 'Paid' ? 'success' : 'warning'; ?>">
                                                Payment: <?php echo htmlentities($row->PaymentStatus); ?>
                                            </span>
                                        </p>
                                        <p class="text-muted small">
                                            Booked on: <?php echo date('d M Y', strtotime($row->BookingDate)); ?>
                                        </p>
                                        
                                        <div class="mt-3">
                                            <?php if($row->Status == 'Pending' && $row->PaymentStatus != 'Paid') { ?>
                                                <a href="payment.php?booking=<?php echo $row->BookingNumber; ?>" class="btn btn-sm btn-success btn-block mb-2">
                                                    <i class="fas fa-credit-card"></i> Make Payment
                                                </a>
                                            <?php } ?>
                                            
                                            <?php if($row->Status == 'Pending') { ?>
                                                <a href="?cancel=<?php echo $row->ID; ?>" class="btn btn-sm btn-danger btn-block mb-2" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                    <i class="fas fa-times"></i> Cancel Booking
                                                </a>
                                            <?php } ?>
                                            
                                            <?php 
                                            // Show payment button when work is completed by worker and payment is not yet made
                                            if($row->WorkerStatus == 'Completed' && $row->PaymentStatus != 'Paid') { ?>
                                                <a href="payment.php?id=<?php echo $row->ID; ?>" class="btn btn-sm btn-success btn-block mb-2">
                                                    <i class="fas fa-money-bill-wave"></i> Pay Now
                                                </a>
                                                <small class="text-success d-block mb-2">
                                                    <i class="fas fa-check-circle"></i> Work completed! Please make payment
                                                </small>
                                            <?php } ?>
                                            
                                            <?php if($row->Status == 'Completed' && $row->PaymentStatus == 'Paid') { ?>
                                                <a href="review.php?booking_id=<?php echo $row->ID; ?>" class="btn btn-sm btn-warning btn-block">
                                                    <i class="fas fa-star"></i> Write Review
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php }} else { ?>
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle fa-3x mb-3"></i>
                        <h4>No Bookings Yet</h4>
                        <p>You haven't made any bookings yet. Start by searching for workers!</p>
                        <a href="search-workers.php" class="btn btn-primary mt-3">
                            <i class="fas fa-search"></i> Search Workers
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>