<?php
include 'dbconnection.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT a.*, p.name AS patient_name, d.name AS doctor_name 
        FROM appointments a
        LEFT JOIN patients p ON a.patient_id = p.id
        LEFT JOIN doctors d ON a.doctor_id = d.id
        WHERE p.name LIKE ? OR d.name LIKE ?
        ORDER BY a.appointment_date DESC, a.appointment_time DESC";

$stmt = $conn->prepare($sql);
$like = "%$search%";
$stmt->bind_param("ss", $like, $like);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $status = $row['status'];
        $badgeClass = match ($status) {
            'Scheduled' => 'badge-primary',
            'Tentative' => 'badge-warning',
            'Add to Waitlist' => 'badge-info',
            'Confirmed' => 'badge-success',
            'In Progress' => 'badge-dark',
            'Completed' => 'badge-success',
            'Cancelled' => 'badge-danger',
            default => 'badge-secondary'
        };

        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['patient_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['doctor_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['appointment_date']) . ' ' . date("h:i A", strtotime($row['appointment_time'])) . '</td>';
        echo '<td><span class="badge ' . $badgeClass . '">' . htmlspecialchars($row['status']) . '</span></td>';
        echo '<td>' . htmlspecialchars($row['type']) . '</td>';
        echo '<td>' . intval($row['duration']) . ' min</td>';
        echo '<td>
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="feather feather-more-vertical"></i></a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="view-appointment.php?id=' . $row['id'] . '">View</a>
                        <a class="dropdown-item" href="edit-appointment.php?id=' . $row['id'] . '">Edit</a>
                        <a class="dropdown-item text-danger" data-toggle="modal" data-target="#deleteModal' . $row['id'] . '">Delete</a>
                        <a class="dropdown-item" href="change-status-appointment.php?id=' . $row['id'] . '">Change Status</a>
                        <a class="dropdown-item" href="download-slip.php?id=' . $row['id'] . '">Download Slip</a>
                    </div>
                </div>
              </td>';
        echo '</tr>';
    }
}
// If no result, return nothing (just an empty string)
