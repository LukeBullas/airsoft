<?php

// Include the database connection file
include 'localdb_connection.php';


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");


// Check if the request is an OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Respond to the preflight request
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    http_response_code(200);
    exit;
}

$skip = FALSE;

// Check if the request is a GET or POST request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Extract the endpoint from the REQUEST_URI
    $endpoint = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Trim the leading slash from the endpoint
    $endpoint = ltrim($endpoint, '/');

    // Determine the endpoint and set the query accordingly
    switch ($endpoint) {
        case 'api.php/bookingsAll':
            // Query to select all items from the database
            $query = "SELECT * FROM login";
            break;
            
        case 'api.php/usersAll':
            // Query to select all items from the database
            $query = "SELECT * FROM bookings";
            break;

    }

    if ($skip == FALSE){
        // Execute the query
        $result = $mysqli->query($query);

        // Check if the query was successful
        if ($result) {
            // Fetch the result as an associative array
            $data = $result->fetch_all(MYSQLI_ASSOC);

            // Close the database connection
            $mysqli->close();

            // Set the content type header
            header('Content-Type: application/json');

            // Return the data as JSON
            echo json_encode($data);
        } else {
            // If the query fails, return an error response
            http_response_code(500);
            echo json_encode(array('error' => 'Failed to fetch data'));
        }
    }
} 

elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST requests

    // Extract the endpoint from the REQUEST_URI
    $endpoint = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Trim the leading slash from the endpoint
    $endpoint = ltrim($endpoint, '/');

    // Determine the endpoint and set the query accordingly
    switch ($endpoint) {
        case 'api.php/userEntry':
            // Retrieve the POST data
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Validate the data (you should perform proper validation and authentication here)

            // Insert the data into the database
            $query = "INSERT INTO login (username, password, id) VALUES (?,?,1)";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("ssss", $username, $password, $id);

            if ($stmt->execute()) {
                // If the query was successful, return a success message
                $response = array('message' => 'Data inserted successfully');
            } else {
                // If the query fails, return an error response
                $response = array('error' => 'Failed to insert data');
            }

            // Set the content type header
            header('Content-Type: application/json');

            // Return the response as JSON
            echo json_encode($response);

            $stmt->close();
            break;

        default:
            // If the endpoint is not recognized, return a 404 error
            http_response_code(404);
            echo json_encode(array('error' => 'Endpoint not found'));
            exit();
    }
}

elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST requests

    // Extract the endpoint from the REQUEST_URI
    $endpoint = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Trim the leading slash from the endpoint
    $endpoint = ltrim($endpoint, '/');

    // Determine the endpoint and set the query accordingly
    switch ($endpoint) {
        case 'api.php/bookingEntry':
            // Retrieve the POST data
            $booking_date = $_POST['booking_date'];
            $booking_time = $_POST['booking_time'];

            // Validate the data (you should perform proper validation and authentication here)

            // Insert the data into the database
            $query = "INSERT INTO bookings (booking_date, booking_time) VALUES (?,?)";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("ssss", $booking_date, $booking_time);

            if ($stmt->execute()) {
                // If the query was successful, return a success message
                $response = array('message' => 'Data inserted successfully');
            } else {
                // If the query fails, return an error response
                $response = array('error' => 'Failed to insert data');
            }

            // Set the content type header
            header('Content-Type: application/json');

            // Return the response as JSON
            echo json_encode($response);

            $stmt->close();
            break;

        default:
            // If the endpoint is not recognized, return a 404 error
            http_response_code(404);
            echo json_encode(array('error' => 'Endpoint not found'));
            exit();
    }
}

elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    
     $endpoint = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Trim the leading slash from the endpoint
    $endpoint = ltrim($endpoint, '/');

    // Determine the endpoint and set the query accordingly
    switch ($endpoint) {
        case 'api.php/userSignup':
    
          $user = $_POST['user'];
          $pass = $_POST['pass'];

          // Insert values into table
          $sql = "INSERT INTO login (username, password, id) VALUES (?, ?, 2)";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("ss", $user, $pass);
          $stmt->execute();

          // Check if insertion was successful
          if ($stmt->affected_rows > 0) {
            http_response_code(201); // Created
            echo json_encode(array('message' => 'Sign up successful!'));
          } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(array('error' => 'Error: ' . $stmt->error));
  }

  $stmt->close();
  break;
}

// Check if the request is a POST request
elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $endpoint = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Trim the leading slash from the endpoint
    $endpoint = ltrim($endpoint, '/');

    // Determine the endpoint and set the query accordingly
    switch ($endpoint) {
        case 'api.php/userLogin':
    // Retrieve the POST data
  $user = $_POST['user'];
  $pass = $_POST['pass'];

  // Prepare the SQL statement
  $sql = "SELECT password FROM login WHERE username =?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $user);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  // Check if the user exists
  if ($row) {
    $stored_password = $row['password'];

    // Check if the input password matches the stored password
    if ($pass === $stored_password) {
      // Return a success response
      http_response_code(200);
      echo json_encode(array('message' => 'Login successful'));
      exit;
    } else {
      // Return an error response
      http_response_code(401);
      echo json_encode(array('error' => 'Incorrect password'));
    }
  } else {
    // Return an error response
    http_response_code(404);
    echo json_encode(array('error' => 'User not found'));
  }

  $stmt->close();
  break;
} 


elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Handle DELETE requests

    // Extract the endpoint from the REQUEST_URI
    $endpoint = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Trim the leading slash from the endpoint
    $endpoint = ltrim($endpoint, '/');

    // Determine the endpoint and set the query accordingly
    switch ($endpoint) {
        case 'api.php/userDelete':
            // Extract data from the request
            parse_str(file_get_contents("php://input"), $delete_vars);
            $id = intval($delete_vars['id']); // Ensure ID is treated as an integer

            $sql = "DELETE FROM booking WHERE id = '$id'";

            // Prepare the SQL statement
            if ($stmt = $mysqli->prepare($sql)) {
                // Execute the statement
                if ($stmt->execute()) {
                    // Return the response as JSON
                    echo "record deleted!";
                } else {
                    // Return the response as JSON
                    echo "error";
                }

                // Close the statement
                $stmt->close();
            } else {
                // Return the response as JSON
                echo "error";
            }

            // Close the connection
            $mysqli->close();
            break;

        default:
            // If the endpoint is not recognized, return a 404 error
            http_response_code(404);
            echo "error";
            exit();
    }
}


 else {
    // If the request method is not GET or POST, return a method not allowed error
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}

?>
