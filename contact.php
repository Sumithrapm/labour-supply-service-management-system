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
    <title>Contact Us - LSSEMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include('includes/header.php'); ?>
    
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-envelope"></i> Contact Us</h1>
            <p class="text-white">Get in touch with us</p>
        </div>
    </div>

    <div class="container py-5">
        <?php
        $sql = "SELECT * FROM tblpage WHERE PageType='contactus'";
        $query = $dbh->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        
        if($query->rowCount() > 0) { ?>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="fas fa-phone fa-3x text-primary mb-3"></i>
                            <h5>Phone</h5>
                            <p class="text-muted">+<?php echo htmlentities($result->MobileNumber); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="fas fa-envelope fa-3x text-success mb-3"></i>
                            <h5>Email</h5>
                            <p class="text-muted"><?php echo htmlentities($result->Email); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="fas fa-map-marker-alt fa-3x text-danger mb-3"></i>
                            <h5>Address</h5>
                            <p class="text-muted"><?php echo htmlentities($result->PageDescription); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="row mt-5">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-paper-plane"></i> Send us a Message</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Message</label>
                                <textarea name="message" class="form-control" rows="5" required></textarea>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-paper-plane"></i> Send Message
                            </button>
                        </form>
                        <?php
                        if(isset($_POST['submit'])) {
                            $name = $_POST['name'];
                            $email = $_POST['email'];
                            $message = $_POST['message'];
                            
                            $sql = "INSERT INTO tblcontact(Name, Email, Message) VALUES(:name, :email, :msg)";
                            $query = $dbh->prepare($sql);
                            $query->bindParam(':name', $name, PDO::PARAM_STR);
                            $query->bindParam(':email', $email, PDO::PARAM_STR);
                            $query->bindParam(':msg', $message, PDO::PARAM_STR);
                            $query->execute();
                            
                            if($dbh->lastInsertId()) {
                                echo "<script>alert('Message sent successfully!');</script>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>