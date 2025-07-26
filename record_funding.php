<?php
include 'config/db.php';

$startup_id = $_POST['startup_id'];
$amount = $_POST['amount'];
$tx_hash = $_POST['tx_hash'];

$tx_log = "\n" . date("Y-m-d H:i:s") . " | TX: $tx_hash | Amount: $amount ETH";

$stmt = $conn->prepare("UPDATE startups SET fund_raised = fund_raised + ?, funding_tx = CONCAT(IFNULL(funding_tx, ''), ?) WHERE id = ?");
$stmt->bind_param("dsi", $amount, $tx_log, $startup_id);
$stmt->execute();
?>