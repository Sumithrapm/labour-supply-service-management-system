<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');

if(strlen($_SESSION['lssemsuid']) == 0) {
    header('location:index.php');
    exit();
}

$userid = $_SESSION['lssemsuid'];
$workerid = $_GET['id'];

// Get worker details
$sql = "SELECT * FROM tblworker WHERE ID=:wid AND Status='Approved'";
$query = $dbh->prepare($sql);
$query->bindParam(':wid', $workerid, PDO::PARAM_STR);
$query->execute();
$worker = $query->fetch(PDO::FETCH_OBJ);

if($query->rowCount() == 0) {
    echo "<script>alert('Worker not found');</script>";
    echo "<script>window.location.href='search-workers.php'</script>";
    exit();
}

// Submit Booking
if(isset($_POST['submit'])) {
    $servicedate = $_POST['servicedate'];
    $servicetime = $_POST['servicetime'];
    $workdesc = $_POST['workdesc'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $bookingnumber = generateBookingNumber();
    
    // Calculate amounts
    $estimatedHours = $_POST['estimated_hours'];
    $totalAmount = $estimatedHours * $worker->HourlyRate;
    
    $sql = "INSERT INTO tblbooking(BookingNumber, UserID, WorkerID, ServiceDate, ServiceTime, WorkDescription, 
            Address, City, Amount, Status, WorkerStatus, PaymentStatus) 
            VALUES(:bn, :uid, :wid, :sd, :st, :wd, :addr, :city, :amount, 
            'Waiting for Approval', 'Pending', 'Pending')";
    $query = $dbh->prepare($sql);
    $query->bindParam(':bn', $bookingnumber, PDO::PARAM_STR);
    $query->bindParam(':uid', $userid, PDO::PARAM_STR);
    $query->bindParam(':wid', $workerid, PDO::PARAM_STR);
    $query->bindParam(':sd', $servicedate, PDO::PARAM_STR);
    $query->bindParam(':st', $servicetime, PDO::PARAM_STR);
    $query->bindParam(':wd', $workdesc, PDO::PARAM_STR);
    $query->bindParam(':addr', $address, PDO::PARAM_STR);
    $query->bindParam(':city', $city, PDO::PARAM_STR);
    $query->bindParam(':amount', $totalAmount, PDO::PARAM_STR);
    $query->execute();
    
    $bookingid = $dbh->lastInsertId();
    
    if($bookingid) {
        // Notify Worker
        addNotification($dbh, 'Worker', $workerid, 
            'New Booking Request', 
            'You have received a new booking request #'.$bookingnumber.'. Please review and respond.', 
            $bookingid);
        
        echo "<script>alert('Booking request submitted successfully! Booking Number: $bookingnumber');</script>";
        echo "<script>window.location.href='my-bookings.php'</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Worker - LSSEMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <script>
    function calculateTotal() {
        var hours = document.getElementById('estimated_hours').value;
        var hourlyRate = <?php echo $worker->HourlyRate; ?>;
        
        if(hours > 0) {
            var total = hours * hourlyRate;
            document.getElementById('total_display').innerHTML = '₹' + total.toFixed(2);
        }
    }
    </script>
</head>
<body>
    <?php include('../includes/header.php'); ?>

    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-calendar-plus"></i> Book Worker</h1>
            <p class="text-white">Complete booking details</p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-wpforms"></i> Booking Details</h5>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="form-group">
                                <label>Service Date <span class="text-danger">*</span></label>
                                <input type="date" name="servicedate" class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Preferred Time <span class="text-danger">*</span></label>
                                <input type="time" name="servicetime" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Estimated Hours <span class="text-danger">*</span></label>
                                <input type="number" name="estimated_hours" id="estimated_hours" class="form-control" 
                                       min="1" step="0.5" placeholder="e.g., 4" onchange="calculateTotal()" required>
                                <small class="form-text text-muted">Enter estimated work duration in hours</small>
                            </div>
                            
                            <div class="form-group">
                                <label>Work Description <span class="text-danger">*</span></label>
                                <textarea name="workdesc" class="form-control" rows="4" 
                                          placeholder="Describe the work to be done..." required></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Service Address <span class="text-danger">*</span></label>
                                <textarea name="address" class="form-control" rows="3" required></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>City <span class="text-danger">*</span></label>
                                <input type="text" name="city" class="form-control" required>
                            </div>
                            
                            <div class="alert alert-info">
                                <h6><i class="fas fa-calculator"></i> Payment Calculation</h6>
                                <table class="table table-sm mb-0">
                                    <tr>
                                        <td>Hourly Rate:</td>
                                        <td><strong>₹<?php echo number_format($worker->HourlyRate, 2); ?>/hr</strong></td>
                                    </tr>
                                    <tr class="table-success">
                                        <td>Estimated Total Amount:</td>
                                        <td><strong id="total_display">₹0.00</strong></td>
                                    </tr>
                                </table>
                                <small class="text-muted">* Final amount will be calculated based on actual hours worked and paid after completion</small>
                            </div>
                            
                            <button type="submit" name="submit" class="btn btn-primary btn-lg btn-block">
                                <i class="fas fa-paper-plane"></i> Submit Booking Request
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-user-tie"></i> Worker Details</h5>
                    </div>
                    <div class="card-body text-center">
                        <img src="../images/<?php echo htmlentities($worker->Picture); ?>" 
                             alt="Worker" class="img-fluid rounded-circle mb-3" 
                             style="width: 150px; height: 150px; object-fit: cover;"
                             onerror="this.src='https://via.placeholder.com/150'">
                        <h4><?php echo htmlentities($worker->FullName); ?></h4>
                        <p class="text-muted"><?php echo htmlentities($worker->Category); ?></p>
                        <hr>
                        <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlentities($worker->City); ?>, <?php echo htmlentities($worker->State); ?></p>
                        <p><i class="fas fa-phone"></i> <?php echo htmlentities($worker->MobileNumber); ?></p>
                        <p><i class="fas fa-clock"></i> <?php echo htmlentities($worker->Experience); ?> years experience</p>
                        <p><i class="fas fa-rupee-sign"></i> ₹<?php echo htmlentities($worker->HourlyRate); ?>/hour</p>
                        <hr>
                        <p class="small"><?php echo htmlentities($worker->Description); ?></p>
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