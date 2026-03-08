<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');

if(strlen($_SESSION['lssemsuid']) == 0) {
    header('location:index.php');
    exit();
}

$userid = $_SESSION['lssemsuid'];

// Update Profile
if(isset($_POST['submit'])) {
    $fname = $_POST['fullname'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    
    $sql = "UPDATE tbluser SET FullName=:fname, MobileNumber=:mobile, Address=:address, City=:city, State=:state WHERE ID=:uid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':fname', $fname, PDO::PARAM_STR);
    $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query->bindParam(':address', $address, PDO::PARAM_STR);
    $query->bindParam(':city', $city, PDO::PARAM_STR);
    $query->bindParam(':state', $state, PDO::PARAM_STR);
    $query->bindParam(':uid', $userid, PDO::PARAM_STR);
    $query->execute();
    
    echo "<script>alert('Profile updated successfully');</script>";
    echo "<script>window.location.href='profile.php'</script>";
}

// Change Password
if(isset($_POST['change_password'])) {
    $currentpwd = $_POST['currentpassword'];
    $newpwd = $_POST['newpassword'];
    
    $sql = "SELECT Password FROM tbluser WHERE ID=:uid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':uid', $userid, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    
    if(password_verify($currentpwd, $result->Password)) {
        $hashedpwd = password_hash($newpwd, PASSWORD_DEFAULT);
        $sql = "UPDATE tbluser SET Password=:pwd WHERE ID=:uid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':pwd', $hashedpwd, PDO::PARAM_STR);
        $query->bindParam(':uid', $userid, PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Password changed successfully');</script>";
    } else {
        echo "<script>alert('Current password is incorrect');</script>";
    }
}

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
    <title>My Profile - LSSEMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <script>
    function checkpass() {
        if(document.getElementById('newpassword').value != document.getElementById('confirmpassword').value) {
            document.getElementById('confirmpassword').setCustomValidity("Passwords don't match");
        } else {
            document.getElementById('confirmpassword').setCustomValidity('');
        }
    }
    </script>
</head>
<body>
    <?php include('../includes/header.php'); ?>

    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-user-circle"></i> My Profile</h1>
            <p class="text-white">Manage your account information</p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <!-- Profile Information -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user-edit"></i> Profile Information</h5>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="fullname" class="form-control" value="<?php echo htmlentities($user->FullName); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Email (Cannot be changed)</label>
                                <input type="email" class="form-control" value="<?php echo htmlentities($user->Email); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Mobile Number</label>
                                <input type="text" name="mobile" class="form-control" pattern="[0-9]{10}" maxlength="10" value="<?php echo htmlentities($user->MobileNumber); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <textarea name="address" class="form-control" rows="3" required><?php echo htmlentities($user->Address); ?></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input type="text" name="city" class="form-control" value="<?php echo htmlentities($user->City); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>State</label>
                                        <input type="text" name="state" class="form-control" value="<?php echo htmlentities($user->State); ?>" required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Profile
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0"><i class="fas fa-lock"></i> Change Password</h5>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="form-group">
                                <label>Current Password</label>
                                <input type="password" name="currentpassword" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="newpassword" id="newpassword" class="form-control" minlength="6" required>
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password</label>
                                <input type="password" id="confirmpassword" class="form-control" onkeyup="checkpass();" required>
                            </div>
                            <button type="submit" name="change_password" class="btn btn-warning">
                                <i class="fas fa-key"></i> Change Password
                            </button>
                        </form>
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