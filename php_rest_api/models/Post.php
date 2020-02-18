<?php 

class Post {
	//DB Stuff
	private $table = 'posts';
	private $conn;
	private $data = array();


	public function __construct($db) {
		$this->conn = $db;
	}

	public function read() {
		$sql = 'SELECT * FROM posts ORDER BY id DESC';

		if($result = $this->conn->query($sql)){
			if($result->rowCount()>0){
				while($res =  $result->fetch()){
					array_push($this->data, $res);
				}
				http_response_code(200);
			}
		}
		return $this->data;
	}

	public function read_single($id) {
		$sql = 'SELECT * FROM posts WHERE id=:id LIMIT 0,1';
		$stmt = $this->conn->prepare($sql);
		$stmt->bindValue(":id", $id);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;
	}
	public function create_post($indata) {
		$fields = [
			'title'=>$indata->title,
			'body'=>$indata->body,
			'author'=>$indata->author,
			'category_id'=>$indata->category_id
		];

		$tableColumnsNames = implode(', ', array_keys($fields));
		$tableColumnsValues = implode(', :', array_keys($fields));

		$sql = "INSERT INTO posts($tableColumnsNames) VALUES (:".$tableColumnsValues.")";
		$stmt = $this->conn->prepare($sql);

		foreach ($fields as $key => $value) {
			$stmt->bindValue(':'.$key, $value);
		}
		$stmtExecute = $stmt->execute();

		return array ("status"=>"Create Success!!", "timestamp"=>date_create());
	}

	public function update_post($indata, $id) {
		$fields = [
			'title'=>$indata->title,
			'body'=>$indata->body,
			'author'=>$indata->author,
			'category_id'=>$indata->category_id
		];

		$sql = "";
		$st = "";
		$counter = 1;
		$totalFields = count($fields);
		foreach ($fields as $key => $value) {
			if ($counter == $totalFields) {
				$set = "$key =:".$key;
				$st = $st.$set;
			}else {
				$set = "$key =:".$key.", ";
				$st = $st.$set;
				$counter++;

			}
		}
		$sql.= "UPDATE posts SET ".$st;
		$sql.= " WHERE id=".$id;
		$stmt = $this->conn->prepare($sql);
		foreach ($fields as $key => $value) {
			$stmt->bindValue(":".$key, $value);
		}
		$stmt->execute();

		return array ("status"=>"Update Success!!", "payload"=>$fields, "timestamp"=>date_create());
	}

	public function delete_post($id){
		$sql = "DELETE FROM posts WHERE id=:id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindValue(":id", $id);
		$stmt->execute();

		return array ("status"=>"Delete Success!!", "id"=> $id, "timestamp"=>date_create());
	}
}













 ?>