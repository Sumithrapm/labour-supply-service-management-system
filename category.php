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
    <title>Categories - LSSEMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include('includes/header.php'); ?>
    
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-list"></i> Service Categories</h1>
            <p class="text-white">Browse all available service categories</p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <?php
            $sql = "SELECT c.*, COUNT(w.ID) as worker_count 
                    FROM tblcategory c 
                    LEFT JOIN tblworker w ON c.Category = w.Category AND w.Status = 'Approved'
                    GROUP BY c.ID 
                    ORDER BY c.Category ASC";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);
            
            // Function to get appropriate icon and color based on category name
            function getCategoryStyle($category) {
                $category = strtolower($category);
                
                // Plumbing related
                if(strpos($category, 'plumb') !== false || strpos($category, 'pipe') !== false) {
                    return ['icon' => 'wrench', 'color' => 'primary'];
                }
                // Electrical
                if(strpos($category, 'electric') !== false || strpos($category, 'wiring') !== false) {
                    return ['icon' => 'bolt', 'color' => 'warning'];
                }
                // Carpentry/Wood
                if(strpos($category, 'carpen') !== false || strpos($category, 'wood') !== false || strpos($category, 'furniture') !== false) {
                    return ['icon' => 'hammer', 'color' => 'danger'];
                }
                // Painting
                if(strpos($category, 'paint') !== false) {
                    return ['icon' => 'paint-roller', 'color' => 'info'];
                }
                // Cleaning
                if(strpos($category, 'clean') !== false || strpos($category, 'house') !== false) {
                    return ['icon' => 'broom', 'color' => 'success'];
                }
                // Gardening/Landscaping
                if(strpos($category, 'garden') !== false || strpos($category, 'landscap') !== false || strpos($category, 'lawn') !== false) {
                    return ['icon' => 'leaf', 'color' => 'success'];
                }
                // AC/HVAC
                if(strpos($category, 'ac') !== false || strpos($category, 'air') !== false || strpos($category, 'hvac') !== false || strpos($category, 'cooling') !== false) {
                    return ['icon' => 'fan', 'color' => 'info'];
                }
                // Appliance Repair
                if(strpos($category, 'appliance') !== false || strpos($category, 'repair') !== false) {
                    return ['icon' => 'tools', 'color' => 'dark'];
                }
                // Pest Control
                if(strpos($category, 'pest') !== false || strpos($category, 'termite') !== false) {
                    return ['icon' => 'bug', 'color' => 'danger'];
                }
                // Security
                if(strpos($category, 'security') !== false || strpos($category, 'cctv') !== false) {
                    return ['icon' => 'video', 'color' => 'dark'];
                }
                // Roofing
                if(strpos($category, 'roof') !== false) {
                    return ['icon' => 'home', 'color' => 'danger'];
                }
                // Flooring
                if(strpos($category, 'floor') !== false || strpos($category, 'tile') !== false) {
                    return ['icon' => 'th-large', 'color' => 'secondary'];
                }
                // Welding
                if(strpos($category, 'weld') !== false || strpos($category, 'metal') !== false) {
                    return ['icon' => 'fire', 'color' => 'warning'];
                }
                // Moving/Packers
                if(strpos($category, 'mov') !== false || strpos($category, 'pack') !== false || strpos($category, 'shift') !== false) {
                    return ['icon' => 'truck-moving', 'color' => 'primary'];
                }
                // Car/Auto
                if(strpos($category, 'car') !== false || strpos($category, 'auto') !== false || strpos($category, 'vehicle') !== false) {
                    return ['icon' => 'car', 'color' => 'primary'];
                }
                // Construction
                if(strpos($category, 'construct') !== false || strpos($category, 'mason') !== false || strpos($category, 'building') !== false) {
                    return ['icon' => 'hard-hat', 'color' => 'warning'];
                }
                // Catering/Food
                if(strpos($category, 'cater') !== false || strpos($category, 'food') !== false || strpos($category, 'cook') !== false) {
                    return ['icon' => 'utensils', 'color' => 'success'];
                }
                // Beauty/Salon
                if(strpos($category, 'beauty') !== false || strpos($category, 'salon') !== false || strpos($category, 'hair') !== false) {
                    return ['icon' => 'cut', 'color' => 'info'];
                }
                // Laundry/Dry Cleaning
                if(strpos($category, 'laundry') !== false || strpos($category, 'dry clean') !== false || strpos($category, 'wash') !== false) {
                    return ['icon' => 'tshirt', 'color' => 'primary'];
                }
                // Default
                return ['icon' => 'tools', 'color' => 'primary'];
            }
            
            if($query->rowCount() > 0) {
                foreach($results as $row) { 
                    $style = getCategoryStyle($row->Category);
                ?>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="category-card text-center">
                            <div class="category-icon bg-<?php echo $style['color']; ?>">
                                <i class="fas fa-<?php echo $style['icon']; ?>"></i>
                            </div>
                            <h5><?php echo htmlentities($row->Category); ?></h5>
                            <p class="text-muted"><?php echo htmlentities($row->worker_count); ?> Workers Available</p>
                            <?php if($row->Description) { ?>
                                <p class="small text-muted"><?php echo htmlentities(substr($row->Description, 0, 50)); ?>...</p>
                            <?php } ?>
                            <a href="user/search-workers.php?category=<?php echo urlencode($row->Category); ?>" class="btn btn-sm btn-outline-<?php echo $style['color']; ?>">
                                <i class="fas fa-eye"></i> View Workers
                            </a>
                        </div>
                    </div>
            <?php } } else { ?>
                <div class="col-md-12">
                    <div class="alert alert-info">No categories available</div>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>