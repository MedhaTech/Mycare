<?php
session_start();
include 'header.php';
include 'dbconnection.php';

$appointment_id = $_GET['appointment_id'] ?? null;
if (!$appointment_id) {
    echo "<div class='alert alert-danger'>Invalid appointment.</div>";
    exit;
}
?>

<div class="container mt-4">
    <h4>Add Procedure for Appointment #APT<?= str_pad($appointment_id, 3, '0', STR_PAD_LEFT) ?></h4>
    <form method="post" action="save-procedure.php">
        <input type="hidden" name="appointment_id" value="<?= $appointment_id ?>">

        <div class="form-group">
            <label for="procedure_name">Procedure Name</label>
            <input type="text" name="procedure_name" id="procedure_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="notes">Notes / Description</label>
            <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
        </div>

        <div class="form-group">
            <label for="cost">Procedure Fee (₹)</label>
            <input type="number" name="cost" id="cost" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Save Procedure</button>
        <a href="appointments.php" class="btn btn-secondary">Back</a>
    </form>
</div>

<?php include 'footer.php'; ?>
