<?php
// Set as JSON Response
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("HTTP/1.0 404 Not Found");
  echo '{error: "Method not allowed"}';
  exit;
}

// Try read our JSON fomat data
try {
  $postData = file_get_contents('php://input');
  $post = json_decode($postData, true);
} catch (Exception $E) {
  echo '{error: "' + $E->getMessage() + '"}';
  exit;
}


$data = [];

$data ['address1'] = $post['Address1'];
$data ['address2'] = $post['Address2'];
$data ['city'] = $post['City'];
$data ['state'] = $post['State'];
$data ['zip'] = $post['Zip5'];

// Add composer requirements to add Query builder
// I use query builder outside because not so easy 
// keep all queries safe and this save my time
// because now 4am and I want sleep :P 
// Docs here: https://meekro.com/docs/retrieving-data.html
require_once(__DIR__ . '/vendor/autoload.php');
// Loading for .env at the root directory
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

/** Setup DB */
DB::$user = $_ENV['DATABASE_USER'];
DB::$password = $_ENV['DATABASE_PASS'];
DB::$dbName = $_ENV['DATABASE_NAME'];
DB::$host = $_ENV['DATABASE_HOST']; //defaults to localhost if omitted
DB::$port = $_ENV['DATABASE_PORT']; // defaults to 3306 if omitted
DB::$encoding = 'utf8'; // defaults to latin1 if omitted


$database = $_ENV['DATABASE_NAME'];
$datatable = $_ENV['DATABASE_TABLE'];

$table = DB::queryFirstField("SHOW TABLE STATUS FROM `{$database}` WHERE `Name`=\"{$datatable}\";");

if (! $table ) {
  
  header("HTTP/1.0 404 Not Found");
  echo json_encode(['error' => 'Database/table not exist']);
  exit;
}

// Try save in database 
DB::insert($datatable, $data);

if (DB::insertId()) {
  echo json_encode(['success' => true, 'message' => ' Address saved successfully', 'id' => DB::insertId()]);
  exit;
}

header('X-Error-Message: Error on save in Database', true, 500);
echo json_encode(['error' => 'Error on save in Database']);
exit;

