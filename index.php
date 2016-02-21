<?php

define('DB_DATABASE', 'dotinstall_db');
define('DB_USERNAME', 'dbuser');
define('DB_PASSWORD', 'test');
define('PDO_DSN', 'mysql:dbhost=localhost;dbname=' . DB_DATABASE);

class User{
  // public $id;
  // public $name;
  // public $score;
  public function show(){
    echo "$this->name($this->score)";
  }
}
try{
  // connection
  $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  /* insert
  $db->exec("insert into users(name, score) values ('taguchi', 55)");
  echo "user added";
  */

  /* explanation
  exec(): 結果を返さない、安全なsql
  query(): 結果を返す、安全、何回も実行されないsql
  prepare(): 結果を返す、安全対策が必要、複数回実行されるsql
  */

  // $stmt = $db->prepare("insert into users(name, score) values(?,?)");
  // $stmt->execute(['taguchi', 44]);
  // $stmt = $db->prepare("insert into users(name, score) values(:name,:score)");
  // $stmt->execute([":name" => 'fkoji', ":score" => 44]);
  // echo "inserted: " . $db->lastInsertId();

  /* bindValue: 値をbind
  $stmt = $db->prepare("insert into users(name, score) values(?,?)");
  $name = "taguchi";
  $stmt->bindValue(1, $name, PDO::PARAM_STR);
  $score = 23;
  $stmt->bindValue(2, $score, PDO::PARAM_INT);
  $stmt->execute();
  $score = 44;
  $stmt->bindValue(2, $score, PDO::PARAM_INT);
  $stmt->execute();
  */
  /*bindParam
  $stmt = $db->prepare("insert into users(name, score) values(?,?)");
  $name = "taguchi";
  $stmt->bindValue(1, $name, PDO::PARAM_STR);
  $stmt->bindParam(2, $score, PDO::PARAM_INT);
  $score = 44;
  $stmt->execute();
  $score = 34;
  $stmt->execute();
  $score = 24;
  $stmt->execute();
  */

  // select all
  //$stmt = $db->query("select * from users");
  // search
  // $stmt = $db->prepare("select score from users where score > ?");
  // $stmt->execute([20]);
  // $stmt = $db->prepare("select name from users where name like ?");
  // $stmt->execute(['%t%']);
  /*
  $stmt = $db->prepare("select name from users order by score desc limit ?");
  $stmt->bindValue(1,1, PDO::PARAM_INT);
  $stmt->execute();

  $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach($users as $user){
    var_dump($user);
  }
  echo $stmt->rowCount() . "records found";
  */
  $stmt = $db->query("select * from users");
  $users = $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
  foreach($users as $user){
    $user->show();
  }

  /* update
  $stmt = $db->prepare("update users set score = :score where name = :name");
  $stmt->execute([
    ':score' => 100,
    ':name' => 'taguchi'
  ]);
  echo 'row updated: ' . $stmt->rowCount();
  */

  /* delete
  $stmt = $db->prepare("delete from users where name = :name");
  $stmt->execute([
    ':name' => "kazuki"
  ]);
  echo 'row deleted: ' . $stmt->rowCount();
  */

  //transliterator_create_inverse
  $db->beginTransaction();
  $db->exec("update users set score = score - 10 where name = 'taguchi'");
  $db->exec("update users set score = score + 10 where name = 'kazuki'");
  $db->commit();

  // disconnect
  $db = null;
}catch(PDOException $e){
  $db->rollback();
  echo $e->getMessage();
  exit;
}
 ?>
