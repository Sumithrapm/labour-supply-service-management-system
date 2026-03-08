<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../includes/dbconnection.php');

if(strlen($_SESSION['lssemsuid']) == 0) {
    header('location:index.php');
    exit();
}

$userid = $_SESSION['lssemsuid'];
$bookingid = isset($_GET['booking']) ? $_GET['booking'] : '';

if(empty($bookingid)) {
    echo "<script>alert('No booking ID provided');</script>";
    echo "<script>window.location.href='my-bookings.php'</script>";
    exit();
}

// Get booking details
$sql = "SELECT b.*, w.FullName as WorkerName, w.Picture, w.Category, w.MobileNumber as WorkerMobile 
        FROM tblbooking b 
        LEFT JOIN tblworker w ON b.WorkerID = w.ID 
        WHERE b.ID=:bid AND b.UserID=:uid AND b.PaymentStatus='Paid'";
$query = $dbh->prepare($sql);
$query->bindParam(':bid', $bookingid, PDO::PARAM_STR);
$query->bindParam(':uid', $userid, PDO::PARAM_STR);
$query->execute();
$booking = $query->fetch(PDO::FETCH_OBJ);

if($query->rowCount() == 0) {
    echo "<script>alert('Booking not found or payment not completed');</script>";
    echo "<script>window.location.href='my-bookings.php'</script>";
    exit();
}

