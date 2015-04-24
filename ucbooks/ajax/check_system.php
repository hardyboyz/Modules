<?php

if (!empty($_GET['action']) && $_GET['action'] == 'contact') {
    $id = $_GET['id'];
    $data = array();
    foreach ($id as $key => $each) {
        $sql = 'SELECT id,bill_acct_id,bill_primary_name,closed from journal_main where journal_id="' . $_GET['jID'] . '" and id="' . $each . '"';
        $result = $db->Execute($sql);

        while (!$result->EOF) {
            $data['name'][] = $result->fields['bill_primary_name'];
            $data['id'][] = $result->fields['id'];
            $data['closed'][] = $result->fields['closed'];
            $result->MoveNext();
        }
    }
}

echo json_encode($data);
?>
