<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../includes/dbconnection.php');

if(strlen($_SESSION['lssemsuid']) == 0) {
    header('location:index.php');
    exit();
}

// Add notification function if not exists
if(!function_exists('addNotification')) {
    function addNotification($dbh, $userType, $userId, $title, $message, $bookingId = null) {
        try {
            $sql = "INSERT INTO tblnotifications (UserType, UserID, Title, Message, BookingID, CreatedAt) 
                    VALUES (:usertype, :userid, :title, :message, :bookingid, NOW())";
            $query = $dbh->prepare($sql);
            $query->bindParam(':usertype', $userType, PDO::PARAM_STR);
            $query->bindParam(':userid', $userId, PDO::PARAM_STR);
            $query->bindParam(':title', $title, PDO::PARAM_STR);
            $query->bindParam(':message', $message, PDO::PARAM_STR);
            $query->bindParam(':bookingid', $bookingId, PDO::PARAM_STR);
            $query->execute();
            return true;
        } catch(Exception $e) {
            error_log("Notification error: " . $e->getMessage());
            return false;
        }
    }
}

$userid = $_SESSION['lssemsuid'];
$bookingid = isset($_GET['id']) ? $_GET['id'] : '';

if(empty($bookingid)) {
    echo "<script>alert('Invalid booking ID');</script>";
    echo "<script>window.location.href='my-bookings.php'</script>";
    exit();
}

// Get booking details with user info and worker hourly rate - Check WorkerStatus = Completed
$sql = "SELECT b.*, w.FullName as WorkerName, w.Picture, w.Category, w.HourlyRate, 
        u.FullName as UserName, u.Email, u.MobileNumber 
        FROM tblbooking b 
        LEFT JOIN tblworker w ON b.WorkerID = w.ID 
        LEFT JOIN tbluser u ON b.UserID = u.ID 
        WHERE b.ID=:bid AND b.UserID=:uid AND b.WorkerStatus='Completed'";
$query = $dbh->prepare($sql);
$query->bindParam(':bid', $bookingid, PDO::PARAM_STR);
$query->bindParam(':uid', $userid, PDO::PARAM_STR);
$query->execute();
$booking = $query->fetch(PDO::FETCH_OBJ);

if($query->rowCount() == 0) {
    echo "<script>alert('Booking not found or work not completed yet by worker');</script>";
    echo "<script>window.location.href='my-bookings.php'</script>";
    exit();
}

// Check if already paid
if($booking->PaymentStatus == 'Paid') {
    echo "<script>alert('Payment already completed for this booking');</script>";
    echo "<script>window.location.href='my-bookings.php'</script>";
    exit();
}

// Calculate amount based on actual work duration with MINIMUM 1 HOUR RULE
$hourlyRate = floatval($booking->HourlyRate);
$actualHours = 0;
$totalAmount = 0;
$isMinimumWage = false;

// Check if TotalHours is set (worker filled actual duration)
if(isset($booking->TotalHours) && $booking->TotalHours > 0) {
    $actualHours = floatval($booking->TotalHours);
} else if(isset($booking->StartTime) && isset($booking->EndTime)) {
    // Calculate from start and end time
    $start = strtotime($booking->StartTime);
    $end = strtotime($booking->EndTime);
    $actualHours = ($end - $start) / 3600; // Convert seconds to hours
}

// APPLY MINIMUM WAGE RULE: If work duration is less than 1 hour, charge for 1 hour
if($actualHours > 0 && $actualHours < 1) {
    $totalAmount = $hourlyRate; // Charge full hourly rate (minimum wage)
    $isMinimumWage = true;
} else if($actualHours >= 1) {
    $totalAmount = $actualHours * $hourlyRate; // Normal calculation
} else {
    // Fallback to original estimated amount or hourly rate
    $totalAmount = floatval($booking->Amount);
    if($totalAmount < $hourlyRate) {
        $totalAmount = $hourlyRate; // Ensure minimum wage
        $isMinimumWage = true;
    }
}

// Ensure totalAmount is never less than hourly rate
if($totalAmount < $hourlyRate) {
    $totalAmount = $hourlyRate;
    $isMinimumWage = true;
}

