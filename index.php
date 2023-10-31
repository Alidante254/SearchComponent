<?php 
require_once 'search.php';

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

    $searchComponent = new Search();
    $searchComponent->setDatabaseConfig('config/config.php');

    // Assuming the search method in the Search class returns the mysqli result object
    $result = $searchComponent->search($keyword, $tables);

    if ($result) {
        $data = array(); // Initialize an array to store the data
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Convert the array to JSON
        $jsonResult = json_encode($data);

        return $jsonResult;
    } else {
        // Handle the case where the query fails
        die("Mysqli query failed: " . $searchComponent->getConnection()->error);
    }
}

?>
