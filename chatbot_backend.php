<?php
// Start session if it is not already started
if (!isset($_SESSION)) {
    session_start();  // Ensures that a session is started, if not already active.
}

// Set your OpenAI API key here
define('OPENAI_API_KEY', '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');  // Replace with your actual OpenAI API key

// Database connection details
$servername = "localhost";
$username = "root";  // Replace with your DB username
$password = "ics311";  // Replace with your DB password
$dbname = "dance_ai_db";  // Replace with your DB name
$port = 3307;  // Assuming this is your MySQL port for XAMPP

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to send request to OpenAI API and get chatbot's response
function getChatbotResponse($userMessage) {
    // Prepare the data to send in the request (includes user message and system setup)
    $messages = [
        [
            "role" => "system",
            "content" => "You are a knowledgeable expert specifically on the subject of dance in America. You are only allowed to respond to questions and statements about various dance styles, history, and culture in the United States. If the user asks about any other topics, respond by saying: 'Sorry, I can only help with questions about dance in America.'"
        ],
        [
            "role" => "user",
            "content" => $userMessage
        ]
    ];

    // Prepare the API request data
    $data = [
        'model' => 'gpt-3.5-turbo',  // You can change this to another model like 'gpt-4' or others.
        'messages' => $messages,  // Include the messages array defined above.
        'max_tokens' => 150  // Limit the length of the response from the chatbot.
    ];

    // Initialize the cURL session for sending the request to OpenAI
    $ch = curl_init();  // Initializes a cURL session.

    // Set the URL for the API endpoint (OpenAI Chat API)
    curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');  // API endpoint for chat completions.

    // Set cURL options to return the response as a string (rather than outputting it directly)
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Ensures the response is returned as a string.

    // Specify that this is a POST request (required by OpenAI API for sending data)
    curl_setopt($ch, CURLOPT_POST, true);  // Sets the request type to POST.

    // Pass the data to be sent to OpenAI as a JSON-encoded string
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));  // Encode data to JSON format and include it in the POST body.

    // Set the required HTTP headers for the API request
    curl_setopt($ch, CURLOPT_HTTPHEADER, [  // Set headers for content type and authorization
        'Content-Type: application/json',  // Specify that the content is in JSON format.
        'Authorization: Bearer ' . OPENAI_API_KEY  // Add the authorization header with your OpenAI API key.
    ]);

    // Execute the cURL request and capture the response
    $response = curl_exec($ch);  // Execute the cURL request and store the response.
    
    // Check for any cURL errors
    if ($response === false) {
        echo "Error: " . curl_error($ch);  // If there was an error in the cURL request, display it.
    }

    // Close the cURL session after completing the request
    curl_close($ch);

    // Decode the JSON response from OpenAI into a PHP array
    $decodedResponse = json_decode($response, true);

    // Check if the expected response content exists and return it
    if (isset($decodedResponse['choices'][0]['message']['content'])) {
        return $decodedResponse['choices'][0]['message']['content'];  // Return the response from the chatbot (message content).
    } else {
        return "Sorry, I couldn't process your request. Please try again.";  // Handle the case where no valid response is received.
    }
}

// Check if the user input exists in the POST request (i.e., the message from the user)
if (isset($_POST['input'])) {
    $userMessage = $_POST['input'];  // Retrieve the user message from the POST data.
    $responseMessage = getChatbotResponse($userMessage);  // Get the chatbot's response by calling the function.

    // If user is logged in, get user_id from the session (replace with actual logic)
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;  // Use NULL for guest users if not logged in

    // Prepare and execute the SQL query to insert the interaction into the database
    $stmt = $conn->prepare("INSERT INTO chatbot_interactions (user_id, query, response) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $userMessage, $responseMessage);  // Bind parameters (i.e., user_id, query, and response)

    if ($stmt->execute()) {
        // Return the chatbot's response back to the frontend (e.g., for AJAX response)
        echo $responseMessage;  // Output the chatbot's response to be displayed on the frontend.
    } else {
        echo "Error: " . $stmt->error;  // Display error if the insert fails.
    }

    $stmt->close();  // Close the prepared statement.
    $conn->close();  // Close the database connection.
} else {
    echo "No message received.";  // If no user message was provided, return an error message.
}
?>
