<?php
require_once __DIR__ . '/../../../lib/DataManager.php';

$dataManager = new DataManager();

// Default response
$response = [
  'success' => false,
  'message' => 'Failed to update status',
  'data' => null
];

// Check if ID, index and status data are provided
if( isset($requestData['id']) && isset($requestData['index']) && isset($requestData['status']) && is_array($requestData['status']) ) {
  $entryId = (int)$requestData['id'];
  $statusIndex = (int)$requestData['index'];
  $status = $requestData['status'];
  
  // Validate status data
  if( empty($status['text']) ) {
    $response['message'] = 'Status text is required';
  } else {
    // Update status in apartment entry
    if( $dataManager->updateStatusInApartment($entryId, $statusIndex, $status) ) {
      $response['success'] = true;
      $response['message'] = 'Status updated successfully';
    } else {
      $response['message'] = 'Failed to update status. Entry not found, not an apartment, or status index invalid.';
    }
  }
} else {
  $response['message'] = 'Missing entry ID, status index, or status data';
}
