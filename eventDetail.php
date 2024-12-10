<?php
    session_start();
    include ("database.php");
        
    //Retrieve data from previous page
    // if(isset($_GET['id'])) {
    //     $_SESSION['eId'] = $_GET['id'];
    // }


    if(isset($_SESSION['eID'])) {
        $eID = $_SESSION['eID'];
    }else{
        $eID = 1;
    }


    // Store event details (assuming they are the same for all rows)
    $sql = "SELECT * FROM event
            WHERE eID = $eID";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);  
        // Access individual columns directly
        $eTitle = $row['eTitle'];
        $eDate = $row['eDate'];
        $eTime = $row['eTime'];
        $eVenue = $row['eVenue'];
        //$eOrganizer = trim($row['eOrganizer']);
        $eOrganizer = $row['eOrganizer'];
        $eBanner = $row['eBanner'];
        $eProfile = $row['eProfile'];
        $eDesc = $row['eDesc'];
        $eReqArray = $row['eReq'];
        $eAbout = $row['eAbout'];
        //  echo '<script type="text/javascript">alert("' . $eVenue . '")</script>';
    }


// Assuming $eId is already defined or sanitized
$sql = "SELECT e.*, p.*
        FROM event e
        LEFT JOIN eventperformer ep ON e.eID = ep.eID
        LEFT JOIN performer p ON ep.pID = p.pID
        WHERE e.eID = $eID AND ep.status='approve'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Initialize variables to store event details and performers
    $performers = [];
    $performerCount = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        // Store performer details
        $performers[] = [
            'pID' => $row['pID'],
            'pName' => $row['pName'],
            'pProfile' => $row['pProfile'],
            'pPosition' => $row['pPosition'],
            'pDesc' => $row['pDesc'],
            'pLabel' => $row['pLabel'],
            // Add other performer details as needed
        ];
        if ($row['pID']){
            $performerCount++;
        }

    }

    foreach ($performers as $performer) {
        $pID = $performer['pID'];
        $pName = $performer['pName'];
        //echo "<script>alert('$pID - $pName');</script>";
    }

        } else {
            
        }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Detail</title>
    <link rel="stylesheet" href="eventDetail.css">
    
