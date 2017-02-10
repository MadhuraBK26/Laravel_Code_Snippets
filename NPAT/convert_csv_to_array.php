<?php

$db_host='192.168.2.77';
$con=mysqli_connect($db_host,"web","websa","npat");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }


// =====================================================================

$file = file_get_contents("Q2-list.csv");
$data_array = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $file));
$previous_project_id = 0;

//mysqli_query($con, "TRUNCATE TABLE project_manager");
foreach ($data_array as $key => $value) {
	if($value[0] == '') continue;
	$project_name = $value[0];
	$manager_name = $value[1];
	$user_name = $value[2];
	
	$project_id = mysqli_query($con, "SELECT id FROM project WHERE name LIKE '%$project_name%' limit 1;");
	$manager_id = mysqli_query($con, "SELECT id FROM users WHERE name LIKE '%$manager_name%' limit 1;");
	$user_id = mysqli_query($con, "SELECT id FROM users WHERE name LIKE '%$user_name%' limit 1;");

	$project_result = mysqli_fetch_array($project_id, MYSQLI_ASSOC);
	$manager_result = mysqli_fetch_array($manager_id, MYSQLI_ASSOC);
	$user_result = mysqli_fetch_array($user_id, MYSQLI_ASSOC);

	// print_r($user_result); exit;

	$id = $key++;
	$project_id = $project_result['id'];
	$manager_id = $manager_result['id'];
	$user_id = $user_result['id'];
	$status_id = 1;
	$start_date = date("Y-m-d H:i:s");
	$end_date = date("Y-m-d H:i:s");
	$percentage_involved = 70;
	$created_at = date("Y-m-d H:i:s");
	$updated_at = date("Y-m-d H:i:s");

	

	mysqli_query($con, "INSERT INTO project_manager (
		id,
		project_id,
		manager_id,
		people_id,
		status,
		start_date,
		end_date,
		percentage_involved,
		created_at,
		updated_at,
		deleted_at
		) VALUES (NULL, '$project_id', '$manager_id', '$user_id', '$status_id', '$start_date', '$end_date', '$percentage_involved', 
		'$created_at', '$updated_at', NULL)");
}

mysqli_close($con);

?>
