<?php
    require_once "connect.php";//ดึงข้อมูล

    if (!isset($_GET['music_type_id'])){
        $_GET['music_type_id'] = '0';
    }

    // ตรวจสอบว่ามีการเลือกประเภทดนตรีจากฟอร์มหรือไม่
    if (isset($_GET['music_type_id']) && !empty($_GET['music_type_id'])) {
    // รับค่าประเภทดนตรีที่เลือกจากฟอร์ม
    $selectedCategory = $_GET['music_type_id'];

    // คำสั่ง SQL สำหรับคัดกรองข้อมูลตามประเภทดนตรีที่เลือก
    $sql = "SELECT musicband.band_id, musicband.band_name, musicband.image_url, musicband.about, musictype.music_type_name
            FROM musicband
            LEFT JOIN musicType ON musicband.music_type_id = musictype.music_type_id
            WHERE musictype.music_type_id = $selectedCategory
            ORDER BY musicband.like_count DESC"; // เรียงจากมากไปน้อย

    // ประมวลผลคำสั่ง SQL
    $result = $conn->query($sql);

    if ($result === false) {
        die("Query failed: " . $conn->error);
    }
} else {
    // หากไม่มีการเลือกประเภทดนตรี ให้แสดงทุกประเภท
    $sql = "SELECT * FROM musicband ORDER BY like_count DESC"; // เรียงจากมากไปน้อย

    // ประมวลผลคำสั่ง SQL
    $result = $conn->query($sql);

    if ($result === false) {
        die("Query failed: " . $conn->error);
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="styles.css">
    </head>

<body class="background">
        
    <div class="container-fluid" style="margin-top: 40px; ">
        <div class="col-12" style="display: flex; justify-content: end;">
            <h1 class="text-light">CS MSU Music Award 2023</h1>
        </div>
    </div> 

        

<div class="container" style="margin-top: 40px;">   

        <div class="row">
            <div class="col-12" style="display: flex; justify-content: start;">
                <h3 class="text-light">วงดนตรี</h3>
            </div>
        </div>

        <div class="row" style="justify-content: first baseline;">
            <div class="col-3" style="display: flow-root; justify-content: end;">
                <form action="index.php" method="get">
                    <select class="form-select" aria-label="Default select example" name="music_type_id" onchange="this.form.submit()">
                        <option selected="" value="">วงดนตรีทั้งหมด</option>
                        <option value="1" <?php echo ($_GET['music_type_id'] == 1) ? 'selected' : ''; ?>>ไทย</option>
                        <option value="2" <?php echo ($_GET['music_type_id'] == 2) ? 'selected' : ''; ?>>International</option>
                        <option value="3" <?php echo ($_GET['music_type_id'] == 3) ? 'selected' : ''; ?>>TPOP</option>
                        <option value="4" <?php echo ($_GET['music_type_id'] == 4) ? 'selected' : ''; ?>>KPOP</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="row">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo " <div class='col-4' style='display: flex; justify-content: center; margin-top: 30px; '>";
                    echo "<a href='show.php?id=" . $row['band_id'] . "' class='zoom'>";
                        echo "<img class='rounded' src='" . $row['image_url'] . "' style='width: 96%;
                        height: auto;
                        object-fit: cover;
                        aspect-ratio: 1/1;
                        border: none;'>";
                    echo "</a>";
                echo "</div>";
            }?>
        </div>
</div>  
        <div class="end-message">
              <p>พัฒนาโดย MR.THIPPARAT KHOMPARSERT | 65011212057 | CSMSU</p>
        </div> 
        
</body>

</html>