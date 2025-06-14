<?php
require_once __DIR__ . '/../../../lib/DataManager.php';

$dataManager = new DataManager();

// Default response
$response = [
  'success' => false,
  'message' => 'Failed to delete apartment entry',
  'data' => null
];

// Check if ID is provided
if( isset($requestData['id']) ) {
  $entryId = (int)$requestData['id'];
  
  // Get existing entry
  $existingEntry = $dataManager->getEntryById($entryId);
  
  if( $existingEntry && $existingEntry['type'] === 'apartment' ) {
    // Delete entry
    if( $dataManager->deleteEntry($entryId) ) {
      $response['success'] = true;
      $response['message'] = 'Apartment entry deleted successfully';
    }
  } else {
    $response['message'] = 'Apartment entry not found';
  }
} else {
  $response['message'] = 'Missing entry ID';
}
