<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Xếp loại học lực</title>
</head>
<body>
    <h2>Tính điểm trung bình & Xếp loại</h2>
    
    <form method="post">
        Toán: <input type="number" step="0.1" name="toan" required><br><br>
        Văn: &nbsp;&nbsp;<input type="number" step="0.1" name="van" required><br><br>
        Anh: &nbsp;&nbsp;<input type="number" step="0.1" name="anh" required><br><br>
        <button type="submit" name="tinh">Xem kết quả</button>
    </form>

    <hr>

    <?php
    if (isset($_POST['tinh'])) {
        $toan = $_POST['toan'];
        $van = $_POST['van'];
        $anh = $_POST['anh'];

        $dtb = ($toan + $van + $anh) / 3;
        
        $dtb = round($dtb, 1);

        if ($dtb >= 8.0) {
            $loai = "Giỏi ";
            $mau = "green";
        } elseif ($dtb >= 6.5) {
            $loai = "Khá ";
            $mau = "blue";
        } elseif ($dtb >= 5.0) {
            $loai = "Trung bình ";
            $mau = "orange";
        } else {
            $loai = "Yếu ";
            $mau = "red";
        }

        echo "<h3>Điểm trung bình: $dtb</h3>";
        echo "<h3>Xếp loại: <span style='color:$mau'>$loai</span></h3>";
    }
    ?>
</body>
</html>