
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
echo "DEBUG: Top of doctors-list.php reached<br>";
?>
<?php include 'header.php';
echo "DEBUG: After including header.php<br>";
?>

<main class="main-wrapper clearfix" style="margin-top: 30px;">
    <div class="container">
        <div class="widget-list">
            <div class="row">
                <div class="widget-holder col-md-12">
                    <div class="widget-bg">
                        <div class="widget-body">
                            <h2 class="box-title">Doctors List</h2>
                            <p>A list of all doctors in your clinic with their details.</p>
                        </div> 

                            <?php
                            include 'dbconnection.php';

                            
                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggleStatus'])) {
                                $id = $_POST['id'];
                                $newStatus = $_POST['currentStatus'] === 'Active' ? 'Inactive' : 'Active';
                                $conn->query("UPDATE doctors SET status='$newStatus' WHERE id=$id");
                                echo "<script>window.location='doctors-list.php';</script>";
                            }

                            $sql = "SELECT * FROM doctors";
                            $result = $conn->query($sql);
                            if (!$result) {
                                die("Query error: " . $conn->error);
                            }
                            ?>

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Doctor ID</th>
                                            <th>Doctor Name</th>
                                            <th>Designation</th>
                                            <th>Contact</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($result->num_rows > 0): ?>
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                                
                                                <div class="modal fade" id="viewDoctor<?php echo $row['id']; ?>" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                                        <div class="modal-content border-0 shadow">
                                                            <div class="modal-header bg-primary text-white">
                                                                <h5 class="modal-title">Doctor Details</h5>
                                                                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body p-4">
                                                                <div class="row">
                                                                    <div class="col-md-6"><strong>Doctor ID:</strong> #<?php echo $row['doctor_id']; ?></div>
                                                                    <div class="col-md-6"><strong>Name:</strong> Dr. <?php echo $row['name']; ?></div>
                                                                    <div class="col-md-6"><strong>Email:</strong> <?php echo $row['email']; ?></div>
                                                                    <div class="col-md-6"><strong>Phone:</strong> <?php echo $row['phone']; ?></div>
                                                                    <div class="col-md-6"><strong>Gender:</strong> <?php echo $row['gender']; ?></div>
                                                                    <div class="col-md-6"><strong>DOB:</strong> <?php echo $row['dob']; ?></div>
                                                                    <div class="col-md-6"><strong>Department:</strong> <?php echo $row['department']; ?></div>
                                                                    <div class="col-md-6"><strong>Designation:</strong> <?php echo $row['designation']; ?></div>
                                                                    <div class="col-md-6"><strong>Qualification:</strong> <?php echo $row['qualification']; ?></div>
                                                                    <div class="col-md-6"><strong>License:</strong> <?php echo $row['license']; ?></div>
                                                                    <div class="col-md-6"><strong>Experience:</strong> <?php echo $row['experience']; ?> years</div>
                                                                    <div class="col-md-6"><strong>Status:</strong> 
                                                                        <span class="badge badge-<?php echo strtolower($row['status']) === 'active' ? 'success' : 'danger'; ?>">
                                                                            <?php echo $row['status']; ?>
                                                                        </span>
                                                                    </div>
                                                                    <div class="col-md-6"><strong>Address:</strong><br>
                                                                        <?php echo $row['address1']; ?>, <?php echo $row['address2']; ?>, <?php echo $row['city']; ?> - <?php echo $row['pincode']; ?>, <?php echo $row['state']; ?>
                                                                    </div>
                                                                    <div class="col-md-6"><strong>Bank:</strong><br>
                                                                        <?php echo $row['bank_name']; ?> - <?php echo $row['account_name']; ?><br>
                                                                        A/C: <?php echo $row['account_number']; ?><br>
                                                                        Branch: <?php echo $row['branch']; ?><br>
                                                                        IFSC: <?php echo $row['ifsc']; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer bg-light py-2">
                                                                <button class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteDoctor<?php echo $row['id']; ?>" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <form method="post" action="delete-doctor.php">
                                                            <div class="modal-content">
                                                                <div class="modal-header"><h5 class="modal-title">Confirm Deletion</h5></div>
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                                    <p>Are you sure you want to delete <strong><?php echo $row['name']; ?></strong>?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" name="deleteDoctor" class="btn btn-danger">Delete</button>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                                <tr>
                                                    <td>#<?php echo $row['doctor_id']; ?></td>
                                                    <td>Dr. <?php echo $row['name']; ?></td>
                                                    <td><?php echo $row['designation']; ?></td>
                                                    <td><?php echo $row['email']; ?><br><?php echo $row['phone']; ?></td>
                                                    <td>
                                                        <form method="post" style="display:inline;">
                                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                            <input type="hidden" name="currentStatus" value="<?php echo $row['status']; ?>">
                                                            <button type="submit" name="toggleStatus" class="badge badge-<?php echo strtolower($row['status']) === 'active' ? 'success' : 'danger'; ?> border-0">
                                                                <?php echo $row['status']; ?>
                                                            </button>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                                <i class="feather feather-more-vertical"></i>
                                                            </a>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" data-toggle="modal" data-target="#viewDoctor<?php echo $row['id']; ?>">View</a>
                                                                <a class="dropdown-item" href="edit-doctor.php?id=<?php echo $row['id']; ?>">Edit</a>
                                                                <a class="dropdown-item text-danger" data-toggle="modal" data-target="#deleteDoctor<?php echo $row['id']; ?>">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="6" class="text-center">No doctors found.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php $conn->close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
