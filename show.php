<?php 
    require_once "connect.php";

    // ตรวจสอบว่ามีค่า ID ที่ส่งมาจากหน้า home.php หรือไม่
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $bandId = $_GET['id'];

    // คำสั่ง SQL เพื่อดึงข้อมูลของวงดนตรีตาม ID
    $sql = "SELECT musicband.band_id, musicband.band_name, musicband.image_url, musicband.about, musictype.music_type_name, musicband.like_count
            FROM musicband
            LEFT JOIN musictype ON musicband.music_type_id = musictype.music_type_id
            WHERE musicband.band_id = $bandId";


    
     // ประมวลผลคำสั่ง SQL
     $result = $conn->query($sql);

     if ($result === false) {
         die("Query failed: " . $conn->error);
     }
 
     // ตรวจสอบว่ามีข้อมูลของวงดนตรีที่ระบุหรือไม่
     if ($result->num_rows > 0) {
         $bandData = $result->fetch_assoc();//วนลูปไปจนเจอ$result
     } else {
         die("Band not found.");
     }
 } else {
     die("Invalid request.");
 }
 
 // ตรวจสอบการกดถูกใจ
 if (isset($_POST['like_button'])) {
     // อัปเดตค่า like_count ในฐานข้อมูล
     $sqlUpdateLikes = "UPDATE musicband SET like_count = like_count + 1 WHERE band_id = $bandId";
 
     if ($conn->query($sqlUpdateLikes) === false) {
         die("Update failed: " . $conn->error);
     }
 
     // รีโหลดหน้าเว็บหลังจากการอัปเดตค่า
     header("Location: show.php?id=$bandId");
     exit();
 }
 
 // ดึงจำนวนการกดถูกใจ
 $sqlLikes = "SELECT like_count FROM musicband WHERE band_id = $bandId";
 $resultLikes = $conn->query($sqlLikes);
 
 if ($resultLikes === false) {
     die("Query failed: " . $conn->error);
 }
 
 $likeCount = 0;
 
//  ดึงข้อมูลกดไลน์
 if ($resultLikes->num_rows > 0) {
     $rowLikes = $resultLikes->fetch_assoc();
     $likeCount = $rowLikes['like_count'];
 }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Page Title</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <script src='main.js'></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="show.css">
    <style>
            .background{
                background-image: url('img/02.jpg'); /* รูปภาพพื้นหลัง */
                height: 90vh;
                background-repeat: no-repeat;
                /* background-position: center center; */
                background-size: cover;
                background-attachment: fixed;
                
            }

    </style>
</head>
<body class="background">
    <div class="container-fluid" style="margin-top: 40px;">
        <div class="col-12" style="display: flex; justify-content: end;">
            <h1 class="text-light">CS MSU Music Award 2023</h1>
        </div>
    </div>
     
    <div class="container-fluid">
        <a href="index.php" style="font-size: 25px;"> หน้าแรก </a>
        <h3> / <?php echo $bandData['band_name']?></h3>
        <div class="show">
                <?php if (isset($bandData)): ?>
                    <div class="card-details">
                        <img src="<?php echo $bandData['image_url']; ?>" class="card-img" alt="Band Image">
                        <h2><?php echo $bandData['band_name'], " - ", $bandData['music_type_name']; ?></h2>
                        <p><?php echo $bandData['about']; ?></p>

                    <!-- ปุ่มถูกใจ -->  
                    <div class="like-button">
                            <form method="post" action="show.php?id=<?php echo $bandId; ?>">
                                <button type="submit" name="like_button" onclick="return confirm('ยืนยันการโหวต <?php echo $bandData['band_name'];?>')" style="background: none; border: none; ">
                                    <i class="bi bi-heart-fill" style="color: red; font-size: 16pt;"></i> (<?php echo $likeCount; ?>)
                                </button>
                            </form>
                    </div>
                    </div>
                <?php else: ?>
                    <p>ไม่พบข้อมูลวงดนตรี</p>
                <?php endif; ?>
            </div>
    </div>
</body>
</html>