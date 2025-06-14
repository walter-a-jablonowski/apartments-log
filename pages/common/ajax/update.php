<?php
require_once __DIR__ . '/../../../lib/DataManager.php';

$dataManager = new DataManager();

// Default response
$response = [
  'success' => false,
  'message' => 'Failed to update common activity entry',
  'data' => null
];

// Check if entry data and ID are provided
if( isset($requestData['entry']) && is_array($requestData['entry']) && isset($requestData['id']) ) {
  $entryId = (int)$requestData['id'];
  $updatedEntry = $requestData['entry'];
  
  // Get existing entry
  $existingEntry = $dataManager->getEntryById($entryId);
  
  if( $existingEntry && $existingEntry['type'] === 'common' ) {
    // Validate required fields
    if( empty($updatedEntry['title']) || empty($updatedEntry['date']) || empty($updatedEntry['text']) ) {
      $response['message'] = 'Missing required fields';
    } else {
      // Preserve existing fields that shouldn't be changed
      $updatedEntry['id'] = $entryId;
      $updatedEntry['type'] = 'common';
      
      // Update entry
      if( $dataManager->updateEntry($entryId, $updatedEntry) ) {
        $response['success'] = true;
        $response['message'] = 'Common activity entry updated successfully';
        $response['data'] = ['id' => $entryId];
      }
    }
  } else {
    $response['message'] = 'Common activity entry not found';
  }
} else {
  $response['message'] = 'Missing entry data or ID';
}
