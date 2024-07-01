<?php
function getAllFeedbacks(){
    // Connect to the database using the SingletonConnectionToDb class
    $dbConnection = SingletonConnectionToDb::getInstance()->getConnection();
    
    // Prepare a query to fetch all feedback entries
    $query = "SELECT * FROM feedback";
    $statement = $dbConnection->prepare($query);
    $statement->execute();
    $result = $statement->get_result();
    
    // Fetch feedback entries
    $feedbacks = array(); // $feedback = []
    while ($row = $result->fetch_assoc()) {
        // Create a Feedback object for each row and add it to the array
        $feedbacks[] = new Feedback($row['_id'], $row['feedBack'], $row['howMuch'],$row['acceptance'], $row['homeDisplayed'], $row['userId']);
    }
    
    // Free result and close statement
    $statement->close();
    
    // Return all feedback entries
    return $feedbacks;
}

function getAllFeedbacksThatShouldDisplayed(){
    // Assuming getAllFeedbacks() function is available and correctly implemented
    $allFeedbacks = getAllFeedbacks();
    
    // Array to store filtered feedbacks
    $filteredFeedbacks = array();
    
    // Iterate through all feedbacks
    foreach($allFeedbacks as $feedback){
        // Check if the feedback should be displayed on the home page
        if($feedback->homeDisplayed === 1){
            // Add the feedback to the filtered array
            $filteredFeedbacks[] = $feedback;
        }
    }
    
    // Return filtered feedbacks
    return $filteredFeedbacks;
}
function analyzeSentiment($text) { 
    // laptop ali         API                           ai model
    // API endpoint and headers
    $url = "https://aspect-based-sentiment-analysis.p.rapidapi.com/topic-sentiment?domain=generic";
    $headers = [
        "Accept: application/json",
        "Content-Type: application/json",
        "x-rapidapi-host: aspect-based-sentiment-analysis.p.rapidapi.com",
        "x-rapidapi-key: 5c48e68c61msh30073016ff70659p1fb999jsnabe8217132d0"
    ];
    // Data payload
    $data = [
        [
            'id' => 1,
            'language' => 'en',
            'text' => $text
        ]
    ];

    // Initialize curl session
    $ch = curl_init();

    // Set curl options
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => $headers,
    ]);

    // Execute curl session
    $response = curl_exec($ch);
    $err = curl_error($ch);

    // Close curl session
    curl_close($ch);

    // Check for curl errors
    if ($err) {
        return "Error: Curl error - " . $err;
    }

    // Decode JSON response
    $result = json_decode($response, true);
    // [1,1,1,1] => 40%
    // Check if sentiments are present in the response
    if (isset($result[0]['sentiments'])) {
        $sentiments = $result[0]['sentiments'];

        // Calculate positive and negative scores
        $positive_count = 0;
        $total = count($sentiments); // positive = [1,1,1]:30% || negative = []

        foreach ($sentiments as $sentiment) {
            if ($sentiment['positive']) {
                $positive_count++;
            }
        }

        // Determine sentiment result
        // 100 
        // 70% positive // 30& 
        if ($total > 0) {
            $positive_percentage = ($positive_count / $total) * 100;
            $negative_percentage = (( $total - $positive_count ) / $total) * 100;

            if ($positive_percentage > 50) {
                return "Positive," . number_format($positive_percentage, 2);
            } elseif ($negative_percentage > 50) {
                return "Negative," . number_format($negative_percentage, 2);
            } else {
                return "Neutral,"."50";
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function insertFeedbackIntoDatabase($feedback, $range, $userId) {
    // Connect to the database using the SingletonConnectionToDb class
    $dbConnection = SingletonConnectionToDb::getInstance()->getConnection();

    // Check if the user already has feedback and delete it
    $deleteQuery = "DELETE FROM feedback WHERE userId = ?";
    $deleteStatement = $dbConnection->prepare($deleteQuery);
    $deleteStatement->bind_param("i", $userId);
    $deleteStatement->execute();
    $deleteStatement->close();

    // Prepare the SQL statement to insert feedback data
    // howmuch = 100
    $query = "INSERT INTO feedback (feedBack, howMuch, userId , acceptance,homeDisplayed) VALUES (?, ?, ?, ?,?)";
    $statement = $dbConnection->prepare($query);
    $result = analyzeSentiment($feedback);//positive, 80%
    $acceptance = 0;
    $homeDisplayed = 1;
    if($result !== false){
        $reslutSplited  = explode(",",$result);//postive,80%
        $acceptance = $reslutSplited[1];
        if($reslutSplited[0] == "Negative"){
            $homeDisplayed = 0;
        }
    }
    // Bind parameters to the prepared statement
    $statement->bind_param("siiii", $feedback, $range, $userId,$acceptance,$homeDisplayed);

    // Execute the query
    if ($statement->execute()) {
        // If the execution is successful, return true
        return true;
    } else {
        // If the execution fails, return false
        return false;
    }
}

function deleteReview($_id){
    // Get database connection using Singleton pattern
    $dbConnection = SingletonConnectionToDb::getInstance()->getConnection();

    // SQL statement to delete record from feedback table
    $sql = "DELETE FROM `feedback` WHERE _id = ?";

    // Prepare the statement
    $stmt = $dbConnection->prepare($sql);

    // Bind parameters (s for string, i for integer, etc. based on the type)
    $stmt->bind_param("i", $_id); // Assuming _id is an integer

    // Execute the statement
    if ($stmt->execute()) {
        // Return true if deletion was successful
        return true;
    } else {
        // Return false or handle the error accordingly
        return false;
    }
}

function display($_id){
    // Get database connection using Singleton pattern
    $dbConnection = SingletonConnectionToDb::getInstance()->getConnection();

    // SQL statement to delete record from feedback table
    $sql = "UPDATE `feedback` SET `homeDisplayed`= 1 where `feedback`.`_id` = ?";

    // Prepare the statement
    $stmt = $dbConnection->prepare($sql);

    // Bind parameters (s for string, i for integer, etc. based on the type)
    $stmt->bind_param("i", $_id); // Assuming _id is an integer

    // Execute the statement
    if ($stmt->execute()) {
        // Return true if deletion was successful
        return true;
    } else {
        // Return false or handle the error accordingly
        return false;
    }
}




