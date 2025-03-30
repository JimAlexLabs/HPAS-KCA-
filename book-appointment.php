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
$gender = $patientInfo['gender'];
$email = $patientInfo['email'];
$contact = $patientInfo['contact'];

if (isset($_POST['book_appointment'])) {
    $doctor = $_POST['doctor'];
    $docFees = $_POST['docFees'];
    $appdate = $_POST['appdate'];
    $apptime = $_POST['apptime'];
    $cur_date = date("Y-m-d");
    date_default_timezone_set('Asia/Kolkata');
    $cur_time = date("H:i:s");
    $apptime1 = strtotime($apptime);
    $appdate1 = strtotime($appdate);
    
    if (date("Y-m-d", $appdate1) >= $cur_date) {
        if ((date("Y-m-d", $appdate1) == $cur_date && date("H:i:s", $apptime1) > $cur_time) || date("Y-m-d", $appdate1) > $cur_date) {
            $check_query = mysqli_query($con, "SELECT apptime FROM appointmenttb WHERE doctor='$doctor' AND appdate='$appdate' AND apptime='$apptime'");

            if (mysqli_num_rows($check_query) == 0) {
                $query = mysqli_query($con, "INSERT INTO appointmenttb(pid, fname, lname, gender, email, contact, doctor, docFees, appdate, apptime, userStatus, doctorStatus) 
                    VALUES($pid, '$fname', '$lname', '$gender', '$email', '$contact', '$doctor', '$docFees', '$appdate', '$apptime', '1', '1')");

                if ($query) {
                    echo "<script>alert('Your appointment has been booked successfully!'); window.location.href='admin-panel.php';</script>";
                } else {
                    echo "<script>alert('Unable to process your request. Please try again!');</script>";
                }
            } else {
                echo "<script>alert('We are sorry to inform that the doctor is not available at this time or date. Please choose a different time or date!');</script>";
            }
        } else {
            echo "<script>alert('Select a time or date in the future!');</script>";
        }
    } else {
        echo "<script>alert('Select a date in the future!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment | THE ROYAL HOSPITALS</title>
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
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4><i class="fa fa-calendar-plus-o"></i> Book an Appointment</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Patient Name:</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" value="<?php echo $fname . ' ' . $lname; ?>" readonly>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Specialization:</label>
                                <div class="col-md-8">
                                    <select name="spec" class="form-control" id="spec" required>
                                        <option value="" disabled selected>Select Specialization</option>
                                        <?php display_specs(); ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Doctor:</label>
                                <div class="col-md-8">
                                    <select name="doctor" class="form-control" id="doctor" required>
                                        <option value="" disabled selected>Select Doctor</option>
                                        <?php display_docs(); ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Consultancy Fees:</label>
                                <div class="col-md-8">
                                    <input type="text" name="docFees" class="form-control" id="docFees" readonly>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Appointment Date:</label>
                                <div class="col-md-8">
                                    <input type="date" name="appdate" class="form-control datepicker" required>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Appointment Time:</label>
                                <div class="col-md-8">
                                    <select name="apptime" class="form-control" id="apptime" required>
                                        <option value="" disabled selected>Select Time</option>
                                        <option value="08:00:00">8:00 AM</option>
                                        <option value="10:00:00">10:00 AM</option>
                                        <option value="12:00:00">12:00 PM</option>
                                        <option value="14:00:00">2:00 PM</option>
                                        <option value="16:00:00">4:00 PM</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" name="book_appointment" class="btn btn-primary">
                                        <i class="fa fa-calendar-check-o"></i> Book Appointment
                                    </button>
                                    <a href="admin-panel.php" class="btn btn-secondary">
                                        <i class="fa fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter doctors by specialization
            document.getElementById('spec').addEventListener('change', function() {
                let spec = this.value;
                let docs = [...document.getElementById('doctor').options];
                
                docs.forEach((el, ind, arr) => {
                    arr[ind].style.display = "";
                    if (el.getAttribute("data-spec") != spec) {
                        arr[ind].style.display = "none";
                    }
                });
            });
            
            // Update fees when doctor is selected
            document.getElementById('doctor').addEventListener('change', function() {
                var selection = document.querySelector(`[value=${this.value}]`).getAttribute('data-value');
                document.getElementById('docFees').value = selection;
            });
        });
    </script>
</body>
</html> 