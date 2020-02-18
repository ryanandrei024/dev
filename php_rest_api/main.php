<?php 
require_once("./config/Database.php");
require_once("./models/Post.php");

$post = new Post($pdo);
$req = array();
if(isset($_REQUEST['request'])) {
	$req = explode('/', rtrim($_REQUEST['request'], "/"));
}else {
	$req = array("errorcatcher"=>$error);
}

switch ($_SERVER['REQUEST_METHOD']) {
	case 'POST':
			switch ($req[0]) {
				case 'read':
					echo json_encode($post->read(), JSON_PRETTY_PRINT);
					break;
				case 'read_single':
					$id = $_GET['id'] ? $_GET['id'] : die();
					$row = $post->read_single($id);
					echo json_encode($row, JSON_PRETTY_PRINT);
					break;
				case 'create':
					$dt=json_decode(file_get_contents("php://input"));
					echo json_encode($post->create_post($dt));
					break;
				case 'update':
					$id = $_GET['id'] ? $_GET['id'] : die();
					$dt=json_decode(file_get_contents("php://input"));
					echo json_encode($post->update_post($dt, $id));
					break;
				case 'delete':
					$id = $_GET['id'] ? $_GET['id'] : die();
					echo json_encode($post->delete_post($id));
					break;
				case 'changepword':
					# code...
					break;

				default:
					http_response_code(400);
					echo json_encode(
			 			array(
			 				"status"=>"failed",
 							"message"=>"Bad Request. Kindly contact the developers fo the list of endpoints"
			 				)
						);
					break;
			}
		break;
	
	default:
		http_response_code(401);
		echo json_encode(
 			array(
 				"status"=>"failed",
 				"message"=>"Unauthorize User"
 				)
			);
		break;
}















 ?>