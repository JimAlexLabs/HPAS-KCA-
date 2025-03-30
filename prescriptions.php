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
// Only access username if it exists in the database
$username = isset($patientInfo['username']) ? $patientInfo['username'] : '';
$fname = $patientInfo['fname'];
$lname = $patientInfo['lname'];

// Generate bill if requested
if (isset($_GET["generate_bill"]) && isset($_GET['ID'])) {
    // Start output buffering before any output
    ob_start();
    
    // Suppress deprecation warnings for TCPDF
    error_reporting(E_ERROR | E_PARSE);
    
    require_once("TCPDF/tcpdf.php");
    $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $obj_pdf->SetCreator(PDF_CREATOR);
    $obj_pdf->SetTitle("Generate Bill");
    $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
    $obj_pdf->SetHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $obj_pdf->SetFooterFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $obj_pdf->SetDefaultMonospacedFont('helvetica');
    $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
    $obj_pdf->SetPrintHeader(false);
    $obj_pdf->SetPrintFooter(false);
    $obj_pdf->SetAutoPageBreak(TRUE, 10);
    $obj_pdf->SetFont('helvetica', '', 12);
    $obj_pdf->AddPage();
    
    $content = '';
    $content .= '
        <br/>
        <h2 align ="center"> THE ROYAL HOSPITALS</h2></br>
        <h3 align ="center"> Bill</h3>
    ';
    
    $ID = $_GET['ID'];
    $presQuery = mysqli_query($con, "SELECT p.pid, p.ID, p.fname, p.lname, p.doctor, p.appdate, p.apptime, p.disease, p.allergy, p.prescription, a.docFees 
                                    FROM prestb p 
                                    INNER JOIN appointmenttb a ON p.ID=a.ID 
                                    WHERE p.pid = '$pid' AND p.ID = '$ID'");
    
    if (!$presQuery || mysqli_num_rows($presQuery) == 0) {
        // If query fails or no results, show error and redirect
        echo "<script>alert('No prescription found or error retrieving data.'); window.location.href='prescriptions.php?pid=$pid';</script>";
        exit();
    }
                                    
    while ($row = mysqli_fetch_array($presQuery)) {
        $content .= '
        <label> Patient ID : </label>' . $row["pid"] . '<br/><br/>
        <label> Appointment ID : </label>' . $row["ID"] . '<br/><br/>
        <label> Patient Name : </label>' . $row["fname"] . ' ' . $row["lname"] . '<br/><br/>
        <label> Doctor Name : </label>' . $row["doctor"] . '<br/><br/>
        <label> Appointment Date : </label>' . $row["appdate"] . '<br/><br/>
        <label> Appointment Time : </label>' . $row["apptime"] . '<br/><br/>
        <label> Disease : </label>' . $row["disease"] . '<br/><br/>
        <label> Allergies : </label>' . $row["allergy"] . '<br/><br/>
        <label> Prescription : </label>' . $row["prescription"] . '<br/><br/>
        <label> Fees Paid : </label>' . $row["docFees"] . '<br/>
        ';
    }
    
    try {
        // Write HTML content to PDF
        $obj_pdf->writeHTML($content);
        
        // Clean any output buffers that might have been started
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Send the PDF
        $obj_pdf->Output("bill.pdf", 'I');
    } catch (Exception $e) {
        // Handle any exceptions that might occur during PDF generation
        echo "<script>alert('Error generating PDF: " . $e->getMessage() . "'); window.location.href='prescriptions.php?pid=$pid';</script>";
    }
    
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescriptions | THE ROYAL HOSPITALS</title>
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
                        <h4><i class="fa fa-file-text-o"></i> Your Prescriptions</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>Doctor Name</th>
                                        <th>Appointment ID</th>
                                        <th>Appointment Date</th>
                                        <th>Appointment Time</th>
                                        <th>Disease</th>
                                        <th>Allergies</th>
                                        <th>Prescription</th>
                                        <th>Bill</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT doctor, ID, appdate, apptime, disease, allergy, prescription FROM prestb WHERE pid='$pid' ORDER BY appdate DESC";
                                    $result = mysqli_query($con, $query);
                                    
                                    if (!$result || mysqli_num_rows($result) == 0) {
                                        echo '<tr><td colspan="8" class="text-center">No prescriptions found.</td></tr>';
                                    } else {
                                        while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['doctor']; ?></td>
                                        <td><?php echo $row['ID']; ?></td>
                                        <td><?php echo $row['appdate']; ?></td>
                                        <td><?php echo $row['apptime']; ?></td>
                                        <td><?php echo $row['disease']; ?></td>
                                        <td><?php echo $row['allergy']; ?></td>
                                        <td><?php echo $row['prescription']; ?></td>
                                        <td>
                                            <a href="prescriptions.php?ID=<?php echo $row['ID']; ?>&generate_bill=1&pid=<?php echo $pid; ?>" 
                                               class="btn btn-sm btn-success" onclick="alert('Bill Paid Successfully!');">
                                                <i class="fa fa-file-pdf-o"></i> Pay & Download
                                            </a>
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