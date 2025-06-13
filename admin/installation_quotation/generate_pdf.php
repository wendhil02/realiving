<?php
// 0) Autoload & DB
require '../../vendor/autoload.php';
include '../../connection/connection.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// 1) Grab POST data
$id           = intval($_POST['id'] ?? 0);
$orderDate    = $_POST['order_date']     ?? '';
$quotationNo  = $_POST['quotation_no']   ?? '';
$installType  = $_POST['install_type']   ?? '';
$contactNo    = $_POST['contact_no']     ?? '';
$address      = $_POST['address']        ?? '';
$employeeName = $_POST['employee_name']  ?? '';
$discount     = floatval($_POST['discount'] ?? 0);
$cart         = json_decode($_POST['cart_data'] ?? '[]', true);

// 2) Look up client/project by ID
$clientName   = '';
$projectScope = '';
if ($id > 0) {
    $stmt = $conn->prepare("
      SELECT clientname, nameproject
        FROM user_info
       WHERE id = ?
      LIMIT 1
    ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows) {
        $row = $res->fetch_assoc();
        $clientName   = $row['clientname'];
        $projectScope = $row['nameproject'];
    }
    $stmt->close();
}

// 3) Compute totals (mirroring quotation.php logic)
$grand     = 0;
$laborSum  = 0;

foreach ($cart as &$it) {
    $w         = floatval($it['width']           ?? 0);
    $h         = floatval($it['height']          ?? 0);
    $l         = floatval($it['length']          ?? 0);
    $oW        = floatval($it['originalWidth']   ?? 0);
    $oH        = floatval($it['originalHeight']  ?? 0);
    $oL        = floatval($it['originalLength']  ?? 0);
    $price     = floatval($it['price']           ?? 0);
    $basePrice = floatval($it['basePrice']       ?? 0);
    $laborRate = floatval($it['labor']           ?? 0);
    $qty       = intval($it['quantity']          ?? 1);
    $combo     = $it['dimensionCombo']           ?? '';

    // 1) Raw deltas
    $rawWdelta = max($w - ($oW + 10), 0);
    $rawHdelta = max($h - $oH, 0);
    $rawLdelta = max($l - $oL, 0);

    // 2) Only uplift dims where originals > 0
    $wDelta = $oW > 0 ? $rawWdelta : 0;
    $hDelta = $oH > 0 ? $rawHdelta : 0;
    $lDelta = $oL > 0 ? $rawLdelta : 0;

    // 3) Area per combo
    switch ($combo) {
        case 'HxL':
            $area = ($h * $l) / 1000000;
            break;
        case 'WxL':
            $area = ($w * $l) / 1000000;
            break;
        case 'Linear':
            $area = $w / 1000;
            break;
        default:
            $area = 0;
    }

    // 4) Cabinet base cost + height discount
    $baseCab = $area * $price * $qty;
    if ($oH > 0 && $h <= ($oH / 2)) {
        $baseCab *= 0.8;
    }

    // 5) Extra uplift
    $extraCab = ($wDelta + $hDelta + $lDelta) * $basePrice * $qty * 0.01;

    // 6) Totals
    $cabinetCost = $baseCab + $extraCab;
    $laborCost   = $area * $laborRate * $qty;
    $lineTotal   = $cabinetCost + $laborCost;

    $grand    += $lineTotal;
    $laborSum += $laborCost;

    // Store for spreadsheet
    $it['computedArea'] = $area;
    $it['cabinetCost']  = $cabinetCost;
    $it['laborCost']    = $laborCost;
    $it['lineTotal']    = $lineTotal;
}
unset($it);

$final = $grand * (1 - $discount / 100);

// 4) Load template
$template    = __DIR__ . '/../templates/REALIVING_QUOTATION1.xlsx';
$spreadsheet = IOFactory::load($template);
$sheet       = $spreadsheet->getActiveSheet();

// 5) Header fields
$sheet->setCellValue('D5', $clientName);
$sheet->setCellValue('D7', $projectScope);
$sheet->setCellValue('K5', $orderDate);
$sheet->setCellValue('K6', $quotationNo);
$sheet->setCellValue('K7', $contactNo);
$sheet->setCellValue('K8', $address);
$sheet->setCellValue('A11', $installType);

// 6) Insert cart rows
$startRow          = 14;
$dataTemplateRow   = $startRow + 1;
$headerTemplateRow = $startRow;
$firstCol          = 'A';
$lastCol           = 'M';

$dataStyle    = $sheet->getStyle("{$firstCol}{$dataTemplateRow}:{$lastCol}{$dataTemplateRow}");
$dataHeight   = $sheet->getRowDimension($dataTemplateRow)->getRowHeight();
$headerStyle  = $sheet->getStyle("{$firstCol}{$headerTemplateRow}:{$lastCol}{$headerTemplateRow}");
$headerHeight = $sheet->getRowDimension($headerTemplateRow)->getRowHeight();

