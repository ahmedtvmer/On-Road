<?php
require_once '../Controllers/ValidationController.php';
ValidationController::validateSession('admin');
require_once '../Models/mechanic.php';
require_once '../Models/request.php';

$mechanic = new Mechanic();
$allMechanics = $mechanic->getAllMechanics();

$request = new Request();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ON-ROAD BREAKDOWN ASSISTANCE | Mechanics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../root/css/main.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .rating-stars {
            color: #ffc107;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .driver-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>
        <?php include_once 'includes/adminsidebar.php'; ?>

        <?php include_once 'includes/navbar.php'; ?>
        
        <div class="admin-content-wrapper">
            <div class="admin-content">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="adminDashboard.php"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Mechanics</li>
                    </ol>
                </nav>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>All Mechanics</h4>
                    <div class="d-flex">
                        <input type="text" id="searchDriver" class="form-control me-2" placeholder="Search mechanic...">
                        <select id="filterLocation" class="form-select me-2">
                            <option value="">All Locations</option>
                            <?php
                            $locations = [];
                            if ($allMechanics) {
                                foreach ($allMechanics as $mech) {
                                    if (!in_array($mech['location'], $locations)) {
                                        $locations[] = $mech['location'];
                                        echo "<option value='" . $mech['location'] . "'>" . $mech['location'] . "</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <?php if (isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php 
                            echo $_SESSION['success_message']; 
                            unset($_SESSION['success_message']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error_message']) && !empty($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php 
                            echo $_SESSION['error_message']; 
                            unset($_SESSION['error_message']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Mechanic</th>
                                        <th>Location</th>
                                        <th>Specialization</th>
                                        <th>Experience</th>
                                        <th>Rating</th>
                                        <th>Total Reviews</th>
                                        <th>Assigned Orders</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($allMechanics): ?>
                                        <?php foreach ($allMechanics as $mechanic): ?>
                                            <?php 
                                            // Get assigned orders count
                                            $assignedOrdersCount = $request->getAssignedOrdersCount($mechanic['id']);
                                            ?>
                                            <tr>
                                                <td><?php echo $mechanic['id']; ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="../root/img/avatar.png" alt="Driver" class="driver-avatar me-2" 
                                                             onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($mechanic['fullName']); ?>&background=4e73df&color=fff'">
                                                        <div>
                                                            <div class="fw-bold"><?php echo $mechanic['fullName']; ?></div>
                                                            <small class="text-muted"><?php echo $mechanic['email']; ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php echo $mechanic['location']; ?></td>
                                                <td><?php echo $mechanic['specialization']; ?></td>
                                                <td><?php echo $mechanic['experience']; ?></td>
                                                <td>
                                                    <div class="rating-stars">
                                                        <?php
                                                        $rating = $mechanic['rating'] ?? 0;
                                                        $fullStars = floor($rating);
                                                        $halfStar = $rating - $fullStars >= 0.5;
                                                        
                                                        for ($i = 1; $i <= 5; $i++) {
                                                            if ($i <= $fullStars) {
                                                                echo '<i class="fas fa-star"></i>';
                                                            } elseif ($i == $fullStars + 1 && $halfStar) {
                                                                echo '<i class="fas fa-star-half-alt"></i>';
                                                            } else {
                                                                echo '<i class="far fa-star"></i>';
                                                            }
                                                        }
                                                        ?>
                                                        <span class="ms-1"><?php echo number_format($rating, 1); ?></span>
                                                    </div>
                                                </td>
                                                <td><?php echo $mechanic['totalReviews'] ?? 0; ?></td>
                                                <td><?php echo $assignedOrdersCount; ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="requests/mechanicProfile.php?id=<?php echo $mechanic['id']; ?>" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteDriverModal<?php echo $mechanic['id']; ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    
                                                    <div class="modal fade" id="deleteDriverModal<?php echo $mechanic['id']; ?>" tabindex="-1" aria-labelledby="deleteDriverModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="deleteDriverModalLabel">Confirm Delete</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you sure you want to delete mechanic <strong><?php echo $mechanic['fullName']; ?></strong>?
                                                                    This action cannot be undone.
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <form action="../Controllers/MechanicController.php" method="POST">
                                                                    <input type="hidden" name="action" value="delete">
                                                                    <input type="hidden" name="mechanic_id" value="<?php echo $mechanic['id']; ?>">
                                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center">No mechanics found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="deleteMechanicModal" tabindex="-1" aria-labelledby="deleteMechanicModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteMechanicModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete mechanic <span id="mechanicName"></span>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteMechanicForm" action="../Controllers/MechanicController.php" method="POST">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="mechanic_id" id="mechanicIdToDelete">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('adminSidebar').classList.toggle('collapsed');
            document.querySelector('.admin-content-wrapper').classList.toggle('expanded');
        });
        
        // Delete mechanic confirmation
        const deleteButtons = document.querySelectorAll('.delete-mechanic');
        const mechanicNameSpan = document.getElementById('mechanicName');
        const mechanicIdInput = document.getElementById('mechanicIdToDelete');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const mechanicId = this.getAttribute('data-id');
                const mechanicName = this.getAttribute('data-name');
                
                mechanicNameSpan.textContent = mechanicName;
                mechanicIdInput.value = mechanicId;
                
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteMechanicModal'));
                deleteModal.show();
            });
        });
        
        // Search and filter functionality
        document.getElementById('searchDriver').addEventListener('keyup', filterDrivers);
        document.getElementById('filterLocation').addEventListener('change', filterDrivers);
        
        function filterDrivers() {
            const searchValue = document.getElementById('searchDriver').value.toLowerCase();
            const locationFilter = document.getElementById('filterLocation').value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const location = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                
                const nameMatch = name.includes(searchValue);
                const locationMatch = locationFilter === '' || location === locationFilter;
                
                if (nameMatch && locationMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>