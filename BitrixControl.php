<?
require_once(__DIR__ . '/vendor/autoload.php');

use App\Bitrix24\Bitrix24API;
use App\Bitrix24\Bitrix24APIException;

$modificator = 50;
$page = ($_POST['page'] ?? 0) * $modificator;
$page_item_start = $page > 0 ? $page : 1;
$page_item_end = $page_item_start + $modificator;

$json_file_name = __DIR__ . '/xlsx/json_rows.json';

if (!file_exists($json_file_name)) {
    echo json_encode([
        'count_rows' => -1,
    ]);
} else{

    if (isset($_POST['b24_source'])) {
        
        $json_file = file_get_contents($json_file_name);
        $json_rows = json_decode($json_file, true);
        $count_rows = count($json_rows);
    
        try {
    
            $webhookURL = $_POST['b24_source'];
            $bx24 = new Bitrix24API($webhookURL);
            $updated_contact = [];
    
            for ($i = $page_item_start; $i < $page_item_end; $i++) {
    
                if (
                    $json_rows[$i] &&
                    (
                        $json_rows[$i][5] != '' ||
                        $json_rows[$i][6] != '' ||
                        $json_rows[$i][7] != '' ||
                        $json_rows[$i][8] != '' ||
                        $json_rows[$i][9] != ''
                    )
                ) {
    
                    $contact_results = [];
    
                    if ($json_rows[$i][2] != '') {
    
                        $phone = str_replace('-', '', (string) $json_rows[$i][2]);
                        $phone = str_replace(' ', '', $phone);
                        $phone = str_replace('+', '', $phone);
                        $phone = '+'.$phone;
                        $contact_results = $bx24->getContactsByPhone(
                            $phone,
                            ['ID', 'NAME']
                        );
                    }
    
                    if (empty($contact_results) && $json_rows[$i][3] != '') {
                        $contact_results = $bx24->fetchContactList(
                            ['EMAIL' => $json_rows[$i][3]],
                            ['ID', 'NAME'],
                            []
                        );
                    }
    
                    foreach ($contact_results as $contacts) {
    
                        sleep(0.6);
                        $result = $bx24->updateContact(
                            $contacts['ID'],
                            [
                                'UTM_SOURCE'    => $json_rows[$i][5],
                                'UTM_MEDIUM'    => $json_rows[$i][6],
                                'UTM_CAMPAIGN'  => $json_rows[$i][7],
                                'UTM_TERM'      => $json_rows[$i][8],
                                'UTM_CONTENT'   => $json_rows[$i][9],
                            ],
                            []
                        );
    
                        if ($result) {
                            $updated_contact[$contacts['ID']] = $contacts['NAME'];
                        }
    
                        sleep(0.6);
                    }
                }
            }
    
            echo json_encode([
                'count_rows' => $count_rows,
                'result' => $updated_contact
            ]);
    
        } catch (Bitrix24APIException $e) {
            printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
        } catch (Exception $e) {
            printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
        }
    }
}