$row     = $startRow;
$prevCat = null;

foreach ($cart as $idx => $it) {
    // — CATEGORY HEADER (ONLY THIS ROW IS RED) —
    if ($it['category'] !== $prevCat) {
        if ($prevCat !== null) {
            $sheet->insertNewRowBefore($row, 1);
            $sheet->duplicateStyle($headerStyle, "{$firstCol}{$row}:{$lastCol}{$row}");
            $sheet->getRowDimension($row)->setRowHeight($headerHeight);
        }
        $sheet->setCellValue("A{$row}", strtoupper($it['category']));
        $sheet->mergeCells("A{$row}:{$lastCol}{$row}");
        $hdr = $sheet->getStyle("A{$row}:{$lastCol}{$row}");
        $hdr->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('CCCCCC');
        $hdr->getFont()
            ->setBold(true)
            ->getColor()->setRGB('FF0000');

        $prevCat = $it['category'];
        $row++;
    }

    // — DATA ROW (RESET TO DEFAULT BLACK) —
    if ($idx > 0) {
        $sheet->insertNewRowBefore($row, 1);
        $sheet->duplicateStyle($dataStyle, "{$firstCol}{$row}:{$lastCol}{$row}");
        $sheet->getRowDimension($row)->setRowHeight($dataHeight);
    }

    // Reset font color & weight
    $dataRange = "{$firstCol}{$row}:{$lastCol}{$row}";
    $style     = $sheet->getStyle($dataRange);
    $style->getFill()->setFillType(Fill::FILL_NONE);
    $style->getFont()
          ->setBold(false)
          ->getColor()->setRGB('000000');

    // Wrap & auto-height
    $sheet->getStyle("E{$row}")
          ->getAlignment()->setWrapText(true);
    $sheet->getRowDimension($row)->setRowHeight(-1);

    // Populate cells
    $sheet->setCellValue("A{$row}", $idx + 1);
    $sheet->mergeCells("B{$row}:D{$row}");
    $sheet->setCellValue("B{$row}", $it['name']);
    $sheet->setCellValue("E{$row}", "{$it['width']}×{$it['height']}×{$it['length']}");
    $sheet->setCellValue("F{$row}", round($it['computedArea'], 3));
    $sheet->setCellValue("G{$row}", $it['unit']);
    $sheet->setCellValue("H{$row}", $it['quantity']);
    $sheet->setCellValue("I{$row}", round($it['price'], 2));
    $sheet->setCellValue("J{$row}", round($it['cabinetCost'], 2));
    $sheet->setCellValue("K{$row}", round($it['labor'], 2));
    $sheet->setCellValue("L{$row}", round($it['laborCost'], 2));
    $sheet->setCellValue("M{$row}", round($it['lineTotal'], 2));
    $sheet->getStyle("I{$row}:M{$row}")
          ->getNumberFormat()->setFormatCode('#,##0.00');
    $sheet->getStyle("M{$row}")
          ->getFont()->setBold(true);

    $row++;
}

// 7) Summary rows
$sumStart = $row;
$sheet->setCellValue("A{$sumStart}", 'Grand Total:');
$sheet->setCellValue("M{$sumStart}", round($grand, 2));
$sheet->getStyle("M{$sumStart}")
      ->getFont()->setBold(true);
$sheet->getStyle("M{$sumStart}")
      ->getNumberFormat()->setFormatCode('#,##0.00');

$sheet->setCellValue("A" . ($sumStart+1), 'Total Labor:');
$sheet->setCellValue("M" . ($sumStart+1), round($laborSum, 2));
$sheet->getStyle("M" . ($sumStart+1))
      ->getFont()->setBold(true);
$sheet->getStyle("M" . ($sumStart+1))
      ->getNumberFormat()->setFormatCode('#,##0.00');

$sheet->setCellValue("A" . ($sumStart+2), "Discount ({$discount}%):");
$sheet->setCellValue("M" . ($sumStart+2), round(-$grand * $discount / 100, 2));
$sheet->getStyle("M" . ($sumStart+2))
      ->getFont()->setBold(true);
$sheet->getStyle("M" . ($sumStart+2))
      ->getNumberFormat()->setFormatCode('#,##0.00');

$sheet->setCellValue("A" . ($sumStart+3), 'Final Total:');
$sheet->setCellValue("M" . ($sumStart+3), round($final, 2));
$sheet->getStyle("M" . ($sumStart+3))
      ->getFont()->setBold(true);
$sheet->getStyle("M" . ($sumStart+3))
      ->getNumberFormat()->setFormatCode('#,##0.00');

// 8) Signature
$sheet->setCellValue("A" . ($sumStart+20), strtoupper($employeeName));

// 9) Send to browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header(
    'Content-Disposition: attachment; filename="Quotation_'
    . preg_replace('/\W+/', '_', $clientName)
    . '.xlsx"'
);
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