</head>
<body>
    <!--Pass boolean value from delete button to php-->
    <form id="confirmationForm" method="post" action="">
        <input type="hidden" id="confirmationValue" name="confirmationValue" value="">
        <input type="hidden" id="deleteID" name="deleteID" value="">
    </form>


    <div class="event-detail-container">
    <button style="
    background-color: #04AA6D; /* Main theme color */
    color: white;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    position: fixed;
    right:50px;
    margin: 4px 2px;
    cursor: pointer;
    border: none;
    border-radius: 5px;"
    onclick="location.href='h_status.php'">Status</button>
        <div class="event-header">
            <div class="uploadCover-box">
                <form action="" method="post" name="imgUpload" id="imgUpload" enctype="multipart/form-data">
                <input type="file" id="my_image" class="uploadImg" name="my_image" accept="image/jpeg, image/png" style="display: none;" onchange="handleImageSelect(event)">
                <label for="my_image">
                    <img src="images/event/banner/<?= htmlspecialchars($eBanner) ?>" id="event-banner" alt="Event Banner" class="event-banner">
                </label>
                <input type="submit" id="banner_submit_button" name="banner_submit_button" style="display: none;">
              </form>
            </div>
            <div class="profile-logo-title">
            <form action="" method="post" name="imgUpload" id="imgUpload" enctype="multipart/form-data">
                <input type="file" id="my_profile" class="uploadImg" name="my_profile" accept="image/jpeg, image/png" style="display: none;" onchange="handleProfileImageSelect(event)">
                    <label for="my_profile">
                        <img src="images/event/profile/<?= htmlspecialchars($eProfile) ?>" alt="Profile Photo" class="profile-logo">
                    </label>
                <input type="submit" id="profile_submit_button" name="profile_submit_button" style="display: none;">
            </form>
                <h1 class="event-title"><?= htmlspecialchars($eTitle) ?></h1>
            </div>
            <button class="edit-btn">
                <img src="assets/edit.png" alt="Edit" class="edit-icon">
            </button>
        </div>
        <div class="event-meta">
            <span class="event-date" >Date: <?= htmlspecialchars($eDate) ?></span>
            <span class="event-time">Time: <?= htmlspecialchars($eTime) ?></span>
            <span class="event-venue">Venue: <?= htmlspecialchars($eVenue) ?></span>
            <span class="event-organizer">Organized by: <?= htmlspecialchars($eOrganizer) ?></span>
        </div>
        <hr>
        <h2>Event Description</h2>
        <p class="event-description"><?= htmlspecialchars($eDesc) ?></h2>
        <h2>Requirement</h2>
        <ul class="event-requirements">
            <?php foreach (explode('.', $eReqArray) as $eReq): ?>
                <li><?= htmlspecialchars($eReq) ?></li>
            <?php endforeach; ?>
        </ul>
        <h2>About the Job</h2>
        <p class="event-about"><?= htmlspecialchars($eAbout) ?></p>
        <!-- <div class="action-buttons">
            <button class="btn accept-btn">Accept</button>
            <button class="btn reject-btn">Reject</button>
            <button class="btn apply-btn">Apply</button>
        </div> -->

        <!-- Hire list -->
        <div class="hire-list-container">
            <h2>Hire List</h2>
            <ul class="hire-list" id="list1">
                <li class="hire-item" id="list2">
                    <img src="download1.jpg" alt="Profile Photo" class="profile-photo">
                    <span class="hire-name">John Doe</span>
                    <span class="hire-position">Position: Performer</span>
                    <span class="hire-role">Vocal</span>
                    <div class="remove-container">
                        <img src="trash.png" alt="Remove" class="trash-icon">
                        <button class="remove-btn">Remove</button>
                    </div>
                </li>

                

            </ul>
        </div>

    </div>
    <div class="edit-form-container" id="edit-form">
        <h2>Edit Event Details</h2>
        <form action="#" method="post">
            <label for="edit-title">Event Title:</label>
            <input type="text" id="edit-title" name="edit-title" value="<?= htmlspecialchars($eTitle) ?>">
            
            <label for="edit-date">Date:</label>
            <input type="date" id="edit-date" name="edit-date" value="<?= htmlspecialchars($eDate) ?>">
            
            <label for="edit-time">Time:</label>
            <input type="text" id="edit-time" name="edit-time" value="<?= htmlspecialchars($eTime) ?>">
            
            <label for="edit-venue">Venue:</label>
            <input type="text" id="edit-venue" name="edit-venue" value="<?= htmlspecialchars($eVenue) ?>">
            
            <label for="edit-organizer">Organizer:</label>
            <input type="text" id="edit-organizer" name="edit-organizer" value="<?= htmlspecialchars($eOrganizer) ?>">
            
            <label for="edit-description">Description:</label>
            <textarea id="edit-description" name="edit-description"><?= htmlspecialchars($eDesc) ?></textarea>
            
            <label for="edit-requirements">Requirements:</label>
            <textarea id="edit-requirements" name="edit-requirements"><?= htmlspecialchars($eReqArray) ?></textarea>
            
            <label for="edit-about">About the Job:</label>
            <textarea id="edit-about" name="edit-about"><?= htmlspecialchars($eAbout) ?></textarea>
            
            <button type="submit" class="btn save-btn" onclick="saveChanges()" name="btnEdit">Save</button>
            <button type="button" class="btn cancel-btn" onclick="cancelEdit()">Cancel</button>
        </form>
        
    </div>
    

    
    <script>
    //Change Image
    
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



    var hireListContainer = document.querySelector('.hire-list');
    // Add event listeners after updating innerHTML
    function removeContent(btn,pID) {
    if (confirm("Are you sure you want to remove this item?")) {
        hireListContainer.removeChild(btn.parentNode.parentNode);
        // Get the form and hidden input field
        let form = document.getElementById('confirmationForm');
                let hiddenInput = document.getElementById('confirmationValue');

                
                // Set the  pID in another hidden input field (if needed)
                let deleteIDInput = document.getElementById('deleteID');
                deleteIDInput.value = pID;
                
                // Submit the form
                form.submit();

    }
}

        
// Initial rendering
renderPerformers();

function renderPerformers() {
    // Clear existing content

    hireListContainer.innerHTML = '';
    var performers = <?php echo json_encode($performers); ?>;
    var performerCount = <?php echo json_encode($performerCount); ?>;
    if (performerCount === 0) {
        // Display a message in the hireListContainer
        hireListContainer.innerHTML = '<li class="hire-item">No performers to display.</li>';
        return; // Stop function execution if performers array is empty
    }

    performers.forEach(performer => {
        const html = `
            <li class="hire-item">
                <input type="hidden" name="pID" value="${performer.pID}">
                <img src="images/performer/profile/${performer.pProfile}" alt="Profile Photo" class="profile-photo">
                <span class="hire-name">${performer.pName}</span>
                <span class="hire-position">${performer.pPosition}</span>
                <div class="remove-container">
                    <img src="assets/trash.png" alt="Remove" class="trash-icon">
                    <button class="remove-btn" onclick="removeContent(this,${performer.pID})" >Remove</button>
                </div>
            </li>
        `;
        
        // Append new item to container
        hireListContainer.innerHTML += html;
    });

    
}


    document.querySelector('.edit-btn').addEventListener('click', function() {
        document.getElementById('edit-form').style.display = 'block';
    });     

