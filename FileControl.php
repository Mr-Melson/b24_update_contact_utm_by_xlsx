<?
require_once (__DIR__ . '/DataBase.php');
require_once (__DIR__ . '/SimpleXLSX.php');
use Shuchkin\SimpleXLSX;

$file_name = $_POST['file_name'];

if ( $xlsx = SimpleXLSX::parse('xlsx/'.$file_name.'.xlsx') ) {

    $rows = $xlsx->rows();

    for ($i=1; $i < count($rows); $i++) {

        if (
            $rows[$i][5] != '' ||
            $rows[$i][6] != '' ||
            $rows[$i][7] != '' ||
            $rows[$i][8] != '' ||
            $rows[$i][9] != ''
        ) {

            $email          = $contacts_utm_database->real_escape_string( $rows[$i][3] );
            $phone          = $contacts_utm_database->real_escape_string( $rows[$i][2] );
            $utm_source     = $contacts_utm_database->real_escape_string( $rows[$i][5] );
            $utm_medium     = $contacts_utm_database->real_escape_string( $rows[$i][6] );
            $utm_campaign   = $contacts_utm_database->real_escape_string( $rows[$i][7] );
            $utm_term       = $contacts_utm_database->real_escape_string( $rows[$i][8] );
            $utm_content    = $contacts_utm_database->real_escape_string( $rows[$i][9] );

            $contacts_utm_database->query(
                "INSERT INTO `Contacts_UTM` (
                    email,
                    phone,
                    utm_source,
                    utm_medium,
                    utm_campaign,
                    utm_term,
                    utm_content
                ) 
                VALUES (
                    '$email',
                    '$phone',
                    '$utm_source',
                    '$utm_medium',
                    '$utm_campaign',
                    '$utm_term',
                    '$utm_content'
                )"
            );
        }
    }

    $json_rows = json_encode($json_rows);
    file_put_contents(__DIR__ . '/xlsx/json_rows.json', $json_rows);

} else {
    echo SimpleXLSX::parseError();
}