// Process Payment
if(isset($_POST['pay_now'])) {
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';
    
    if(empty($payment_method)) {
        echo "<script>alert('Please select a payment method');</script>";
    } else {
        try {
            // Update booking with payment info and actual amount
            $sql = "UPDATE tblbooking SET 
                    Amount=:amount,
                    PaymentStatus='Paid', 
                    PaymentMethod=:method, 
                    PaymentDate=NOW()
                    WHERE ID=:bid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':bid', $bookingid, PDO::PARAM_STR);
            $query->bindParam(':method', $payment_method, PDO::PARAM_STR);
            $query->bindParam(':amount', $totalAmount, PDO::PARAM_STR);
            $query->execute();
            
            // Update overall booking status to Completed
            $sql2 = "UPDATE tblbooking SET Status='Completed' WHERE ID=:bid";
            $query2 = $dbh->prepare($sql2);
            $query2->bindParam(':bid', $bookingid, PDO::PARAM_STR);
            $query2->execute();
            
            // Notify Worker
            addNotification($dbh, 'Worker', $booking->WorkerID, 
                'Payment Received', 
                'Payment of ₹'.$totalAmount.' received for booking #'.$booking->BookingNumber.'. Transaction completed successfully.', 
                $bookingid);
            
            // Redirect to success page
            header("Location: payment-success.php?booking=".$bookingid);
            exit();
        } catch(Exception $e) {
            echo "<script>alert('Payment processing error: " . addslashes($e->getMessage()) . "');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - LSSEMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <?php include('../includes/header.php'); ?>

    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-money-check-alt"></i> Complete Payment</h1>
            <p class="text-white">Pay for your completed service</p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-check-circle"></i> Work Completed Successfully!</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success">
                            <i class="fas fa-info-circle"></i> The worker has marked this job as completed. 
                            Please review the work details below and complete the payment.
                        </div>

                        <?php if($isMinimumWage) { ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> <strong>Minimum Wage Applied:</strong> 
                            Jobs completed in less than 1 hour are charged at the minimum rate of 1 hour to ensure fair compensation for the worker.
                        </div>
                        <?php } ?>

                        <table class="table table-bordered">
                            <tr>
                                <td><strong>Booking Number:</strong></td>
                                <td><?php echo htmlentities($booking->BookingNumber); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Service Date:</strong></td>
                                <td><?php echo date('d M Y', strtotime($booking->ServiceDate)); ?> at <?php echo htmlentities($booking->ServiceTime); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Work Description:</strong></td>
                                <td><?php echo htmlentities($booking->WorkDescription); ?></td>
                            </tr>
                            <?php if(isset($booking->StartTime) && isset($booking->EndTime)) { ?>
                            <tr>
                                <td><strong>Work Duration:</strong></td>
                                <td>
                                    Started: <?php echo date('h:i A', strtotime($booking->StartTime)); ?><br>
                                    Completed: <?php echo date('h:i A', strtotime($booking->EndTime)); ?>
                                    <?php if($actualHours > 0) { ?>
                                        <br><strong>Actual Time: <?php echo number_format($actualHours, 2); ?> hours</strong>
                                        <?php if($isMinimumWage && $actualHours < 1) { ?>
                                            <br><span class="badge badge-warning">Billed as: 1.00 hour (Minimum Wage)</span>
                                        <?php } ?>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                            <tr class="table-info">
                                <td><strong>Hourly Rate:</strong></td>
                                <td>₹<?php echo number_format($hourlyRate, 2); ?> per hour</td>
                            </tr>
                            <?php if($actualHours > 0) { ?>
                            <tr class="table-light">
                                <td><strong>Calculation:</strong></td>
                                <td>
                                    <?php if($isMinimumWage && $actualHours < 1) { ?>
                                        1.00 hour (minimum) × ₹<?php echo number_format($hourlyRate, 2); ?>/hour
                                        <br><small class="text-muted">Actual work time: <?php echo number_format($actualHours, 2); ?> hours</small>
                                    <?php } else { ?>
                                        <?php echo number_format($actualHours, 2); ?> hours × ₹<?php echo number_format($hourlyRate, 2); ?>/hour
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                            <tr class="table-warning">
                                <td><strong>Total Amount to Pay:</strong></td>
                                <td><h4 class="text-danger mb-0">₹<?php echo number_format($totalAmount, 2); ?></h4></td>
                            </tr>
                        </table>

                        <?php if($booking->Remark) { ?>
                        <div class="alert alert-info">
                            <strong><i class="fas fa-comment"></i> Worker's Remark:</strong><br>
                            <?php echo htmlentities($booking->Remark); ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-credit-card"></i> Select Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" id="paymentForm">
                            <div class="form-group">
                                
                                <div class="custom-control custom-radio mb-3">
                                    <input type="radio" id="razorpay" name="payment_method" value="Razorpay" class="custom-control-input">
                                    <label class="custom-control-label" for="razorpay">
                                        <i class="fas fa-credit-card text-primary"></i> <strong>Razorpay</strong> - UPI, Card, Net Banking, Wallet
                                    </label>
                                </div>

                                <div class="custom-control custom-radio mb-3">
                                    <input type="radio" id="demo" name="payment_method" value="Demo Payment" class="custom-control-input" checked>
                                    <label class="custom-control-label" for="demo">
                                        <i class="fas fa-check-circle text-success"></i> <strong>Demo Payment</strong> - For Testing (Instant Success)
                                    </label>
                                </div>
                                
                                <div class="custom-control custom-radio mb-3">
                                    <input type="radio" id="cash" name="payment_method" value="Cash" class="custom-control-input">
                                    <label class="custom-control-label" for="cash">
                                        <i class="fas fa-money-bill-wave text-success"></i> <strong>Cash</strong> - Pay to Worker Directly
                                    </label>
                                </div>
                            </div>

                            <div class="alert alert-primary" id="razorpayNote" style="display:none;">
                                <i class="fas fa-shield-alt"></i> <strong>Secure Payment:</strong> 
                                Complete your payment securely through Razorpay. All major payment methods accepted.
                            </div>

                            <div class="alert alert-warning" id="demoNote">
                                <i class="fas fa-exclamation-triangle"></i> <strong>Demo Mode:</strong> 
                                This will simulate a successful payment instantly for testing purposes.
                            </div>

                            <div class="alert alert-info" id="cashNote" style="display:none;">
                                <i class="fas fa-info-circle"></i> <strong>Cash Payment:</strong> 
                                You've chosen to pay directly to the worker in cash. Make sure to get a proper receipt.
                            </div>

                            <button type="submit" name="pay_now" id="payButton" class="btn btn-success btn-lg btn-block">
                                <i class="fas fa-lock"></i> Pay ₹<?php echo number_format($totalAmount, 2); ?> Now
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
                        <?php if(!empty($booking->Picture)): ?>
                            <img src="../images/<?php echo htmlentities($booking->Picture); ?>" 
                                 alt="Worker" class="rounded-circle mb-3" 
                                 style="width: 120px; height: 120px; object-fit: cover;"
                                 onerror="this.src='https://via.placeholder.com/120'">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/120" alt="Worker" class="rounded-circle mb-3" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        <?php endif; ?>
                        <h5><?php echo htmlentities($booking->WorkerName); ?></h5>
                        <p class="text-muted mb-0"><?php echo htmlentities($booking->Category); ?></p>
                        <hr>
                        <div class="text-left">
                            <p class="mb-2"><i class="fas fa-rupee-sign text-success"></i> <strong>₹<?php echo number_format($hourlyRate, 2); ?>/hour</strong></p>
                            <?php if($actualHours > 0) { ?>
                            <p class="mb-0">
                                <i class="fas fa-clock text-primary"></i> 
                                <?php if($isMinimumWage && $actualHours < 1) { ?>
                                    Worked: <strong><?php echo number_format($actualHours, 2); ?> hours</strong><br>
                                    <small class="text-muted">Charged: 1.00 hour (minimum)</small>
                                <?php } else { ?>
                                    Worked: <strong><?php echo number_format($actualHours, 2); ?> hours</strong>
                                <?php } ?>
                            </p>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="card mt-3 bg-light">
                    <div class="card-body">
                        <h6><i class="fas fa-info-circle text-primary"></i> Minimum Wage Policy</h6>
                        <p class="small mb-0">
                            To ensure fair compensation, all jobs are charged at a minimum of <strong>1 hour</strong>, 
                            even if completed faster. This protects workers' earnings and maintains service quality.
                        </p>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <h6><i class="fas fa-shield-alt"></i> Secure Payment</h6>
                        <ul class="small mb-0 pl-3">
                            <li>All transactions are encrypted</li>
                            <li>Your payment info is protected</li>
                            <li>Secure payment gateway</li>
                            <li>No data stored on our servers</li>
                        </ul>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        // Toggle payment method notes
        $('input[name="payment_method"]').change(function() {
            var method = $(this).val();
            
            // Hide all notes
            $('#demoNote, #razorpayNote, #cashNote').hide();
            
            // Show relevant note
            if(method == 'Demo Payment') {
                $('#demoNote').show();
            } else if(method == 'Razorpay') {
                $('#razorpayNote').show();
            } else if(method == 'Cash') {
                $('#cashNote').show();
            }
        });

        // Handle form submission
        $('#paymentForm').submit(function(e) {
            var selectedMethod = $('input[name="payment_method"]:checked').val();
            
            // If Razorpay selected, prevent default and open Razorpay
            if(selectedMethod == 'Razorpay') {
                e.preventDefault();
                initiateRazorpayPayment();
            }
            // For demo and cash, form will submit normally
        });

        function initiateRazorpayPayment() {
            var amountToPay = <?php echo $totalAmount * 100; ?>; // Amount in paise
            
            var options = {
                "key": "rzp_test_YOUR_KEY_HERE", // Replace with your actual Razorpay key
                "amount": amountToPay,
                "currency": "INR",
                "name": "LSSEMS",
                "description": "Payment for Booking #<?php echo $booking->BookingNumber; ?>",
                "image": "../images/logo.png",
                "handler": function (response){
                    // Payment Success
                    $('#payButton').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing Payment...');
                    
                    // Send payment data to server
                    $.ajax({
                        url: 'process-payment.php',
                        type: 'POST',
                        data: {
                            booking_id: <?php echo $bookingid; ?>,
                            payment_id: response.razorpay_payment_id,
                            payment_signature: response.razorpay_signature,
                            payment_method: 'Razorpay',
                            amount: amountToPay / 100
                        },
                        success: function(response) {
                            try {
                                var data = JSON.parse(response);
                                if(data.status == 'success') {
                                    window.location.href = 'payment-success.php?booking=<?php echo $bookingid; ?>';
                                } else {
                                    alert('Error: ' + data.message);
                                    $('#payButton').prop('disabled', false).html('<i class="fas fa-lock"></i> Pay ₹<?php echo number_format($totalAmount, 2); ?> Now');
                                }
                            } catch(e) {
                                // Even if there's a parsing error, payment was successful
                                window.location.href = 'payment-success.php?booking=<?php echo $bookingid; ?>';
                            }
                        },
                        error: function(xhr, status, error) {
                            // Even if there's a server error, payment was successful
                            alert('Payment successful! Redirecting to confirmation page...');
                            window.location.href = 'payment-success.php?booking=<?php echo $bookingid; ?>';
                        }
                    });
                },
                "prefill": {
                    "name": "<?php echo htmlentities($booking->UserName); ?>",
                    "email": "<?php echo htmlentities($booking->Email ?? ''); ?>",
                    "contact": "<?php echo htmlentities($booking->MobileNumber ?? ''); ?>"
                },
                "notes": {
                    "booking_id": "<?php echo $booking->BookingNumber; ?>",
                    "payment_type": "Service Payment"
                },
                "theme": {
                    "color": "#667eea"
                },
                "modal": {
                    "ondismiss": function(){
                        $('#payButton').prop('disabled', false);
                        console.log('Payment cancelled by user');
                    }
                }
            };
            
            var rzp = new Razorpay(options);
            
            rzp.on('payment.failed', function (response){
                alert('Payment Failed: ' + response.error.description);
                $('#payButton').prop('disabled', false);
            });
            
            rzp.open();
        }
    });
    </script>
</body>
</html>