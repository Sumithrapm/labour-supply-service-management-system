<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../includes/dbconnection.php');

/* ---------------- LOGIN CHECK ---------------- */
if (!isset($_SESSION['lssemsuid']) || $_SESSION['lssemsuid'] == '') {
    header("Location: index.php");
    exit();
}

$user_id   = $_SESSION['lssemsuid'];
$user_name = $_SESSION['name'] ?? 'User';

/* ---------------- HANDLE DELETE ---------------- */
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['booking_id'])) {
    $booking_id = intval($_GET['booking_id']);
    
    // Verify the review belongs to this user
    $check = "SELECT ID FROM tblreview WHERE BookingID = :bid AND UserID = :uid";
    $checkStmt = $dbh->prepare($check);
    $checkStmt->bindParam(':bid', $booking_id, PDO::PARAM_INT);
    $checkStmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $checkStmt->execute();
    
    if ($checkStmt->rowCount() > 0) {
        $delete = "DELETE FROM tblreview WHERE BookingID = :bid AND UserID = :uid";
        $deleteStmt = $dbh->prepare($delete);
        $deleteStmt->bindParam(':bid', $booking_id, PDO::PARAM_INT);
        $deleteStmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
        
        if ($deleteStmt->execute()) {
            $_SESSION['success_msg'] = "Review deleted successfully.";
        } else {
            $_SESSION['error_msg'] = "Failed to delete review.";
        }
    }
    
    header("Location: review.php?booking_id=" . $booking_id);
    exit();
}

/* ---------------- GET BOOKING ID ---------------- */
$booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;

if ($booking_id <= 0) {
    die("Invalid booking ID");
}

/* ---------------- FETCH BOOKING DETAILS ---------------- */
$sql = "
    SELECT b.*, 
           w.FullName AS worker_name,
           w.ID AS worker_id,
           w.Category AS service_name,
           r.ID as review_id,
           r.Rating as existing_rating,
           r.Review as existing_review,
           r.ReviewDate as review_date
    FROM tblbooking b
    JOIN tblworker w ON b.WorkerID = w.ID
    LEFT JOIN tblreview r ON r.BookingID = b.ID AND r.UserID = :user_id
    WHERE b.ID = :booking_id 
      AND b.UserID = :user_id 
      AND b.Status = 'Completed'
";

$query = $dbh->prepare($sql);
$query->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
$query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$query->execute();

if ($query->rowCount() == 0) {
    die("Booking not found or not eligible for review");
}

$booking = $query->fetch(PDO::FETCH_OBJ);

/* ---------------- CHECK IF EDITING ---------------- */
$is_editing = isset($_GET['edit']) && $_GET['edit'] == '1' && $booking->review_id;

/* ---------------- SUBMIT OR UPDATE REVIEW ---------------- */
$success_message = '';
$error_message = '';

