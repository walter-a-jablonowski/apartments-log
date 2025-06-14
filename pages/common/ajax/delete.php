<?php
require_once __DIR__ . '/../../../lib/DataManager.php';

$dataManager = new DataManager();

// Default response
$response = [
  'success' => false,
  'message' => 'Failed to delete common activity entry',
  'data' => null
];

// Check if ID is provided
if( isset($requestData['id']) ) {
  $entryId = (int)$requestData['id'];
  
  // Get existing entry
  $existingEntry = $dataManager->getEntryById($entryId);
  
  if( $existingEntry && $existingEntry['type'] === 'common' ) {
    // Delete entry
    if( $dataManager->deleteEntry($entryId) ) {
      $response['success'] = true;
      $response['message'] = 'Common activity entry deleted successfully';
    }
  } else {
    $response['message'] = 'Common activity entry not found';
  }
} else {
  $response['message'] = 'Missing entry ID';
}
