<?
$file_name = $_POST['file_name'];

require_once (__DIR__ . '/SimpleXLSX.php');
use Shuchkin\SimpleXLSX;

if ( $xlsx = SimpleXLSX::parse('xlsx/'.$file_name.'.xlsx') ) {

    for ($i=1; $i < count($xlsx->rows()); $i++) {

        if (
            $xlsx->rows()[$i][5] != '' ||
            $xlsx->rows()[$i][6] != '' ||
            $xlsx->rows()[$i][7] != '' ||
            $xlsx->rows()[$i][8] != '' ||
            $xlsx->rows()[$i][9] != ''
        ) {
            
            $json_rows[$xlsx->rows()[$i][3]]['phone']         = $xlsx->rows()[$i][2];
            $json_rows[$xlsx->rows()[$i][3]]['utm_source']    = $xlsx->rows()[$i][5];
            $json_rows[$xlsx->rows()[$i][3]]['utm_medium']    = $xlsx->rows()[$i][6];
            $json_rows[$xlsx->rows()[$i][3]]['utm_campaign']  = $xlsx->rows()[$i][7];
            $json_rows[$xlsx->rows()[$i][3]]['utm_term']      = $xlsx->rows()[$i][8];
            $json_rows[$xlsx->rows()[$i][3]]['utm_content']   = $xlsx->rows()[$i][9];
        }
    }

    $json_rows = json_encode($json_rows);
    file_put_contents(__DIR__ . '/xlsx/json_rows.json', $json_rows);

} else {
    echo SimpleXLSX::parseError();
}