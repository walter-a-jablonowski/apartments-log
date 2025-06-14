<?php
require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

// Get the request data
$requestData = json_decode(file_get_contents('php://input'), true);

// Default response
$response = [
  'success' => false,
  'message' => 'Invalid request',
  'data' => null
];

// Check if action is set
if( isset($requestData['action']) && isset($requestData['page']) ) {
  $action = $requestData['action'];
  $page = $requestData['page'];
  
  // Validate page to prevent directory traversal
  $page = preg_replace('/[^a-zA-Z0-9_]/', '', $page);
  
  // Check if the AJAX handler file exists
  $handlerFile = __DIR__ . "/pages/{$page}/ajax/{$action}.php";
  
  if( file_exists($handlerFile) ) {
    // Include the AJAX handler
    include $handlerFile;
  } else {
    $response['message'] = "Handler not found: {$action}";
  }
} else {
  $response['message'] = 'Missing action or page parameter';
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