// Calculate amounts
$totalAmount = floatval($booking->Amount);
$advancePaid = isset($booking->AdvanceAmount) ? floatval($booking->AdvanceAmount) : 0;
$paidAmount = $totalAmount - $advancePaid;
if($paidAmount <= 0) {
    $paidAmount = $totalAmount;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - LSSEMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        
        .success-animation {
            animation: scaleIn 0.5s ease-in-out;
        }
        
        @keyframes scaleIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .success-checkmark {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }
        
        .success-checkmark i {
            font-size: 60px;
            color: #28a745;
        }
        
        .payment-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            background: white;
        }
        
        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            background: #667eea;
            animation: confetti-fall 3s linear forwards;
        }
        
        @keyframes confetti-fall {
            to {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }
        
        .page-title {
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        @media print {
            body {
                background: white !important;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Success Animation -->
                <div class="text-center mb-4 success-animation">
                    <div class="success-checkmark mb-4">
                        <i class="fas fa-check"></i>
                    </div>
                    <h1 class="page-title mb-3">
                        <i class="fas fa-check-circle"></i> Payment Successful!
                    </h1>
                    <p class="lead text-white">Your payment has been processed successfully</p>
                </div>

                <!-- Payment Details Card -->
                <div class="card payment-card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-receipt"></i> Payment Receipt</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <p class="text-muted mb-1">Booking Number</p>
                                <h5 class="text-dark">#<?php echo htmlentities($booking->BookingNumber); ?></h5>
                            </div>
                            <div class="col-6 text-right">
                                <p class="text-muted mb-1">Payment Date</p>
                                <h5 class="text-dark"><?php echo date('d M Y', strtotime($booking->PaymentDate)); ?></h5>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-3 text-center">
                                <?php if(!empty($booking->Picture)): ?>
                                    <img src="../images/<?php echo htmlentities($booking->Picture); ?>" 
                                         alt="Worker" class="rounded-circle mb-2" 
                                         style="width: 80px; height: 80px; object-fit: cover;"
                                         onerror="this.src='https://via.placeholder.com/80'">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/80" alt="Worker" class="rounded-circle mb-2">
                                <?php endif; ?>
                            </div>
                            <div class="col-md-9">
                                <h6 class="mb-1"><?php echo htmlentities($booking->WorkerName); ?></h6>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-briefcase"></i> <?php echo htmlentities($booking->Category); ?>
                                </p>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-phone"></i> <?php echo htmlentities($booking->WorkerMobile); ?>
                                </p>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-2">
                            <div class="col-8">
                                <p class="mb-0"><strong>Service Date:</strong></p>
                            </div>
                            <div class="col-4 text-right">
                                <p class="mb-0"><?php echo date('d M Y', strtotime($booking->ServiceDate)); ?></p>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-8">
                                <p class="mb-0"><strong>Service Time:</strong></p>
                            </div>
                            <div class="col-4 text-right">
                                <p class="mb-0"><?php echo htmlentities($booking->ServiceTime); ?></p>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-12">
                                <p class="mb-0"><strong>Work Description:</strong></p>
                                <p class="text-muted"><?php echo htmlentities($booking->WorkDescription); ?></p>
                            </div>
                        </div>

                        <?php if(isset($booking->TotalHours) && $booking->TotalHours > 0) { ?>
                        <div class="row mb-2">
                            <div class="col-8">
                                <p class="mb-0"><strong>Hours Worked:</strong></p>
                            </div>
                            <div class="col-4 text-right">
                                <p class="mb-0"><?php echo $booking->TotalHours; ?> hours</p>
                            </div>
                        </div>
                        <?php } ?>

                        <hr>

                        <div class="row mb-2">
                            <div class="col-8">
                                <p class="mb-0">Total Amount:</p>
                            </div>
                            <div class="col-4 text-right">
                                <p class="mb-0">₹<?php echo number_format($totalAmount, 2); ?></p>
                            </div>
                        </div>

                        <?php if($advancePaid > 0) { ?>
                        <div class="row mb-2 text-success">
                            <div class="col-8">
                                <p class="mb-0">Advance Paid:</p>
                            </div>
                            <div class="col-4 text-right">
                                <p class="mb-0">- ₹<?php echo number_format($advancePaid, 2); ?></p>
                            </div>
                        </div>
                        <?php } ?>

                        <hr class="border-success">

                        <div class="row">
                            <div class="col-8">
                                <h5 class="mb-0 text-success"><strong>Amount Paid:</strong></h5>
                            </div>
                            <div class="col-4 text-right">
                                <h4 class="mb-0 text-success"><strong>₹<?php echo number_format($paidAmount, 2); ?></strong></h4>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle"></i> 
                                    <strong>Payment Method:</strong> <?php echo htmlentities($booking->PaymentMethod); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card payment-card mb-4 no-print">
                    <div class="card-body text-center">
                        <h5 class="mb-3">What would you like to do next?</h5>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <a href="review.php?booking_id=<?php echo $bookingid; ?>" class="btn btn-warning btn-block btn-lg">
                                    <i class="fas fa-star"></i><br>
                                    <small>Write Review</small>
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="my-bookings.php" class="btn btn-primary btn-block btn-lg">
                                    <i class="fas fa-list"></i><br>
                                    <small>My Bookings</small>
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="search-workers.php" class="btn btn-success btn-block btn-lg">
                                    <i class="fas fa-search"></i><br>
                                    <small>Book Again</small>
                                </a>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button onclick="window.print()" class="btn btn-outline-secondary">
                                <i class="fas fa-print"></i> Print Receipt
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Thank You Message -->
                <div class="card payment-card no-print">
                    <div class="card-body text-center bg-light">
                        <i class="fas fa-heart text-danger fa-2x mb-3"></i>
                        <h5>Thank You for Using LSSEMS!</h5>
                        <p class="text-muted mb-0">
                            We hope you had a great experience with our service. 
                            Your feedback helps us improve and serve you better.
                        </p>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-headset"></i> Need help? Contact our support team
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Confetti effect
    function createConfetti() {
        const colors = ['#667eea', '#764ba2', '#f093fb', '#4facfe', '#43e97b', '#fa709a', '#ffd89b'];
        for(let i = 0; i < 50; i++) {
            setTimeout(() => {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * window.innerWidth + 'px';
                confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDelay = Math.random() * 3 + 's';
                confetti.style.top = '-10px';
                document.body.appendChild(confetti);
                
                setTimeout(() => confetti.remove(), 3000);
            }, i * 30);
        }
    }
    
    // Run confetti on page load
    window.addEventListener('load', function() {
        createConfetti();
        console.log('Payment success page loaded');
    });
    </script>
</body>
</html>