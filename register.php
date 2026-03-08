<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');

if(isset($_POST['submit'])) {
    $fname = $_POST['fullname'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    
    // Check if email already exists
    $sql = "SELECT ID FROM tbluser WHERE Email=:email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    
    if($query->rowCount() > 0) {
        echo "<script>alert('Email already registered. Please use a different email.');</script>";
    } else {
        $sql = "INSERT INTO tbluser(FullName, Email, MobileNumber, Password, Address, City, State) 
                VALUES(:fname, :email, :mobile, :password, :address, :city, :state)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':fname', $fname, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->bindParam(':address', $address, PDO::PARAM_STR);
        $query->bindParam(':city', $city, PDO::PARAM_STR);
        $query->bindParam(':state', $state, PDO::PARAM_STR);
        $query->execute();
        
        $lastInsertId = $dbh->lastInsertId();
        if($lastInsertId) {
            echo "<script>alert('Registration successful! Please login.');</script>";
            echo "<script>window.location.href='index.php'</script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration - LSSEMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 50px 0;
        }
        .register-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .register-card {
            background: #fff;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .register-header h2 {
            color: #2c3e50;
            font-weight: 700;
        }
        .form-group label {
            font-weight: 600;
            color: #2c3e50;
        }
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
        }
    </style>
    <script>
    function checkpass() {
        if(document.getElementById('password').value != document.getElementById('confirmpassword').value) {
            document.getElementById('confirmpassword').setCustomValidity("Passwords don't match");
        } else {
            document.getElementById('confirmpassword').setCustomValidity('');
        }
    }
    </script>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <div class="register-card">
                <div class="register-header">
                    <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
                    <h2>User Registration</h2>
                    <p class="text-muted">Create your account to book services</p>
                </div>
                <form method="post">
                    <div class="form-group">
                        <label>Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="fullname" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Mobile Number <span class="text-danger">*</span></label>
                        <input type="text" name="mobile" class="form-control" pattern="[0-9]{10}" maxlength="10" required>
                    </div>
                    <div class="form-group">
                        <label>Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control" minlength="6" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" id="confirmpassword" class="form-control" onkeyup="checkpass();" required>
                    </div>
                    <div class="form-group">
                        <label>Address <span class="text-danger">*</span></label>
                        <textarea name="address" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>City <span class="text-danger">*</span></label>
                                <input type="text" name="city" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>State <span class="text-danger">*</span></label>
                                <input type="text" name="state" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary btn-block btn-register">
                        <i class="fas fa-user-check"></i> Register
                    </button>
                </form>
                <div class="text-center mt-3">
                    <p>Already have an account? <a href="index.php">Login here</a></p>
                    <a href="../index.php" class="text-muted"><i class="fas fa-arrow-left"></i> Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>