<?
require_once (__DIR__ . '/db_settings.php');

$contacts_utm_database = new mysqli($servername, $username, $password, $dbname);

if ($contacts_utm_database->connect_error) {
    die("Connection failed: " . $contacts_utm_database->connect_error);
}

$result = $contacts_utm_database->query("SHOW TABLES LIKE 'Contacts_UTM'");

if ($result->num_rows == 1) {
    return;
} else {

    echo "Table does not exist";

    $sql = "CREATE TABLE Contacts_UTM (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(30) NOT NULL,
        phone VARCHAR(30) NOT NULL,
        utm_source VARCHAR(30) NOT NULL,
        utm_medium VARCHAR(50),
        utm_campaign VARCHAR(50),
        utm_term VARCHAR(50),
        utm_content VARCHAR(50)
        )";

    if ($contacts_utm_database->query($sql) === TRUE) {
        echo "<br>Table Contacts_UTM created successfully";
    } else {
        echo "<br>Error creating table: " . $contacts_utm_database->error;
    }
}
