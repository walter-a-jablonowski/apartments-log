<?php
require_once __DIR__ . '/../../../lib/DataManager.php';

$dataManager = new DataManager();

// Default response
$response = [
  'success' => false,
  'message' => 'Failed to create common activity entry',
  'data' => null
];

// Check if entry data is provided
if( isset($requestData['entry']) && is_array($requestData['entry']) ) {
  $entry = $requestData['entry'];
  
  // Validate required fields
  if( empty($entry['title']) || empty($entry['date']) || empty($entry['text']) ) {
    $response['message'] = 'Missing required fields';
  } else {
    // Add required fields
    $entry['id'] = $dataManager->getNextEntryId();
    $entry['type'] = 'common';
    
    // Save entry
    if( $dataManager->addEntry($entry) ) {
      $response['success'] = true;
      $response['message'] = 'Common activity entry created successfully';
      $response['data'] = ['id' => $entry['id']];
    }
  }
} else {
  $response['message'] = 'No entry data provided';
}
