<?php
require_once __DIR__ . '/../../lib/DataManager.php';

$dataManager = new DataManager();
$entries = $dataManager->getAllEntries();
