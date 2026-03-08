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
    <title>LSSEMS - Labor Supply & Service Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include_once('includes/header.php');?>
<!-- Hero Section with Auto-Sliding Images -->
<section class="hero-section p-0">
    <div class="container-fluid px-0">
        <div class="row no-gutters align-items-center">

            <!-- Left Content -->
            <div class="col-md-6 p-5">
                <h2 class="hero-title">Find Skilled Workers for Any Job @ Any Where</h2>
                <p class="hero-subtitle">Connect with verified professionals for all your service needs</p>
                
                <div class="hero-buttons mt-4">
                    <a href="category.php" class="btn btn-primary btn-lg mr-3 mb-2">
                        <i class="fas fa-th-large mr-2"></i>Browse Categories
                    </a>
                    <a href="worker/register.php" class="btn btn-outline-primary btn-lg mb-2">
                        <i class="fas fa-user-plus mr-2"></i>Register as Worker
                    </a>
                </div>
            </div>

            <!-- Right Carousel -->
            <div class="col-md-6 px-0">
                <div id="heroCarousel" class="carousel slide" data-ride="carousel" data-interval="2000">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=1600&h=700&fit=crop" class="w-100" alt="Plumber">
                        </div>
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1600&h=700&fit=crop" class="w-100" alt="Electrician">
                        </div>
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1621905251918-48416bd8575a?w=1600&h=700&fit=crop" class="w-100" alt="Carpenter">
                        </div>
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=1600&h=700&fit=crop" class="w-100" alt="Cleaning">
                        </div>
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=1600&h=700&fit=crop" class="w-100" alt="Construction">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>



<!-- Search Section -->
    <section class="search-section">
        <div class="container">
            <div class="search-box">
                <form action="user/search-workers.php" method="POST">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" name="location" class="form-control form-control-lg" placeholder="Enter your location (optional)">
                            <small class="form-text text-muted">Leave blank to search all locations</small>
                        </div>
                        <div class="col-md-5">
                            <select name="categories" class="form-control form-control-lg" required>
                                <option value="">Select Category</option>
                                <?php 
                                $sql = "SELECT * FROM tblcategory ORDER BY Category ASC";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                if($query->rowCount() > 0) {
                                    foreach($results as $row) { ?>
                                        <option value="<?php echo htmlentities($row->Category); ?>">
                                            <?php echo htmlentities($row->Category); ?>
                                        </option>
                                <?php }} ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" name="search" class="btn btn-primary btn-lg btn-block">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- Categories Section -->
    <section class="categories-section py-5">
        <div class="container">
            <div class="section-title text-center mb-5">
                <h2>Popular Categories</h2>
                <p>Browse through our most requested service categories</p>
            </div>
            <div class="row">
                <?php
                $sql = "SELECT c.*, COUNT(w.ID) as worker_count 
                        FROM tblcategory c 
                        LEFT JOIN tblworker w ON c.Category = w.Category AND w.Status = 'Approved'
                        GROUP BY c.ID 
                        ORDER BY worker_count DESC 
                        LIMIT 8";
                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                
                $icons = ['wrench', 'bolt', 'hammer', 'paint-brush', 'broom', 'leaf', 'car', 'utensils'];
                $colors = ['primary', 'success', 'warning', 'danger', 'info', 'dark', 'secondary', 'purple'];
                
                if($query->rowCount() > 0) {
                    $cnt = 0;
                    foreach($results as $row) { ?>
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="category-card text-center">
                                <div class="category-icon bg-<?php echo $colors[$cnt % 8]; ?>">
                                    <i class="fas fa-<?php echo $icons[$cnt % 8]; ?>"></i>
                                </div>
                                <h5><?php echo htmlentities($row->Category); ?></h5>
                                <p class="text-muted"><?php echo htmlentities($row->worker_count); ?> Workers</p>
                                <a href="user/search-workers.php?category=<?php echo urlencode($row->Category); ?>" class="btn btn-sm btn-outline-primary">View Workers</a>
                            </div>
                        </div>
                <?php $cnt++; }} ?>
            </div>
            <div class="text-center mt-4">
                <a href="category.php" class="btn btn-primary">View All Categories</a>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works py-5 bg-light">
        <div class="container">
            <div class="section-title text-center mb-5">
                <h2>How It Works</h2>
                <p>Simple steps to get your work done</p>
            </div>
            <div class="row">
                <div class="col-md-3 text-center">
                    <div class="step-icon">
                        <i class="fas fa-search fa-3x text-primary"></i>
                    </div>
                    <h5 class="mt-3">Search</h5>
                    <p>Find workers by category and location</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="step-icon">
                        <i class="fas fa-user-check fa-3x text-success"></i>
                    </div>
                    <h5 class="mt-3">Choose</h5>
                    <p>Select from verified professionals</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="step-icon">
                        <i class="fas fa-calendar-check fa-3x text-warning"></i>
                    </div>
                    <h5 class="mt-3">Book</h5>
                    <p>Schedule your service appointment</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="step-icon">
                        <i class="fas fa-thumbs-up fa-3x text-info"></i>
                    </div>
                    <h5 class="mt-3">Get Done</h5>
                    <p>Professional service delivery</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="stats-section py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="fas fa-users fa-3x mb-3 text-primary"></i>
                        <?php
                        $sql = "SELECT COUNT(*) as total FROM tblworker WHERE Status='Approved'";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $result = $query->fetch(PDO::FETCH_OBJ);
                        ?>
                        <h2><?php echo $result->total; ?>+</h2>
                        <p>Verified Workers</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="fas fa-briefcase fa-3x mb-3 text-success"></i>
                        <?php
                        $sql = "SELECT COUNT(*) as total FROM tblbooking WHERE Status='Completed'";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $result = $query->fetch(PDO::FETCH_OBJ);
                        ?>
                        <h2><?php echo $result->total; ?>+</h2>
                        <p>Jobs Completed</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="fas fa-smile fa-3x mb-3 text-warning"></i>
                        <?php
                        $sql = "SELECT COUNT(*) as total FROM tbluser";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $result = $query->fetch(PDO::FETCH_OBJ);
                        ?>
                        <h2><?php echo $result->total; ?>+</h2>
                        <p>Happy Customers</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="fas fa-star fa-3x mb-3 text-info"></i>
                        <?php
                        $sql = "SELECT AVG(Rating) as avg_rating FROM tblreview";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $result = $query->fetch(PDO::FETCH_OBJ);
                        ?>
                        <h2><?php echo number_format($result->avg_rating, 1); ?></h2>
                        <p>Average Rating</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include_once('includes/footer.php');?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>