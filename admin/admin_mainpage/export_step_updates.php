<?php
require '../../vendor/autoload.php'; // Adjust path if needed

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

include '../../connection/connection.php';

if (!isset($_GET['id'])) {
    die("Client ID not provided.");
}

$clientId = (int)$_GET['id'];

// Fetch client info
$clientQuery = $conn->query("SELECT clientname FROM user_info WHERE id = $clientId");
$client = $clientQuery->fetch_assoc();
$clientName = $client['clientname'] ?? "Client";
$clientContact = $client['contact_no'] ?? "N/A";

// Step labels matching RealLiving's format
$stepLabels = [
    1 => 'Quotation',
    2 => 'Site Visit',
    3 => 'Material Approval',
    4 => 'Plan/3D Approval',
    5 => 'Cutting list',
    6 => 'Order Processing',
    7 => 'Fabrication',
    8 => 'Delivery',
    9 => 'Installation',
    10 => 'Handover'
];

// Create Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Step Updates');

// Page setup
$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
$sheet->getPageSetup()->setFitToWidth(1);
$sheet->getPageSetup()->setFitToHeight(0);
$sheet->getPageMargins()->setTop(0.5);
$sheet->getPageMargins()->setRight(0.5);
$sheet->getPageMargins()->setLeft(0.5);
$sheet->getPageMargins()->setBottom(0.5);

// Set column widths
$sheet->getColumnDimension('A')->setWidth(8);  // No.
$sheet->getColumnDimension('B')->setWidth(25); // Step
$sheet->getColumnDimension('C')->setWidth(20); // Update Time
$sheet->getColumnDimension('D')->setWidth(16); // End Date
$sheet->getColumnDimension('E')->setWidth(50); // Description
$sheet->getColumnDimension('F')->setWidth(15); // Status
$sheet->getColumnDimension('G')->setWidth(15);
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->getColumnDimension('I')->setWidth(15);

// HEADER SECTION - Enhanced RealLiving's template
// Logo and Company name
$sheet->mergeCells('A1:C2');
$sheet->setCellValue('A1', 'RealLiving');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(24)->setColor(new Color('FF00A6D6')); // Blue color for RealLiving
$sheet->setCellValue('A2', 'DESIGN CENTER'); // Add "DESIGN CENTER" text below
$sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14)->setColor(new Color('FFF7941D')); // Orange color for DESIGN CENTER
$sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:A2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

