<?php
require_once(__DIR__ . '/../tcpdf/tcpdf.php');
include('../db.php');

function generateInvoice($orderId) {
    global $conn;

    $orderId = intval($orderId);

    // Fetch order info
    $orderQuery = mysqli_query($conn, "SELECT * FROM orders WHERE id = '$orderId'");
    if (!$orderQuery || mysqli_num_rows($orderQuery) == 0) {
        return false;
    }
    $order = mysqli_fetch_assoc($orderQuery);

    // Fetch order items with product names
    $query = "
    SELECT oi.*, p.name as product_name
    FROM order_items oi
    LEFT JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = $orderId
    ";
    $itemQuery = mysqli_query($conn, $query);

    $grandTotal = 0;

    class MYPDF extends TCPDF {
        public function Header() {
            $this->Image('../bgremove_img/invoice_logo.jpg', 15, 10, 25);
            $this->SetY(15);
            $this->SetFont('helvetica', 'B', 20);
            $this->SetTextColor(60,139,114);
            $this->Cell(0, 10, 'YogaClass - Order Invoice', 0, 1, 'C');
            $this->SetFont('helvetica', '', 13);
            $this->SetTextColor(46,139,114);
            $this->Cell(0, 8, ' Order Placed Successfully!', 0, 1, 'C');
            $this->Ln(5);
        }

        public function Footer() {
            $this->SetY(-18);
            $this->SetDrawColor(124, 191, 142);
            $this->SetLineWidth(0.3);
            $this->Line(15, $this->GetY(), $this->w - 15, $this->GetY());
            $this->SetFont('helvetica', 'I', 8);
            $this->SetTextColor(100, 100, 100);
            $this->Cell(0, 10, 'YogaClass • www.yogaclass.com • support@yogaclass.com • +91-9876543210', 0, 0, 'L');
            $this->SetTextColor(150, 150, 150);
            $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' / '.$this->getAliasNbPages(), 0, 0, 'R');
        }
    }

    $pdf = new MYPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('YogaClass');
    $pdf->SetTitle('Order Invoice #' . $orderId);
    $pdf->SetMargins(15, 45, 15);
    $pdf->SetAutoPageBreak(TRUE, 25);
    $pdf->AddPage();

    $customerHTML = '
    <h3 style="color:#00796b;">Customer Info:- </h3>
    <table cellspacing="2" cellpadding="4">
    <tr><td><b>Name:</b></td><td>' . htmlspecialchars($order['name']) . '</td></tr>
    <tr><td><b>Email:</b></td><td>' . htmlspecialchars($order['email']) . '</td></tr>
    <tr><td><b>Phone:</b></td><td>' . htmlspecialchars($order['phone']) . '</td></tr>
    <tr><td><b>Shipping Address:</b></td><td>' . nl2br(htmlspecialchars($order['shipping_address'])) . '</td></tr>
    <tr><td><b>Order Date:</b></td><td>' . htmlspecialchars($order['order_date']) . '</td></tr>
    <tr><td><b>Order ID:</b></td><td>#' . htmlspecialchars($order['id']) . '</td></tr>
    </table>';
    $pdf->writeHTML($customerHTML, true, false, false, false, '');

    $html = '<h3 style="color:#2e8b72;">Items Ordered</h3>
    <table border="1" cellpadding="5" cellspacing="0">
    <tr style="background-color:#2e8b72; color:#fff;">
        <th width="60%">Product</th><th width="20%">Qty</th><th width="20%">Price</th>
    </tr>';

    $fill = false;
    while ($item = mysqli_fetch_assoc($itemQuery)) {
        $total = $item['price'] * $item['quantity'];
        $grandTotal += $total;
        $bg = $fill ? '#f1f8e9' : '#ffffff';
        $html .= '<tr style="background-color:' . $bg . ';">
            <td>' . htmlspecialchars($item['product_name']) . '</td>
            <td align="center">' . $item['quantity'] . '</td>
            <td align="right">' . number_format($item['price'], 2) . ' RS</td>
        </tr>';
        $fill = !$fill;
    }

    $html .= '<tr style="background-color:#e8f5e9; font-weight:bold;">
        <td colspan="2" align="right">Total Paid:</td>
        <td align="right">' . number_format($grandTotal, 2) . ' RS</td>
    </tr>
    </table>';
    $pdf->writeHTML($html, true, false, false, false, '');

    $filename = 'YogaClass_Invoice_' . $orderId . '.pdf';
    $filepath = __DIR__ . '/../invoices/' . $filename;

    // Ensure invoices directory exists
    if (!is_dir(__DIR__ . '/../invoices')) {
        mkdir(__DIR__ . '/../invoices', 0777, true);
    }

    $pdf->Output($filepath, 'F'); // Save to server

    return $filepath; // Return file path
}
?>
