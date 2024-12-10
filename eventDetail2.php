<?php
    session_start();
    include ("database.php");
        
    //Retrieve data from previous page
    // if(isset($_GET['id'])) {
    //     $_SESSION['eId'] = $_GET['id'];
    // }

    $_SESSION['eId'] = 1;

    if(isset($_SESSION['eId'])) {
        $eId = $_SESSION['eId'];
    }
// Assuming $eId is already defined or sanitized
$sql = "SELECT e.*, p.*
        FROM event e
        LEFT JOIN eventperformer ep ON e.eID = ep.eID
        LEFT JOIN performer p ON ep.pID = p.pID
        WHERE e.eID = $eId";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Initialize variables to store event details and performers
    $performers = [];

    while ($row = mysqli_fetch_assoc($result)) {
        // Store event details (assuming they are the same for all rows)
        if (empty($eTitle)) {
            // Access individual columns directly
            $eTitle = $row['eTitle'];
            $eDate = $row['eDate'];
            $eTime = $row['eTime'];
            $eVenue = $row['eVenue'];
            //$eOrganizer = trim($row['eOrganizer']);
            $eOrganizer = $row['eOrganizer'];
            $eHeader = $row['eHeader'];
            $eProfile = $row['eProfile'];
            $eDesc = $row['eDesc'];
            $eReqArray = $row['eReq'];
            $eReq = explode(',', $eReqArray);
            $eAbout = $row['eAbout'];
        }

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
    }

    foreach ($performers as $performer) {
        $pID = $performer['pID'];
        $pName = $performer['pName'];
        //echo "<script>alert('$pID - $pName');</script>";
    }

        } else {
            echo "No records found";
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
        <div class="event-header">
            <img src="download1.jpg" alt="Event Banner" class="event-banner">
            <div class="event-logo-title">
                <img src="download1.jpg" alt="Event Logo" class="event-logo">
                <h1 class="event-title"><?= htmlspecialchars($eTitle) ?></h1>
            </div>
            <button class="edit-btn">
                <img src="edit.png" alt="Edit" class="edit-icon">
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
        <ul class="event-requirements">
            <li>Requirement 1</li>
            <li>Requirement 2</li>
            <li>Requirement 3</li>
        </ul>
        <h2>About the Job</h2>
        <p class="event-about">Details about the job, expectations, and other relevant information.</p>
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
        <form>
            <label for="edit-title">Event Title:</label>
            <input type="text" id="edit-title" name="edit-title" value="Event Title">
            
            <label for="edit-date">Date:</label>
            <input type="date" id="edit-date" name="edit-date" value="2024-07-20">
            
            <label for="edit-time">Time:</label>
            <input type="text" id="edit-time" name="edit-time" value="10:00 AM - 4:00 PM">
            
            <label for="edit-venue">Venue:</label>
            <input type="text" id="edit-venue" name="edit-venue" value="Conference Hall A">
            
            <label for="edit-organizer">Organizer:</label>
            <input type="text" id="edit-organizer" name="edit-organizer" value="Organizer Name">
            
            <label for="edit-description">Description:</label>
            <textarea id="edit-description" name="edit-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur nec nisl odio. Mauris vehicula at nunc id posuere.</textarea>
            
            <label for="edit-requirements">Requirements:</label>
            <textarea id="edit-requirements" name="edit-requirements">Requirement 1\nRequirement 2\nRequirement 3</textarea>
            
            <label for="edit-about">About the Job:</label>
            <textarea id="edit-about" name="edit-about">Details about the job, expectations, and other relevant information.</textarea>
            
            <button type="button" class="btn save-btn" onclick="saveChanges()">Save</button>
            <button type="button" class="btn cancel-btn" onclick="cancelEdit()">Cancel</button>
        </form>
        
    </div>
    

    
    <script>
        
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
    const performers = <?php echo json_encode($performers); ?>;
    if (!performers || performers.length === 1) {
        // Display a message in the hireListContainer
        hireListContainer.innerHTML = '<li class="hire-item">No performers to display.</li>';
        return; // Stop function execution if performers array is empty
    }

    performers.forEach(performer => {
        const html = `
            <li class="hire-item">
                <input type="hidden" name="pID" value="${performer.pID}">
                <img src="${performer.pProfile}" alt="Profile Photo" class="profile-photo">
                <span class="hire-name">${performer.pName}</span>
                <span class="hire-position">Position: ${performer.pPosition}</span>
                <span class="hire-role">${performer.pLabel}</span>
                <div class="remove-container">
                    <img src="trash.png" alt="Remove" class="trash-icon">
                    <button class="remove-btn" onclick="removeContent(this,${performer.pID})" >Remove</button>
                </div>
            </li>
        `;
        
        // Append new item to container
        hireListContainer.innerHTML += html;
    });

    
}










        renderPerformers()

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
    // Check if the 'confirmationValue' field exists in the POST data
    if (isset($_POST['confirmationValue'])) {
        $deleteID = $_POST['deleteID'];

        $sql = "DELETE FROM eventperformer WHERE pID = '$deleteID'";

        // Execute the DELETE query
        mysqli_query($conn, $sql);
        echo "<script>location.href = 'eventDetail.php';</script>";
    }

    ?>
</body>
</html>
