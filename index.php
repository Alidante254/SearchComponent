<?php 
#get the external files
require_once 'search/search.php';
require_once 'config/check_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read the JSON request body
    $request = json_decode(file_get_contents('php://input'), true);

    if (isset($request['keyword'])) {

        $keyword = $request['keyword'];
        $tables = $request['tables'];
        // Send JSON data to the JavaScript file
        header('Content-Type: application/json');
        echo SearchQuery($keyword, $tables);
        
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Keyword is missing']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

function SearchQuery($keyword, $tables) {
    #define path to the config files
    $configFile = 'config/config.php';
    $envFile = '../db.env';
    checkConfigFile($configFile, $envFile);

    $searchComponent = new Search();
    $searchComponent->setDatabaseConfig('config/config.php');

    // Assuming the search method in the Search class returns the mysqli result object
    $result = $searchComponent->search($keyword, $tables);

    if ($result) {
        $data = array(); // Initialize an array to store the data
       
        while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

           
        $jsonResult = json_encode($data);

        return $jsonResult;
                
    }
}

?>
