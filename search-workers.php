<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../includes/dbconnection.php');

// Initialize search variables
$category = '';
$location = '';
$searchPerformed = false;

// Handle form submission
if(isset($_POST['search'])) {
    $category = isset($_POST['categories']) ? trim($_POST['categories']) : '';
    $location = isset($_POST['location']) ? trim($_POST['location']) : '';
    $searchPerformed = true;
}
// Handle GET parameters (from external links)
elseif(isset($_GET['category']) || isset($_GET['location'])) {
    $category = isset($_GET['category']) ? trim($_GET['category']) : '';
    $location = isset($_GET['location']) ? trim($_GET['location']) : '';
    $searchPerformed = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Workers - LSSEMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .worker-card {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }
        
        .worker-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .worker-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        
        .worker-info {
            padding: 1.5rem;
        }
        
        .worker-name {
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .worker-category {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .search-card {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="page-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="fas fa-search"></i> Search Workers</h1>
                    <p class="mb-0">Find the perfect worker for your needs</p>
                </div>
                <button onclick="window.history.back()" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <!-- Search Form -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card search-card">
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-5 mb-3 mb-md-0">
                                    <label for="location" class="font-weight-bold">Location (Optional)</label>
                                    <input type="text" 
                                           name="location" 
                                           id="location"
                                           class="form-control" 
                                           placeholder="Enter city or location" 
                                           value="<?php echo htmlspecialchars($location); ?>">
                                    <small class="form-text text-muted">Leave blank to search all locations</small>
                                </div>
                                <div class="col-md-5 mb-3 mb-md-0">
                                    <label for="categories" class="font-weight-bold">Category <span class="text-danger">*</span></label>
                                    <select name="categories" id="categories" class="form-control" required>
                                        <option value="">-- Select Category --</option>
                                        <?php 
                                        $sql2 = "SELECT * FROM tblcategory ORDER BY Category ASC";
                                        $query2 = $dbh->prepare($sql2);
                                        $query2->execute();
                                        $results2 = $query2->fetchAll(PDO::FETCH_OBJ);
                                        foreach($results2 as $row2) { ?>
                                            <option value="<?php echo htmlspecialchars($row2->Category); ?>" 
                                                <?php if($category == $row2->Category) echo 'selected'; ?>>
                                                <?php echo htmlspecialchars($row2->Category); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="d-none d-md-block">&nbsp;</label>
                                    <button type="submit" name="search" class="btn btn-primary btn-block">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Section -->
        <div class="row">
            <?php
            if($searchPerformed && !empty($category)) {
                // Build query based on search criteria
                try {
                    if(!empty($location)) {
                        // Search with both category and location
                        $sql = "SELECT * FROM tblworker 
                                WHERE Status = 'Approved' 
                                AND Category = :cat 
                                AND City LIKE :loc 
                                ORDER BY FullName ASC";
                        $query = $dbh->prepare($sql);
                        $query->bindParam(':cat', $category, PDO::PARAM_STR);
                        $searchLocation = "%" . $location . "%";
                        $query->bindParam(':loc', $searchLocation, PDO::PARAM_STR);
                    } else {
                        // Search with category only
                        $sql = "SELECT * FROM tblworker 
                                WHERE Status = 'Approved' 
                                AND Category = :cat 
                                ORDER BY FullName ASC";
                        $query = $dbh->prepare($sql);
                        $query->bindParam(':cat', $category, PDO::PARAM_STR);
                    }
                    
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    $resultCount = $query->rowCount();
                    
                    if($resultCount > 0) {
                        echo '<div class="col-md-12 mb-3">';
                        echo '<div class="alert alert-info">';
                        echo '<i class="fas fa-info-circle"></i> Found <strong>' . $resultCount . '</strong> worker(s) matching your criteria';
                        echo '</div>';
                        echo '</div>';
                        
                        foreach($results as $row) { ?>
                            <div class="col-md-4 mb-4">
                                <div class="worker-card">
                                    <img src="../images/<?php echo htmlspecialchars($row->Picture); ?>" 
                                         alt="<?php echo htmlspecialchars($row->FullName); ?>" 
                                         class="worker-image"
                                         onerror="this.src='https://via.placeholder.com/300x250?text=No+Image'">
                                    <div class="worker-info">
                                        <h5 class="worker-name"><?php echo htmlspecialchars($row->FullName); ?></h5>
                                        <p class="worker-category">
                                            <i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($row->Category); ?>
                                        </p>
                                        <p class="text-muted mb-2">
                                            <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($row->City); ?>
                                        </p>
                                        <p class="text-muted mb-2">
                                            <i class="fas fa-phone"></i> <?php echo htmlspecialchars($row->MobileNumber); ?>
                                        </p>
                                        <p class="text-success font-weight-bold mb-2">
                                            <i class="fas fa-rupee-sign"></i> ₹<?php echo htmlspecialchars($row->Charges); ?>/day
                                        </p>
                                        <p class="text-muted mb-3">
                                            <i class="fas fa-clock"></i> <?php echo htmlspecialchars($row->Experience); ?> years exp.
                                        </p>
                                        <a href="worker-detail.php?id=<?php echo $row->ID; ?>" class="btn btn-info btn-sm btn-block mb-2">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                        <a href="book-worker.php?id=<?php echo $row->ID; ?>" class="btn btn-success btn-block">
                                            <i class="fas fa-calendar-plus"></i> Book Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    } else { ?>
                        <div class="col-md-12">
                            <div class="alert alert-warning text-center">
                                <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                                <h4>No Workers Found</h4>
                                <p class="mb-0">No workers found matching your search criteria.</p>
                                <p class="mb-0">
                                    <?php if(!empty($location)): ?>
                                        Try searching without location or select a different category.
                                    <?php else: ?>
                                        Try selecting a different category.
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    <?php }
                } catch(PDOException $e) {
                    echo '<div class="col-md-12">';
                    echo '<div class="alert alert-danger">';
                    echo '<i class="fas fa-exclamation-circle"></i> <strong>Error:</strong> ' . htmlspecialchars($e->getMessage());
                    echo '</div>';
                    echo '</div>';
                }
            } elseif($searchPerformed && empty($category)) {
                // Category not selected
                ?>
                <div class="col-md-12">
                    <div class="alert alert-danger text-center">
                        <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                        <h4>Category Required</h4>
                        <p class="mb-0">Please select a category to search for workers.</p>
                    </div>
                </div>
            <?php } else {
                // No search performed yet
                ?>
                <div class="col-md-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle fa-3x mb-3"></i>
                        <h4>Start Your Search</h4>
                        <p class="mb-0">Select a category and optionally a location to find available workers.</p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>