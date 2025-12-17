<!DOCTYPE html>
<html>
<body>
<form method="post">
    Nhập tên: <input type="text" name="ten">
    <input type="submit" value="Gửi">
</form>

<?php
if (isset($_POST["ten"])) {
    $ten = $_POST["ten"];
    echo "Xin chào $ten";
}
?>
</body>
</html>