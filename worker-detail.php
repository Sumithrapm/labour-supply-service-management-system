<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');

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

// Get worker reviews
$sql = "SELECT r.*, u.FullName as UserName 
        FROM tblreview r 
        LEFT JOIN tbluser u ON r.UserID = u.ID 
        WHERE r.WorkerID=:wid 
        ORDER BY r.ReviewDate DESC";
$query = $dbh->prepare($sql);
$query->bindParam(':wid', $workerid, PDO::PARAM_STR);
$query->execute();
$reviews = $query->fetchAll(PDO::FETCH_OBJ);

// Calculate average rating
$sql = "SELECT AVG(Rating) as avg_rating, COUNT(*) as total_reviews FROM tblreview WHERE WorkerID=:wid";
$query = $dbh->prepare($sql);
$query->bindParam(':wid', $workerid, PDO::PARAM_STR);
$query->execute();
$rating_data = $query->fetch(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Profile - LSSEMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include('../includes/header.php'); ?>

    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-user-tie"></i> Worker Profile</h1>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="../images/<?php echo htmlentities($worker->Picture); ?>" 
                             alt="Worker" class="img-fluid rounded-circle mb-3" 
                             style="width: 200px; height: 200px; object-fit: cover;"
                             onerror="this.src='https://via.placeholder.com/200'">
                        <h3><?php echo htmlentities($worker->FullName); ?></h3>
                        <p class="text-muted"><?php echo htmlentities($worker->Category); ?></p>
                        
                        <div class="mb-3">
                            <?php
                            $avg = $rating_data->avg_rating ? round($rating_data->avg_rating, 1) : 0;
                            for($i = 1; $i <= 5; $i++) {
                                if($i <= $avg) {
                                    echo '<i class="fas fa-star text-warning"></i>';
                                } else {
                                    echo '<i class="far fa-star text-warning"></i>';
                                }
                            }
                            ?>
                            <p class="mt-2"><?php echo $avg; ?>/5 (<?php echo $rating_data->total_reviews; ?> reviews)</p>
                        </div>
                        
                        <hr>
                        
                        <p><i class="fas fa-phone text-primary"></i> <?php echo htmlentities($worker->MobileNumber); ?></p>
                        <p><i class="fas fa-envelope text-primary"></i> <?php echo htmlentities($worker->Email); ?></p>
                        <p><i class="fas fa-map-marker-alt text-primary"></i> <?php echo htmlentities($worker->City); ?>, <?php echo htmlentities($worker->State); ?></p>
                        
                        <hr>
                        
                        <div class="alert alert-info">
                            <h6><i class="fas fa-rupee-sign"></i> Pricing</h6>
                            <p class="mb-1"><strong>Hourly Rate:</strong> ₹<?php echo htmlentities($worker->HourlyRate); ?>/hour</p>
                            <p class="mb-1"><strong>Daily Charges:</strong> ₹<?php echo htmlentities($worker->Charges); ?>/day</p>
                            <p class="mb-0"><strong>Advance Payment:</strong> ₹<?php echo htmlentities($worker->AdvancePayment); ?></p>
                        </div>
                        
                        <a href="book-worker.php?id=<?php echo $worker->ID; ?>" class="btn btn-success btn-lg btn-block">
                            <i class="fas fa-calendar-plus"></i> Book Now
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> About</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Experience:</strong> <?php echo htmlentities($worker->Experience); ?> years</p>
                        <p><strong>Category:</strong> <?php echo htmlentities($worker->Category); ?></p>
                        <p><strong>Description:</strong></p>
                        <p><?php echo htmlentities($worker->Description); ?></p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-star"></i> Reviews (<?php echo $rating_data->total_reviews; ?>)</h5>
                    </div>
                    <div class="card-body">
                        <?php if($query->rowCount() > 0) {
                            foreach($reviews as $review) { ?>
                                <div class="media mb-3 pb-3 border-bottom">
                                    <div class="media-body">
                                        <h6 class="mt-0"><?php echo htmlentities($review->UserName); ?></h6>
                                        <div class="text-warning mb-2">
                                            <?php
                                            for($i = 1; $i <= 5; $i++) {
                                                if($i <= $review->Rating) {
                                                    echo '<i class="fas fa-star"></i>';
                                                } else {
                                                    echo '<i class="far fa-star"></i>';
                                                }
                                            }
                                            ?>
                                        </div>
                                        <p class="mb-1"><?php echo htmlentities($review->Review); ?></p>
                                        <small class="text-muted"><?php echo date('d M Y', strtotime($review->ReviewDate)); ?></small>
                                    </div>
                                </div>
                        <?php }} else { ?>
                            <p class="text-muted">No reviews yet. Be the first to review!</p>
                        <?php } ?>
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