function saveChanges() {
    const title = document.getElementById('edit-title').value;
    const date = document.getElementById('edit-date').value;
    const time = document.getElementById('edit-time').value;
    const venue = document.getElementById('edit-venue').value;
    const organizer = document.getElementById('edit-organizer').value;
    const description = document.getElementById('edit-description').value;
    const requirements = document.getElementById('edit-requirements').value.split('\n');
    const about = document.getElementById('edit-about').value;

    document.querySelector('.event-title').textContent = title;
    document.querySelector('.event-date').textContent = `Date: ${new Date(date).toLocaleDateString()}`;
    document.querySelector('.event-time').textContent = `Time: ${time}`;
    document.querySelector('.event-venue').textContent = `Venue: ${venue}`;
    document.querySelector('.event-organizer').textContent = `Organized by: ${organizer}`;
    document.querySelector('.event-description').textContent = description;
    
    const requirementsList = document.querySelector('.event-requirements');
    requirementsList.innerHTML = '';
    requirements.forEach(req => {
        const li = document.createElement('li');
        li.textContent = req;
        requirementsList.appendChild(li);
    });

    document.querySelector('.event-about').textContent = about;
    document.getElementById('edit-form').style.display = 'none';
}

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
            $img_upload_path = 'images/event/banner/'. $new_img_name;
            move_uploaded_file($imgtmp_name, $img_upload_path);

            //SQL Query to database
            $imgName= $new_img_name;
            $sql = "UPDATE event 
                SET eBanner = '$imgName'
                WHERE eID = '$eID'";
            mysqli_query($conn,$sql);

            echo "<script>location.href = 'eventDetail.php?pID=$eID';</script>";
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
            $img_upload_path = 'images/event/profile/'. $new_img_name;
            move_uploaded_file($imgtmp_name, $img_upload_path);

            //SQL Query to database
            $imgName= $new_img_name;
            $sql = "UPDATE event 
                SET eProfile = '$imgName'
                WHERE eID = '$eID'";
            mysqli_query($conn,$sql);

            echo "<script>location.href = 'eventDetail.php?pID=$eID';</script>";
        }else{
            echo '<script>alert("You can\'t upload files of this type!")</script>';        
        }
    }
}

    // Check if the 'confirmationValue' field exists in the POST data
    if (isset($_POST['confirmationValue'])) {
        $deleteID = $_POST['deleteID'];

        $sql = "DELETE FROM eventperformer WHERE pID = '$deleteID' AND eID = '$eID'";

        // Execute the DELETE query
        mysqli_query($conn, $sql);
        echo "<script>location.href = 'eventDetail.php';</script>";
    }

    //Edit Event Detail
    ob_start();        
        // Handle the received videoName
        if (isset($_POST['btnEdit'])) {
            $eID = $_SESSION['eID'];
            $eTitle = filter_input(INPUT_POST, "edit-title", FILTER_SANITIZE_SPECIAL_CHARS);
            $eDate = filter_input(INPUT_POST, "edit-date", FILTER_SANITIZE_SPECIAL_CHARS);
            $eTime = filter_input(INPUT_POST, "edit-time", FILTER_SANITIZE_SPECIAL_CHARS);
            $eVenue = filter_input(INPUT_POST, "edit-venue", FILTER_SANITIZE_SPECIAL_CHARS);
            $eOrganizer = filter_input(INPUT_POST, "edit-organizer", FILTER_SANITIZE_SPECIAL_CHARS);
            $eDesc = filter_input(INPUT_POST, "edit-description", FILTER_SANITIZE_SPECIAL_CHARS);
            $eReq = filter_input(INPUT_POST, "edit-requirements", FILTER_SANITIZE_SPECIAL_CHARS);
            $eAbout = filter_input(INPUT_POST, "edit-about", FILTER_SANITIZE_SPECIAL_CHARS);
    
            //SQL Query to insert all data into table course
            $sql = "UPDATE event SET 
                eTitle = '$eTitle',
                eDate = '$eDate',
                eTime = '$eTime',
                eVenue = '$eVenue',
                eOrganizer = '$eOrganizer',
                eDesc = '$eDesc',
                eReq = '$eReq',
                eAbout = '$eAbout'
                WHERE eID = '$eID'";

            mysqli_query($conn,$sql);
            mysqli_close($conn);
            echo '<script>alert("The Information is saved")</script>'; 
            echo "<script>location.href = 'eventDetail.php';</script>";
        }
        
   ob_end_flush();


    ?>
</body>
</html>
