<?php include 'header.php'; ?>

<main class="main-wrapper clearfix" style="margin-top: 30px;">
    <div class="container">
        <div class="widget-list">
            <div class="row">
                <div class="widget-holder col-md-12">
                    <div class="widget-bg">
                        <div class="widget-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h2 class="box-title">Patients List</h2>
                                    <p>A list of all patients in your clinic with their details.</p>
                                </div>
                                <div>
                                    <a href="book-appointment.php" class="btn btn-primary">+ Quick Book Appointment</a>
                                </div>
                            </div>

                            <?php
                            $conn = new mysqli("192.185.129.71", "medha_mycare", "peO*aDq0=Hb&", "medha_mycare");
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            $sql = "SELECT patients.id, patients.name, patients.phone, doctors.name AS doctor_name
                                    FROM patients
                                    LEFT JOIN doctors ON patients.doctor_id = doctors.id";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo '<div class="table-responsive">';
                                echo '<table class="table table-bordered table-striped" style="background-color: white; color: black;">';
                                echo '<thead class="thead-dark">
                                        <tr>
                                            <th>Patient ID</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Doctor</th>
                                            <th>Actions</th>
                                        </tr>
                                      </thead>
                                      <tbody class="text-dark">';

                                while ($row = $result->fetch_assoc()) {
                                    $patientID = '#PT' . str_pad($row['id'], 4, '0', STR_PAD_LEFT);
                                    $editUrl = 'edit-patient.php?id=' . $row['id'];

                                    echo '<tr>';
                                    echo '<td>' . $patientID . '</td>';
                                    echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['doctor_name']) . '</td>';
                                    echo '<td><a href="' . $editUrl . '" class="btn btn-sm btn-info">Edit</a></td>';
                                    echo '</tr>';
                                }

                                echo '</tbody></table></div>';
                            } else {
                                echo '<p class="text-danger">No patients found.</p>';
                            }

                            $conn->close();
                            ?>

                        </div> <!-- /.widget-body -->
                    </div> <!-- /.widget-bg -->
                </div> <!-- /.widget-holder -->
            </div> <!-- /.row -->
        </div> <!-- /.widget-list -->
    </div> <!-- /.container -->
</main>