$sheet->mergeCells('D1:F2');
$sheet->setCellValue('D1', 'INITIAL STEP UPDATES');
$sheet->getStyle('D1')->getFont()->setBold(true)->setSize(20);
$sheet->getStyle('D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('D1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

// Company details and address
$sheet->mergeCells('G1:I1');
$sheet->setCellValue('G1', 'MC Premier 25, Quezon City, Metro Manila');
$sheet->getStyle('G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->mergeCells('G2:I2');
$sheet->setCellValue('G2', 'www.realivingdesigncenter.com');
$sheet->getStyle('G2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('G2')->getFont()->setUnderline(true)->setColor(new Color('FF00A6D6')); // Blue underlined URL

// Add border to header section with thicker border
$sheet->getStyle('A1:I2')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
$sheet->getStyle('A1:I2')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THICK);

// CLIENT INFO SECTION - Similar to the quotation template
$currentRow = 4;

// Client Name
$sheet->setCellValue('A' . $currentRow, 'Client Name:');
$sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
$sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A' . $currentRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F2F2F2');

$sheet->mergeCells('B' . $currentRow . ':E' . $currentRow);
$sheet->setCellValue('B' . $currentRow, $clientName);

// Date and Report No
$sheet->setCellValue('F' . $currentRow, 'Date:');
$sheet->getStyle('F' . $currentRow)->getFont()->setBold(true);
$sheet->getStyle('F' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('F' . $currentRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F2F2F2');

$sheet->mergeCells('G' . $currentRow . ':I' . $currentRow);
$sheet->setCellValue('G' . $currentRow, date('m/d/Y'));

$currentRow++;

// Project Name
$sheet->setCellValue('A' . $currentRow, 'Project:');
$sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
$sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A' . $currentRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F2F2F2');

$sheet->mergeCells('B' . $currentRow . ':E' . $currentRow);
$sheet->setCellValue('B' . $currentRow, 'Project #' . $clientId);

// Report No
$sheet->setCellValue('F' . $currentRow, 'Report No:');
$sheet->getStyle('F' . $currentRow)->getFont()->setBold(true);
$sheet->getStyle('F' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('F' . $currentRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F2F2F2');

$sheet->mergeCells('G' . $currentRow . ':I' . $currentRow);
$sheet->setCellValue('G' . $currentRow, 'SR-' . date('Ymd') . '-' . $clientId);

$currentRow++;

// Contact
$sheet->setCellValue('A' . $currentRow, 'Contact No:');
$sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
$sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A' . $currentRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F2F2F2');

$sheet->mergeCells('B' . $currentRow . ':I' . $currentRow);
$sheet->setCellValue('B' . $currentRow, $clientContact);

$currentRow += 2;

// TABLE HEADER - Enhanced orange header like in the template
$sheet->mergeCells('A' . $currentRow . ':I' . $currentRow);
$sheet->setCellValue('A' . $currentRow, 'PROJECT STEP UPDATES');
$sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A' . $currentRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F7941D'); // Orange color
$sheet->getStyle('A' . $currentRow)->getFont()->getColor()->setRGB('FFFFFF'); // White text
$sheet->getRowDimension($currentRow)->setRowHeight(30); // Make header row taller

$currentRow++;

// Column headers with enhanced styling
$headers = ['No.', 'Step', 'Update Time', 'End Date', 'Description'];
$columns = ['A', 'B', 'C', 'D', 'E'];

for ($i = 0; $i < count($headers); $i++) {
    $sheet->setCellValue($columns[$i] . $currentRow, $headers[$i]);
    $sheet->getStyle($columns[$i] . $currentRow)->getFont()->setBold(true);
    $sheet->getStyle($columns[$i] . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle($columns[$i] . $currentRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle($columns[$i] . $currentRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E6E6E6'); // Slightly darker gray
    $sheet->getStyle($columns[$i] . $currentRow)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM);
}

$sheet->mergeCells('F' . $currentRow . ':I' . $currentRow);
$sheet->setCellValue('F' . $currentRow, 'Status');
$sheet->getStyle('F' . $currentRow)->getFont()->setBold(true);
$sheet->getStyle('F' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('F' . $currentRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('F' . $currentRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E6E6E6'); // Slightly darker gray
$sheet->getStyle('F' . $currentRow)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM);
$sheet->getRowDimension($currentRow)->setRowHeight(25); // Make header row taller

$currentRow++;

// Fetch step updates
$sql = "SELECT step, update_time, end_date, description FROM step_updates WHERE client_id = ? ORDER BY step ASC, update_time ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $clientId);
$stmt->execute();
$result = $stmt->get_result();

$stepCounts = [];
$rowNum = $currentRow;
$index = 1;

while ($row = $result->fetch_assoc()) {
    $stepNum = $row['step'];
    if (!isset($stepCounts[$stepNum])) {
        $stepCounts[$stepNum] = 0;
    }
    $stepCounts[$stepNum]++;

    $sheet->setCellValue("A$rowNum", $index);
    $sheet->setCellValue("B$rowNum", $stepLabels[$stepNum] ?? "Step " . $stepNum);
    $sheet->setCellValue("C$rowNum", date('m/d/Y h:i A', strtotime($row['update_time'])));

    $sheet->setCellValue("D$rowNum", !empty($row['end_date']) ? date('m/d/Y', strtotime($row['end_date'])) : 'N/A');
    $sheet->setCellValue("E$rowNum", $row['description']);

    // Status column with colored indicators
    $sheet->mergeCells('F' . $rowNum . ':I' . $rowNum);
    $completed = (!empty($row['end_date']) && strtotime($row['end_date']) < time());
    $status = $completed ? 'Completed' : 'In Progress';
    $sheet->setCellValue("F$rowNum", $status);

    // Color-code the status
    if ($completed) {
        $sheet->getStyle("F$rowNum")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('DDFFDD'); // Light green for completed
        $sheet->getStyle("F$rowNum")->getFont()->getColor()->setRGB('006600'); // Dark green text
    } else {
        $sheet->getStyle("F$rowNum")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFF2CC'); // Light yellow for in progress
        $sheet->getStyle("F$rowNum")->getFont()->getColor()->setRGB('996600'); // Dark yellow/brown text
    }
    $sheet->getStyle("F$rowNum")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Add alternating row colors for readability
    if ($index % 2 == 0) {
        $sheet->getStyle("A$rowNum:E$rowNum")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F9F9F9');
    }

    $rowNum++;
    $index++;
}

// Add borders to the entire table
$sheet->getStyle('A' . ($currentRow - 1) . ':I' . ($rowNum - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

$rowNum += 2;

// NOTES section with styled header
$sheet->mergeCells('A' . $rowNum . ':I' . $rowNum);
$sheet->setCellValue('A' . $rowNum, 'NOTES:');
$sheet->getStyle('A' . $rowNum)->getFont()->setBold(true)->setSize(12);
$sheet->getStyle('A' . $rowNum)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F2F2F2');
$sheet->getStyle('A' . $rowNum)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM);
$sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A' . $rowNum)->getFont()->getColor()->setRGB('000000');
$sheet->getRowDimension($rowNum)->setRowHeight(22);

$rowNum++;
$sheet->mergeCells('A' . $rowNum . ':I' . $rowNum);
$sheet->setCellValue('A' . $rowNum, '1. Report generated on ' . date('m/d/Y H:i'));
$sheet->getStyle('A' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
$sheet->getStyle('A' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);

$rowNum++;
$sheet->mergeCells('A' . $rowNum . ':I' . $rowNum);
$sheet->setCellValue('A' . $rowNum, '2. All dates are subject to change based on project requirements');
$sheet->getStyle('A' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
$sheet->getStyle('A' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);

$rowNum++;
$sheet->mergeCells('A' . $rowNum . ':I' . $rowNum);
$sheet->setCellValue('A' . $rowNum, '3. For questions or concerns, please contact our office');
$sheet->getStyle('A' . $rowNum)->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
$sheet->getStyle('A' . $rowNum)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
$sheet->getStyle('A' . $rowNum)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

$rowNum += 2;

// Thank you message with enhanced styling
$sheet->mergeCells('A' . $rowNum . ':I' . $rowNum);
$sheet->setCellValue('A' . $rowNum, 'Thank you for giving us a chance to be at service for you. Keep safe');
$sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A' . $rowNum)->getFont()->setItalic(true)->setSize(11);
$sheet->getStyle('A' . $rowNum)->getFont()->getColor()->setRGB('00A6D6'); // Blue color
$sheet->getRowDimension($rowNum)->setRowHeight(25); // Taller row for thank you message

$rowNum += 2;

// Prepared by / Approved by section with enhanced styling
$sheet->mergeCells('A' . $rowNum . ':C' . $rowNum);
$sheet->setCellValue('A' . $rowNum, 'Prepared By:');
$sheet->getStyle('A' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A' . $rowNum)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F2F2F2');

$sheet->mergeCells('D' . $rowNum . ':F' . $rowNum);
$sheet->setCellValue('D' . $rowNum, 'Approved By:');
$sheet->getStyle('D' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('D' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('D' . $rowNum)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F2F2F2');

$sheet->mergeCells('G' . $rowNum . ':I' . $rowNum);
$sheet->setCellValue('G' . $rowNum, 'Conforme:');
$sheet->getStyle('G' . $rowNum)->getFont()->setBold(true);
$sheet->getStyle('G' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('G' . $rowNum)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F2F2F2');

$rowNum++;
$sheet->mergeCells('A' . $rowNum . ':C' . $rowNum);
$sheet->setCellValue('A' . $rowNum, 'MS. CHRISTINE DELAROSA');
$sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->mergeCells('D' . $rowNum . ':F' . $rowNum);
$sheet->setCellValue('D' . $rowNum, 'MS. KEN YANG');
$sheet->getStyle('D' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->mergeCells('G' . $rowNum . ':I' . $rowNum);
$sheet->setCellValue('G' . $rowNum, '');
$sheet->getStyle('G' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Add space for signature (empty row)
$rowNum++;
$sheet->mergeCells('A' . $rowNum . ':C' . $rowNum);
$sheet->mergeCells('D' . $rowNum . ':F' . $rowNum);
$sheet->mergeCells('G' . $rowNum . ':I' . $rowNum);
$sheet->getRowDimension($rowNum)->setRowHeight(30);

// Apply borders to the signature section
$sheet->getStyle('A' . ($rowNum - 2) . ':I' . $rowNum)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
$sheet->getStyle('A' . ($rowNum - 2) . ':I' . $rowNum)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM);

// Add page numbers and logo info in footer
$sheet->getHeaderFooter()->setOddFooter('&L&B' . $sheet->getTitle() . '&C&P of &N&R&"RealLiving Design Center"&D');

// Set headers to download the file
$filename = "{$clientName}_step_updates.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$filename\"");
header('Cache-Control: max-age=0');

// Write file to output
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
