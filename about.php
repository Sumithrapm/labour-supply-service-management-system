<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - LSSEMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include('includes/header.php'); ?>
    
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-info-circle"></i> About Us</h1>
            <p class="text-white">Learn more about LSSEMS</p>
        </div>
    </div>

    <div class="container py-5">
        <?php
        $sql = "SELECT * FROM tblpage WHERE PageType='aboutus'";
        $query = $dbh->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        
        if($query->rowCount() > 0) { ?>
            <div class="row">
                <div class="col-md-8">
                    <h2 class="mb-4"><?php echo htmlentities($result->PageTitle); ?></h2>
                    <div class="content">
                        <?php echo $result->PageDescription; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5><i class="fas fa-bullseye"></i> Our Mission</h5>
                            <p>To connect skilled workers with customers efficiently and transparently.</p>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5><i class="fas fa-eye"></i> Our Vision</h5>
                            <p>To be the leading platform for labor supply and service management.</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="alert alert-warning">About page content not available</div>
        <?php } ?>
    </div>

    <?php include('includes/footer.php'); ?>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>