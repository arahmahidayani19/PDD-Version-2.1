<?php
include '../koneksi.php';

$docColumns = array();

$sqlColumns = "SHOW COLUMNS FROM machine_documents";
$resultColumns = mysqli_query($conn, $sqlColumns);

if ($resultColumns) {
    while ($col = mysqli_fetch_assoc($resultColumns)) {
        $field = $col['Field'];
        if ($field == 'id' || $field == 'machine_name') {
            continue;
        }
        if (substr($field, -5) === '_path') {
            $docColumns[] = $field;
        }
    }
} else {
    $docColumns = array();
}
