<?php
include('func.php');
include('newfunc.php');
$con = mysqli_connect("localhost", "root", "password123", "myhmsdb");

if (!isset($_GET['pid'])) {
    echo "<script>alert('Invalid access. Please use the dashboard.'); window.location.href='admin-panel.php';</script>";
    exit();
}

$pid = $_GET['pid'];

// Get patient information
$patientQuery = mysqli_query($con, "SELECT * FROM patreg WHERE pid = '$pid'");
if (mysqli_num_rows($patientQuery) == 0) {
    echo "<script>alert('Patient not found.'); window.location.href='admin-panel.php';</script>";
    exit();
}

$patientInfo = mysqli_fetch_assoc($patientQuery);
$username = $patientInfo['username'];
$fname = $patientInfo['fname'];
$lname = $patientInfo['lname'];

// Handle appointment cancellation
if (isset($_GET['cancel']) && isset($_GET['ID'])) {
    $appointmentId = $_GET['ID'];
    $query = mysqli_query($con, "UPDATE appointmenttb SET userStatus='0' WHERE ID = '$appointmentId' AND pid = '$pid'");
    
    if ($query) {
        echo "<script>alert('Your appointment has been cancelled successfully.');</script>";
    } else {
        echo "<script>alert('Unable to cancel the appointment. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment History | THE ROYAL HOSPITALS</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
    <style>
        .bg-primary {
            background: -webkit-linear-gradient(left, #3931af, #00c6ff);
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card-header {
            border-radius: 10px 10px 0 0 !important;
        }
        .table th {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <a class="navbar-brand" href="#"><i class="fa fa-user-plus" aria-hidden="true"></i> THE ROYAL HOSPITALS</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="admin-panel.php"><i class="fa fa-home" aria-hidden="true"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container" style="margin-top: 100px;">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4><i class="fa fa-history"></i> Appointment History</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>Doctor Name</th>
                                        <th>Consultancy Fees</th>
                                        <th>Appointment Date</th>
                                        <th>Appointment Time</th>
                                        <th>Current Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT ID, doctor, docFees, appdate, apptime, userStatus, doctorStatus FROM appointmenttb WHERE fname ='$fname' AND lname='$lname' ORDER BY appdate DESC, apptime DESC";
                                    $result = mysqli_query($con, $query);
                                    
                                    if (mysqli_num_rows($result) == 0) {
                                        echo '<tr><td colspan="6" class="text-center">No appointment history found.</td></tr>';
                                    } else {
                                        while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['doctor']; ?></td>
                                        <td><?php echo $row['docFees']; ?></td>
                                        <td><?php echo $row['appdate']; ?></td>
                                        <td><?php echo $row['apptime']; ?></td>
                                        <td>
                                            <?php
                                            if (($row['userStatus'] == 1) && ($row['doctorStatus'] == 1)) {
                                                echo '<span class="badge badge-success">Active</span>';
                                            } else if (($row['userStatus'] == 0) && ($row['doctorStatus'] == 1)) {
                                                echo '<span class="badge badge-danger">Cancelled by You</span>';
                                            } else if (($row['userStatus'] == 1) && ($row['doctorStatus'] == 0)) {
                                                echo '<span class="badge badge-danger">Cancelled by Doctor</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if (($row['userStatus'] == 1) && ($row['doctorStatus'] == 1)) { ?>
                                                <a href="appointment-history.php?ID=<?php echo $row['ID']; ?>&cancel=update&pid=<?php echo $pid; ?>" 
                                                   onclick="return confirm('Are you sure you want to cancel this appointment?')"
                                                   class="btn btn-sm btn-danger">
                                                   <i class="fa fa-times"></i> Cancel
                                                </a>
                                            <?php } else { 
                                                echo '<span class="text-muted">Cancelled</span>';
                                            } ?>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="admin-panel.php" class="btn btn-primary">
                                <i class="fa fa-arrow-left"></i> Back to Dashboard
                            </a>
                            <a href="book-appointment.php?pid=<?php echo $pid; ?>" class="btn btn-success">
                                <i class="fa fa-plus"></i> Book New Appointment
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
</body>
</html> 