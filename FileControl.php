<?
$file_name = $_POST['file_name'];

require_once (__DIR__ . '/SimpleXLSX.php');
use Shuchkin\SimpleXLSX;

if ( $xlsx = SimpleXLSX::parse('xlsx/'.$file_name.'.xlsx') ) {

    $json_rows = json_encode( $xlsx->rows() );
    file_put_contents(__DIR__ . '/xlsx/json_rows.json', $json_rows);

} else {
    echo SimpleXLSX::parseError();
}