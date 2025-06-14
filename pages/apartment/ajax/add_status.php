<?php
require_once __DIR__ . '/../../../lib/DataManager.php';

$dataManager = new DataManager();

// Default response
$response = [
  'success' => false,
  'message' => 'Failed to add status update',
  'data' => null
];

// Check if ID and status data are provided
if( isset($requestData['id']) && isset($requestData['status']) && is_array($requestData['status']) ) {
  $entryId = (int)$requestData['id'];
  $status = $requestData['status'];
  
  // Validate status data
  if( empty($status['text']) ) {
    $response['message'] = 'Status text is required';
  } else {
    // Add status to apartment entry
    if( $dataManager->addStatusToApartment($entryId, $status) ) {
      $response['success'] = true;
      $response['message'] = 'Status update added successfully';
    } else {
      $response['message'] = 'Failed to add status update. Entry not found or not an apartment.';
    }
  }
} else {
  $response['message'] = 'Missing entry ID or status data';
}
