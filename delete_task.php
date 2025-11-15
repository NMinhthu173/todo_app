<?php
require 'config.php';
if(!isset($_SESSION['user_id'])) header('Location: login.php');

$id=$_GET['id']??null;
if($id){
$stmt=$pdo->prepare("DELETE FROM tasks WHERE id=? AND user_id=?");
$stmt->execute([$id,$_SESSION['user_id']]);
}
header('Location: dashboard.php'); exit();
?>
