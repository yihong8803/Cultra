<?php
    session_start();
    include ("database.php");

    // Assuming pID is passed as a query parameter
    if(isset($_SESSION['pID'])) {
        $pID = $_SESSION['pID'];
    }
        // Default pID for testing, remove or handle this properly in production

    // Fetch performer details
    $sql = "SELECT * FROM performer WHERE pID = $pID";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);  
        // Access individual columns directly
        $pName = $row['pName'];
        $pProfile = $row['pProfile'];
        $pPosition = $row['pPosition'];
        $pDesc = $row['pDesc'];
        $pTalents = $row['pTalent'];
        $pBanner = $row['pBanner'];
    } else {
        echo "No records found";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Detail</title>
    <link rel="stylesheet" href="profileDetail.css">
</head>
<body>
    <div class="profile-detail-container">
        <div class="profile-header">
            <div class="uploadCover-box">
                <form action="" method="post" name="imgUpload" id="imgUpload" enctype="multipart/form-data">
                <input type="file" id="my_image" class="uploadImg" name="my_image" accept="image/jpeg, image/png" style="display: none;" onchange="handleImageSelect(event)">
                    <label for="my_image">
                        <img src="images/performer/banner/<?= htmlspecialchars($pBanner) ?>" id="profile-banner" alt="Profile Banner" class="profile-banner">
                    </label>
                    <input type="submit" id="banner_submit_button" name="banner_submit_button" style="display: none;">
                </form>
            </div>
            <div class="profile-logo-title">
            <form action="" method="post" name="imgUpload" id="imgUpload" enctype="multipart/form-data">
                <input type="file" id="my_profile" class="uploadImg" name="my_profile" accept="image/jpeg, image/png" style="display: none;" onchange="handleProfileImageSelect(event)">
                    <label for="my_profile">
                        <img src="images/performer/profile/<?= htmlspecialchars($pProfile) ?>" alt="Profile Photo" class="profile-logo">
                    </label>
                <input type="submit" id="profile_submit_button" name="profile_submit_button" style="display: none;">
            </form>
                <h1 class="profile-name"><?= htmlspecialchars($pName) ?></h1>
            </div>
            <button class="edit-btn">
                <img src="assets/edit.png" alt="Edit" class="edit-icon">
            </button>
        </div>
        <div class="profile-meta">
            <h2>Position</h2>
            <p class="profile-position"><?= htmlspecialchars($pPosition) ?></p>
            <h2>Profile Description</h2>
            <p class="profile-description"><?= htmlspecialchars($pDesc) ?></p>
            <h2>Talents</h2>
            <ul class="profile-talents">
                <?php foreach (explode('.', $pTalents) as $talent): ?>
                    <li><?= htmlspecialchars($talent) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <hr>
        <div class="edit-form-container" id="edit-form">
            <h2>Edit Profile Details</h2>
            <form action="#" method="post">
                <label for="edit-name">Name:</label>
                <input type="text" id="edit-name" name="edit-name" value="<?= htmlspecialchars($pName) ?>">
                
                <label for="edit-position">Position:</label>
                <input type="text" id="edit-position" name="edit-position" value="<?= htmlspecialchars($pPosition) ?>">
                
                <label for="edit-description">Description:</label>
                <textarea id="edit-description" name="edit-description"><?= htmlspecialchars($pDesc) ?></textarea>
                
                <label for="edit-talents">Talents: (Seperate by full stop '.')</label>
                <textarea id="edit-talents" name="edit-talents"><?= htmlspecialchars($pTalents) ?></textarea>
                
                <button type="submit" class="btn save-btn" name="btnEdit">Save</button>
                <button type="button" class="btn cancel-btn" onclick="cancelEdit()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        
        function handleImageSelect(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('profile-banner');
                    img.src = e.target.result;
                    img.style.display = 'block';
                }
                reader.readAsDataURL(file);
                document.getElementById('banner_submit_button').click();
            }
        }

        function handleProfileImageSelect(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('profile-banner');
                    img.src = e.target.result;
                    img.style.display = 'block';
                }
                reader.readAsDataURL(file);
                document.getElementById('profile_submit_button').click();
            }
        }

        document.querySelector('.edit-btn').addEventListener('click', function() {
            document.getElementById('edit-form').style.display = 'block';
        });

        function cancelEdit() {
            document.getElementById('edit-form').style.display = 'none';
        }
    </script>

    <?php
    if (isset($_POST['banner_submit_button'])) {
        $imgName = $_FILES['my_image']['name'];
        $imgtmp_name = $_FILES['my_image']['tmp_name'];
        $imgerror = $_FILES['my_image']['error'];
        if($imgerror == 0){
            $img_ex = pathinfo($imgName, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
            $imgallowed_exs = array('jpg','jpeg','png');
            if(in_array($img_ex_lc, $imgallowed_exs)){
                $new_img_name = uniqid("image-", true). '.' . $img_ex_lc;
                $img_upload_path = 'images/performer/banner/'. $new_img_name;
                move_uploaded_file($imgtmp_name, $img_upload_path);

                //SQL Query to database
                $imgName= $new_img_name;
                $sql = "UPDATE performer 
                    SET pBanner = '$imgName'
                    WHERE pID = '$pID'";
                mysqli_query($conn,$sql);

                echo "<script>location.href = 'profileDetail.php?pID=$pID';</script>";
            }else{
                echo '<script>alert("You can\'t upload files of this type!")</script>';        
            }
        }
    }elseif (isset($_POST['profile_submit_button'])) {   
        $imgName = $_FILES['my_profile']['name'];
        $imgtmp_name = $_FILES['my_profile']['tmp_name'];
        $imgerror = $_FILES['my_profile']['error'];
        if($imgerror == 0){
            $img_ex = pathinfo($imgName, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
            $imgallowed_exs = array('jpg','jpeg','png');
            if(in_array($img_ex_lc, $imgallowed_exs)){
                $new_img_name = uniqid("image-", true). '.' . $img_ex_lc;
                $img_upload_path = 'images/performer/profile/'. $new_img_name;
                move_uploaded_file($imgtmp_name, $img_upload_path);

                //SQL Query to database
                $imgName= $new_img_name;
                $sql = "UPDATE performer 
                    SET pProfile = '$imgName'
                    WHERE pID = '$pID'";
                mysqli_query($conn,$sql);

                echo "<script>location.href = 'profileDetail.php?pID=$pID';</script>";
            }else{
                echo '<script>alert("You can\'t upload files of this type!")</script>';        
            }
        }
    }

    if (isset($_POST['btnEdit'])) {
        $pName = filter_input(INPUT_POST, "edit-name", FILTER_SANITIZE_SPECIAL_CHARS);
        $pPosition = filter_input(INPUT_POST, "edit-position", FILTER_SANITIZE_SPECIAL_CHARS);
        $pDesc = filter_input(INPUT_POST, "edit-description", FILTER_SANITIZE_SPECIAL_CHARS);
        $pTalents = filter_input(INPUT_POST, "edit-talents", FILTER_SANITIZE_SPECIAL_CHARS);

        $sql = "UPDATE performer SET 
            pName = '$pName',
            pPosition = '$pPosition',
            pDesc = '$pDesc',
            pTalent = '$pTalents'
            WHERE pID = '$pID'";

        mysqli_query($conn, $sql);
        mysqli_close($conn);
        echo '<script>alert("The Information is saved")</script>'; 
        echo "<script>location.href = 'profileDetail.php?pID=$pID';</script>";
    }
    ?>
</body>
</html>