// Display session messages
if (isset($_SESSION['success_msg'])) {
    $success_message = $_SESSION['success_msg'];
    unset($_SESSION['success_msg']);
}
if (isset($_SESSION['error_msg'])) {
    $error_message = $_SESSION['error_msg'];
    unset($_SESSION['error_msg']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating      = intval($_POST['rating']);
    $review_text = trim($_POST['review_text']);

    if ($rating < 1 || $rating > 5) {
        $error_message = "Please select a rating between 1 and 5.";
    } elseif (strlen($review_text) < 10) {
        $error_message = "Review must be at least 10 characters long.";
    } else {
        if ($booking->review_id && $is_editing) {
            // UPDATE existing review
            $update = "
                UPDATE tblreview 
                SET Rating = :rating, Review = :review, ReviewDate = NOW()
                WHERE ID = :review_id AND UserID = :uid
            ";
            
            $stmt = $dbh->prepare($update);
            $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
            $stmt->bindParam(':review', $review_text, PDO::PARAM_STR);
            $stmt->bindParam(':review_id', $booking->review_id, PDO::PARAM_INT);
            $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $success_message = "Your review has been updated successfully.";
                header("refresh:2;url=my-bookings.php");
            } else {
                $error_message = "Something went wrong. Please try again.";
            }
        } else {
            // INSERT new review
            $insert = "
                INSERT INTO tblreview 
                (UserID, WorkerID, BookingID, Rating, Review, ReviewDate)
                VALUES 
                (:uid, :wid, :bid, :rating, :review, NOW())
            ";

            $stmt = $dbh->prepare($insert);
            $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':wid', $booking->worker_id, PDO::PARAM_INT);
            $stmt->bindParam(':bid', $booking_id, PDO::PARAM_INT);
            $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
            $stmt->bindParam(':review', $review_text, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $success_message = "Thank you! Your review has been submitted successfully.";
                header("refresh:2;url=my-bookings.php");
            } else {
                $error_message = "Something went wrong. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo $is_editing ? 'Edit Review' : 'Write Review'; ?></title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .rating-option {
        cursor: pointer;
        padding: 10px;
        margin: 5px 0;
        border: 2px solid #e9ecef;
        border-radius: 5px;
        transition: all 0.3s;
    }
    .rating-option:hover {
        background-color: #f8f9fa;
        border-color: #007bff;
    }
    .rating-option input[type="radio"] {
        margin-right: 10px;
    }
    .star-display {
        color: #ffc107;
        font-size: 20px;
    }
    .existing-review-card {
        background-color: #f8f9fa;
        border-left: 4px solid #28a745;
        padding: 15px;
        margin-bottom: 20px;
    }
</style>
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-body">

            <h3 class="mb-3 text-primary">
                <i class="fas fa-star"></i> <?php echo $is_editing ? 'Edit Review' : ($booking->review_id ? 'Your Review' : 'Write Review'); ?>
            </h3>

            <?php if ($success_message): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <strong>Worker:</strong> <?php echo htmlentities($booking->worker_name); ?><br>
                <strong>Service:</strong> <?php echo htmlentities($booking->service_name); ?><br>
                <strong>Booking Date:</strong> <?php echo date('d M Y', strtotime($booking->BookingDate)); ?>
            </div>

            <?php if ($booking->review_id && !$is_editing): ?>
                <!-- DISPLAY EXISTING REVIEW -->
                <div class="existing-review-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="mb-2">Your Review</h5>
                            <small class="text-muted">
                                <i class="far fa-calendar"></i> 
                                <?php echo date('d M Y, h:i A', strtotime($booking->review_date)); ?>
                            </small>
                        </div>
                        <div>
                            <a href="review.php?booking_id=<?php echo $booking_id; ?>&edit=1" 
                               class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="review.php?booking_id=<?php echo $booking_id; ?>&action=delete" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Are you sure you want to delete this review?');">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                    
                    <div class="star-display mb-2">
                        <?php 
                        for($i = 1; $i <= 5; $i++) {
                            if($i <= $booking->existing_rating) {
                                echo '<i class="fas fa-star"></i>';
                            } else {
                                echo '<i class="far fa-star"></i>';
                            }
                        }
                        ?>
                        <span class="ml-2 text-dark">(<?php echo $booking->existing_rating; ?>/5)</span>
                    </div>
                    
                    <p class="mb-0"><?php echo nl2br(htmlentities($booking->existing_review)); ?></p>
                </div>
                
                <a href="my-bookings.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to My Bookings
                </a>

            <?php else: ?>
                <!-- REVIEW FORM (NEW OR EDIT) -->
                <form method="post">

                    <div class="form-group">
                        <label><strong>Rating</strong></label>
                        <?php 
                        $default_rating = $is_editing ? $booking->existing_rating : 0;
                        for ($i = 5; $i >= 1; $i--): 
                        ?>
                            <div class="rating-option">
                                <label class="mb-0 w-100 d-flex align-items-center">
                                    <input type="radio" name="rating" value="<?php echo $i; ?>" 
                                           <?php echo ($default_rating == $i) ? 'checked' : ''; ?> required>
                                    <span class="star-display">
                                        <?php echo str_repeat('★', $i) . str_repeat('☆', 5-$i); ?>
                                    </span>
                                    <span class="ml-2">(<?php echo $i; ?> Star<?php echo $i > 1 ? 's' : ''; ?>)</span>
                                </label>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <div class="form-group">
                        <label><strong>Your Review</strong></label>
                        <textarea name="review_text" class="form-control" rows="5" 
                                  placeholder="Share your experience with this service..." required><?php 
                            echo $is_editing ? htmlentities($booking->existing_review) : ''; 
                        ?></textarea>
                        <small class="form-text text-muted">Minimum 10 characters</small>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> <?php echo $is_editing ? 'Update Review' : 'Submit Review'; ?>
                    </button>

                    <?php if ($is_editing): ?>
                        <a href="review.php?booking_id=<?php echo $booking_id; ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel Edit
                        </a>
                    <?php else: ?>
                        <a href="my-bookings.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    <?php endif; ?>

                </form>
            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>