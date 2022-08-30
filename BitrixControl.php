<?
ini_set('max_execution_time', 3000);

require_once(__DIR__ . '/vendor/autoload.php');
require_once (__DIR__ . '/DataBase.php');

use App\Bitrix24\Bitrix24API;
use App\Bitrix24\Bitrix24APIException;

$json_file_name = __DIR__ . '/xlsx/json_rows.json';

if (!file_exists($json_file_name)) {
    echo json_encode([
        'result' => -1,
    ]);
} elseif (isset($_POST['b24_source'])) {
        
    $json_file = file_get_contents($json_file_name);
    $json_rows = json_decode($json_file, true);

    try {

        $webhookURL = $_POST['b24_source'];
        $bx24 = new Bitrix24API($webhookURL);

        if (isset($_POST['page'])) {
            $offset = (int)$_POST['page'] * 100;
        } else {
            $offset = 0;
        }

        sleep(0.6);
        
        $generator = $bx24->fetchContactList(
            [
                $offset,
                'UTM_SOURCE'    => '',
                'UTM_MEDIUM'    => '',
                'UTM_CAMPAIGN'  => '',
                'UTM_TERM'      => '',
                'UTM_CONTENT'   => '',
            ],
            ['EMAIL'],
            []
        );

        $updated_contacts = [];

        foreach ($generator as $contacts) {
            
            $count_contacts = count((array)$contacts);

            foreach ($contacts as $contact) {

                foreach ($contact['EMAIL'] as $contact_email) {

                    $current_email = $contact_email['VALUE'];

                    $contact_query = $contacts_utm_database->query(
                        "SELECT * FROM Contacts_UTM WHERE email='$current_email' LIMIT 1"
                    );

                    if ($contact_query->num_rows > 0) {

                        $row = $contact_query->fetch_row();

                        $updated_contacts[] = [
                            'ID'            => $contact['ID'],
                            'UTM_SOURCE'    => $row[3],
                            'UTM_MEDIUM'    => $row[4],
                            'UTM_CAMPAIGN'  => $row[5],
                            'UTM_TERM'      => $row[6],
                            'UTM_CONTENT'   => $row[7],
                        ];

                        break;
                    }
                }
            }
        }

        if (!empty($updated_contacts)) {

            sleep(0.6);

            $contact_results = $bx24->updateContacts($updated_contacts);

            echo json_encode([
                'count_contacts' => $count_contacts,
                'result' => implode(', ', $contact_results)
            ]);
        } else {
            echo json_encode([
                'count_contacts' => $count_contacts,
                'result' => 'Not found'
            ]);
        }

        if ($count_contacts < 50 && $offset > 28000) {
            $select = $contacts_utm_database->query( "DROP TABLE Contacts_UTM" );
        }

    } catch (Bitrix24APIException $e) {
        printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
    } catch (Exception $e) {
        printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
    }
}

