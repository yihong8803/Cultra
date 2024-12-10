<?php
    session_start();
    include ("database.php");

    if(isset($_SESSION['oID'])) {
         $oID = $_SESSION['oID'];
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Propose Event</title>
    <!-- Links to google font icon -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <link rel="stylesheet" href="./assets/CSS/propose_event.css">
</head>
<body>
    <div class="container">
        <div class="logo-section">
            <img id="logo" src="./assets/images/CULTRA_logo.png" alt="Cultra Logo">
            <h1>CULTRA</h1>
        </div>
        <h1>Propose an Event</h1>
        <form id="propose-event-form" method="post" enctype="multipart/form-data" >
            <div class="section">
                <h2>Event Details</h2>
                <div class="form-group">
                    <label for="event-title">Event Title</label>
                    <input type="text" id="event-title" name="event-title" required>
                </div>
                <div class="date-time-container">
                    <div class="form-group">
                        <label for="event-date">Event Date</label>
                        <input type="date" id="event-date" name="event-date" required>
                    </div>
                    <div class="form-group">
                        <label for="event-time">Event Time</label>
                        <input type="text" id="event-time" name="event-time" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="event-venue">Event Venue</label>
                    <input type="text" id="event-venue" name="event-venue" required>
                </div>
            </div>

            <div class="section">
                <h2>Organizer Details</h2>
                <div class="form-group">
                    <label for="my_image">Event Banner</label>
                    <input type="file" id="my_image" class="uploadImg" name="my_image" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label for="my_profile">Event Profile (Logo)</label>
                    <input type="file" id="my_profile" class="uploadImg" name="my_profile" accept="image/*" required>
                </div>
            </div>

            <div class="section">
                <h2>Event Description</h2>
                <div class="form-group">
                    <label for="event-description">Description</label>
                    <textarea id="event-description" name="event-description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="event-requirements">Requirements</label>
                    <textarea id="event-requirements" name="event-requirements" required></textarea>
                </div>
                <div class="form-group">
                    <label for="event-about">About</label>
                    <textarea id="event-about" name="event-about" required></textarea>
                </div>
            </div>
            
            <button type="submit" name="btnSubmit">Submit</button>
        </form>
    </div>

    <script src="script.js"></script>
    <?php
        if(isset($_POST['btnSubmit'])){
            $imgName = $_FILES['my_profile']['name'];
            $imgtmp_name = $_FILES['my_profile']['tmp_name'];
            $imgerror = $_FILES['my_profile']['error'];
            if($imgerror == 0){
                $img_ex = pathinfo($imgName, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);
                $imgallowed_exs = array('jpg','jpeg','png');
                if(in_array($img_ex_lc, $imgallowed_exs)){
                    $new_img_name = uniqid("image-", true). '.' . $img_ex_lc;
                    $img_upload_path = 'images/event/profile/'. $new_img_name;
                    move_uploaded_file($imgtmp_name, $img_upload_path);
                    $eProfile= $new_img_name;

                }
            }

            
            $imgName = $_FILES['my_image']['name'];
            $imgtmp_name = $_FILES['my_image']['tmp_name'];
            $imgerror = $_FILES['my_image']['error'];
            if($imgerror == 0){
                $img_ex = pathinfo($imgName, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);
                $imgallowed_exs = array('jpg','jpeg','png');
                if(in_array($img_ex_lc, $imgallowed_exs)){
                    $new_img_name = uniqid("image-", true). '.' . $img_ex_lc;
                    $img_upload_path = 'images/event/banner/'. $new_img_name;
                    move_uploaded_file($imgtmp_name, $img_upload_path);
                    $eBanner= $new_img_name;
                }
            }


            $eTitle = filter_input(INPUT_POST, "event-title", FILTER_SANITIZE_SPECIAL_CHARS);
            $eDate = filter_input(INPUT_POST, "event-date", FILTER_SANITIZE_SPECIAL_CHARS);
            $eTime = filter_input(INPUT_POST, "event-time", FILTER_SANITIZE_SPECIAL_CHARS);
            $eVenue = filter_input(INPUT_POST, "event-venue", FILTER_SANITIZE_SPECIAL_CHARS);
            $eDesc = filter_input(INPUT_POST, "event-description", FILTER_SANITIZE_SPECIAL_CHARS);
            $eReq = filter_input(INPUT_POST, "event-requirements", FILTER_SANITIZE_SPECIAL_CHARS);
            $eAbout = filter_input(INPUT_POST, "event-about", FILTER_SANITIZE_SPECIAL_CHARS);

            //SQL Query to insert all data into table course
            $sql = "INSERT INTO event (eTitle, eDate, eTime, eVenue, eOrganizer, eBanner, eProfile, eDesc, eReq, eAbout)
                VALUES ('$eTitle', '$eDate', '$eTime', '$eVenue', '$oID', '$eBanner', '$eProfile', '$eDesc', '$eReq', '$eAbout')";

            if (mysqli_query($conn, $sql)) {
                echo "Record inserted successfully!";
            } else {
                echo '<script type="text/javascript">alert("' . $sql . '")</script>';  
                echo '<script type="text/javascript">alert("' . mysqli_error($conn) . '")</script>';  
                
            }
            mysqli_close($conn);
            echo '<script>alert("The Information is saved")</script>'; 
            echo "<script>location.href = 'organizer_home.php';</script>";
        }
    ?>
</body>
</html>
