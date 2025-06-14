<?php
require_once __DIR__ . '/../../../lib/DataManager.php';

$dataManager = new DataManager();

// Default response
$response = [
  'success' => false,
  'message' => 'Failed to delete status',
  'data' => null
];

// Check if ID and index are provided
if( isset($requestData['id']) && isset($requestData['index']) ) {
  $entryId = (int)$requestData['id'];
  $statusIndex = (int)$requestData['index'];
  
  // Delete status from apartment entry
  if( $dataManager->deleteStatusFromApartment($entryId, $statusIndex) ) {
    $response['success'] = true;
    $response['message'] = 'Status deleted successfully';
  } else {
    $response['message'] = 'Failed to delete status. Entry not found, not an apartment, or status index invalid.';
  }
} else {
  $response['message'] = 'Missing entry ID or status index';
}
