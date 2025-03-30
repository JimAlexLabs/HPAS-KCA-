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
    
    // Create custom PDF class with header and footer
    class MYPDF extends TCPDF {
        // Page header
        public function Header() {
            // Get the current page break margin
            $bMargin = $this->getBreakMargin();
            // Get current auto-page-break mode
            $auto_page_break = $this->AutoPageBreak;
            // Disable auto-page-break
            $this->SetAutoPageBreak(false, 0);
            
            // Logo
            $logo_path = $_SERVER['DOCUMENT_ROOT'] . "/Hospital-Management-System/img/logo.png";
            if (file_exists($logo_path)) {
                $this->Image($logo_path, 10, 10, 30, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            } else {
                // Fallback to favicon if main logo doesn't exist
                $this->Image('images/favicon.png', 10, 10, 20, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            }
            
            // Set colors
            $this->SetTextColor(48, 48, 150);
            $this->SetFont('helvetica', 'B', 20);
            $this->SetXY(45, 10);
            $this->Cell(0, 10, 'THE ROYAL HOSPITALS', 0, false, 'L', 0, '', 0, false, 'M', 'M');
            
            $this->SetFont('helvetica', '', 10);
            $this->SetTextColor(80, 80, 80);
            $this->SetXY(45, 20);
            $this->Cell(0, 5, 'Excellence in Healthcare', 0, false, 'L', 0, '', 0, false, 'M', 'M');
            
            $this->SetXY(45, 25);
            $this->Cell(0, 5, 'P.O. Box 12345, Nairobi, Kenya', 0, false, 'L', 0, '', 0, false, 'M', 'M');
            $this->SetXY(45, 30);
            $this->Cell(0, 5, 'Tel: +254 700 000000 | Email: info@royalhospitals.co.ke', 0, false, 'L', 0, '', 0, false, 'M', 'M');
            
            // Decorative line
            $this->SetDrawColor(57, 49, 175);
            $this->SetLineWidth(0.8);
            $this->Line(10, 40, 200, 40);
            
            // Subtitle: Invoice
            $this->SetFont('helvetica', 'B', 16);
            $this->SetTextColor(57, 49, 175);
            $this->SetXY(10, 45);
            $this->Cell(0, 10, 'OFFICIAL RECEIPT', 0, false, 'C', 0, '', 0, false, 'M', 'M');
            
            // Restore auto-page-break status
            $this->SetAutoPageBreak($auto_page_break, $bMargin);
            // Set the starting point for the page content
            $this->setPageMark();
        }
        
        // Page footer
        public function Footer() {
            // Position at 15 mm from bottom
            $this->SetY(-40);
            
            // Decorative line
            $this->SetDrawColor(57, 49, 175);
            $this->SetLineWidth(0.3);
            $this->Line(10, 260, 200, 260);
            
            // Set font
            $this->SetFont('helvetica', 'I', 8);
            $this->SetTextColor(80, 80, 80);
            
            // Footer text
            $this->Cell(0, 10, 'Thank you for choosing The Royal Hospitals for your healthcare needs.', 0, false, 'C', 0, '', 0, false, 'T', 'M');
            $this->Ln(5);
            $this->Cell(0, 10, 'This is an official receipt for services rendered. Please retain for your records.', 0, false, 'C', 0, '', 0, false, 'T', 'M');
            $this->Ln(5);
            $this->Cell(0, 10, 'For inquiries about this bill, please contact our billing department at +254 700 111222', 0, false, 'C', 0, '', 0, false, 'T', 'M');
            $this->Ln(5);
            $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        }
    }
    
    // Create new PDF document
    $obj_pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Set document information
    $obj_pdf->SetCreator('The Royal Hospitals');
    $obj_pdf->SetAuthor('The Royal Hospitals');
    $obj_pdf->SetTitle('Medical Receipt');
    $obj_pdf->SetSubject('Patient Medical Receipt');
    $obj_pdf->SetKeywords('Hospital, Receipt, Bill, Patient, Medical');
    
    // Set default header and footer data
    $obj_pdf->SetHeaderData('', '', 'THE ROYAL HOSPITALS', 'Medical Receipt');
    
    // Set margins
    $obj_pdf->SetMargins(PDF_MARGIN_LEFT, 60, PDF_MARGIN_RIGHT);
    $obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
    // Set auto page breaks
    $obj_pdf->SetAutoPageBreak(TRUE, 40);
    
    // Set image scale factor
    $obj_pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    
    // Add a page
    $obj_pdf->AddPage();
    
    // Generate unique receipt number
    $receipt_number = 'RH-' . date('Ymd') . '-' . rand(1000, 9999);
    
    // Get payment date and time (current)
    $payment_date = date('Y-m-d');
    $payment_time = date('H:i:s');
    
    // Get the ID
    $ID = $_GET['ID'];
    
    // Get prescription and appointment data
    $presQuery = mysqli_query($con, "SELECT p.pid, p.ID, p.fname, p.lname, p.doctor, p.appdate, p.apptime, p.disease, p.allergy, p.prescription, a.docFees 
                                FROM prestb p 
                                INNER JOIN appointmenttb a ON p.ID=a.ID 
                                WHERE p.pid = '$pid' AND p.ID = '$ID'");
    
    if (!$presQuery || mysqli_num_rows($presQuery) == 0) {
        // If query fails or no results, show error and redirect
        echo "<script>alert('No prescription found or error retrieving data.'); window.location.href='prescriptions.php?pid=$pid';</script>";
        exit();
    }
    
    $row = mysqli_fetch_array($presQuery);
    
    // Start HTML content
    $content = '';
    
    // Patient and billing information section
    $content .= '
    <table cellspacing="0" cellpadding="5" border="0">
        <tr>
            <td width="50%" style="font-size:12pt; color:#3931af; font-weight:bold;">PATIENT INFORMATION</td>
            <td width="50%" style="font-size:12pt; color:#3931af; font-weight:bold;">BILLING INFORMATION</td>
        </tr>
        <tr>
            <td width="50%" style="border:1px solid #cccccc; background-color:#f9f9f9; padding:8px;">
                <table cellspacing="0" cellpadding="3">
                    <tr>
                        <td style="font-weight:bold; width:40%;">Patient ID:</td>
                        <td>' . $row["pid"] . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold; width:40%;">Full Name:</td>
                        <td>' . $row["fname"] . ' ' . $row["lname"] . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold; width:40%;">Doctor:</td>
                        <td>' . $row["doctor"] . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold; width:40%;">Appointment Date:</td>
                        <td>' . $row["appdate"] . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold; width:40%;">Appointment Time:</td>
                        <td>' . $row["apptime"] . '</td>
                    </tr>
                </table>
            </td>
            <td width="50%" style="border:1px solid #cccccc; background-color:#f9f9f9; padding:8px;">
                <table cellspacing="0" cellpadding="3">
                    <tr>
                        <td style="font-weight:bold; width:50%;">Receipt Number:</td>
                        <td>' . $receipt_number . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold; width:50%;">Appointment ID:</td>
                        <td>' . $row["ID"] . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold; width:50%;">Payment Date:</td>
                        <td>' . $payment_date . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold; width:50%;">Payment Time:</td>
                        <td>' . $payment_time . '</td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold; width:50%;">Payment Method:</td>
                        <td>M-Pesa</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
    ';
    
    // Medical details section
    $content .= '
    <h4 style="color:#3931af; border-bottom:1px solid #3931af; padding-bottom:5px;">MEDICAL DETAILS</h4>
    <table cellspacing="0" cellpadding="5" style="border:1px solid #cccccc; width:100%;">
        <tr style="background-color:#f0f0f5;">
            <th style="border:1px solid #cccccc; width:30%;">Diagnosis</th>
            <th style="border:1px solid #cccccc; width:30%;">Allergies</th>
            <th style="border:1px solid #cccccc; width:40%;">Prescription</th>
        </tr>
        <tr style="background-color:#ffffff;">
            <td style="border:1px solid #cccccc;">' . $row["disease"] . '</td>
            <td style="border:1px solid #cccccc;">' . $row["allergy"] . '</td>
            <td style="border:1px solid #cccccc;">' . $row["prescription"] . '</td>
        </tr>
    </table>
    <br>
    ';
    
    // Payment breakdown section
    $content .= '
    <h4 style="color:#3931af; border-bottom:1px solid #3931af; padding-bottom:5px;">PAYMENT BREAKDOWN</h4>
    <table cellspacing="0" cellpadding="5" style="border:1px solid #cccccc; width:100%;">
        <tr style="background-color:#f0f0f5;">
            <th style="border:1px solid #cccccc; width:70%;">Description</th>
            <th style="border:1px solid #cccccc; width:30%; text-align:right;">Amount (KES)</th>
        </tr>
        <tr>
            <td style="border:1px solid #cccccc;">Consultation Fee (Dr. ' . $row["doctor"] . ')</td>
            <td style="border:1px solid #cccccc; text-align:right;">' . number_format($row["docFees"] * 0.7, 2) . '</td>
        </tr>
        <tr>
            <td style="border:1px solid #cccccc;">Medication and Prescription</td>
            <td style="border:1px solid #cccccc; text-align:right;">' . number_format($row["docFees"] * 0.3, 2) . '</td>
        </tr>
        <tr style="background-color:#f0f0f5; font-weight:bold;">
            <td style="border:1px solid #cccccc;">TOTAL</td>
            <td style="border:1px solid #cccccc; text-align:right;">' . number_format($row["docFees"], 2) . '</td>
        </tr>
    </table>
    <br>
    ';
    
    // Verification and stamp section
    $content .= '
    <table cellspacing="0" cellpadding="5" border="0">
        <tr>
            <td width="70%">
                <h4 style="color:#3931af; border-bottom:1px solid #3931af; padding-bottom:5px;">PAYMENT VERIFICATION</h4>
                <div style="border:1px solid #cccccc; padding:10px; background-color:#f9f9f9;">
                    <p style="font-weight:bold;">Payment Status: <span style="color:green;">CONFIRMED</span></p>
                    <p>Transaction ID: MPESA' . rand(100000000, 999999999) . '</p>
                    <p>This receipt has been digitally verified and is valid as proof of payment.</p>
                </div>
            </td>
            <td width="30%" align="center">
                <div style="border:2px dashed #3931af; padding:10px;">
                    <span style="font-size:14pt; color:#3931af; font-weight:bold;">OFFICIAL STAMP</span><br>
                    <img src="images/favicon.png" alt="Hospital Stamp" width="60" height="60" /><br>
                    <span style="font-size:10pt;">THE ROYAL HOSPITALS</span><br>
                    <span style="font-size:8pt;">' . date('d/m/Y') . '</span>
                </div>
            </td>
        </tr>
    </table>
    <br>
    ';
    
    // Generate a simple QR code with transaction information
    $qrText = "Receipt: " . $receipt_number . "\nPatient: " . $row["fname"] . " " . $row["lname"] . "\nAmount: " . $row["docFees"] . "\nDate: " . $payment_date;
    $style = array(
        'border' => 2,
        'vpadding' => 'auto',
        'hpadding' => 'auto',
        'fgcolor' => array(57, 49, 175),
        'bgcolor' => false,
        'module_width' => 1,
        'module_height' => 1
    );
    
    // Disclaimer and notes
    $content .= '
    <div style="background-color:#f0f0f5; padding:10px; border-left:4px solid #3931af;">
        <p style="font-size:9pt;"><strong>Important Note:</strong> This receipt serves as proof of payment for medical services rendered at The Royal Hospitals. For reimbursement purposes or insurance claims, please present this receipt along with any additional documentation required by your provider.</p>
        <p style="font-size:9pt;">Keep this receipt for your records and future reference. We are committed to providing you with the highest quality of healthcare services.</p>
    </div>
    ';
    
    try {
        // Write HTML content to PDF
        $obj_pdf->writeHTML($content, true, false, true, false, '');
        
        // Generate QR code at the bottom right
        $obj_pdf->write2DBarcode($qrText, 'QRCODE,L', 160, 230, 30, 30, $style, 'N');
        
        // Clean any output buffers that might have been started
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Send the PDF
        $obj_pdf->Output("Royal_Hospitals_Receipt_" . $receipt_number . ".pdf", 'I');
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