<?php
    session_start();
    include ("database.php");

    if(isset($_SESSION['pID'])) {
        $pID = $_SESSION['pID'];
    }

    $sql = "SELECT * FROM performer
    WHERE pID = $pID";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
$row = mysqli_fetch_assoc($result);  
// Access individual columns directly
$pName = $row['pName'];
$pProfile = $row['pProfile'];
$pPosition = $row['pPosition'];
$pDesc = $row['pDesc'];
$pLabel = $row['pLabel'];
//  echo '<script type="text/javascript">alert("' . $eVenue . '")</script>';
}


// Assuming $eId is already defined or sanitized
$sql = "SELECT e.*, p.*
        FROM PERFORMER p
        LEFT JOIN eventperformer ep ON p.pID = ep.pID
        LEFT JOIN EVENT e ON ep.eID = e.eID
        WHERE p.pID = $pID AND type='request'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Initialize variables to store event details and performers
    $events = [];
    $eventCount = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        // Store performer details
        $events[] = [
            'eID' => $row['eID'],
            'eTitle' => $row['eTitle'],
            'eProfile' => $row['eProfile']
        ];

    }
} 

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Status</title>
    <link rel="stylesheet" href="./status.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="navbar-left">
                <div class="logo-section">
                    <a href="#">
                        <img src="assets/CULTRA logo.png" alt="Cultra Logo">
                    </a>
                    <h1>CULTRA</h1>
                </div>
                <div class="user-profile">
                <a href="profileDetail.php">
                    <img src="images/performer/profile/<?= htmlspecialchars($pProfile) ?>">
                    </a>
                    <h3><?= htmlspecialchars($pName) ?></h3>
                </div>
            </div>
            <ul class="menu-links">
                <li><a href="performer_home.php">Home</a></li>
                <li><a href="performer_home.php">Upcoming Event</a></li>
                <li><a href="performer_home.php">Past Event</a></li>
                <li><a href="r_status.php">Status</a></li>
                <li ><a href="logIn_signUp.php" id="logout-button">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        


        <div class="event-status"></div>
        <div class="pagination">
            <!-- Pagination buttons will be generated here -->
        </div>
    </main>

    <script>
         document.addEventListener("DOMContentLoaded", function () {
            const eventStatusContainer = document.querySelector('.event-status');
            const paginationContainer = document.querySelector('.pagination');

            var events = <?php echo json_encode($events); ?>;
            var eventCount = <?php echo json_encode($eventCount); ?>;
            
            staticEvents=[];

            // Corrected variable name from staticEvemts to staticEvents
// Assuming 'events' is an array of event objects provided elsewhere
events.forEach(event => {
    staticEvents.push({
        id: event.eID,
        name: event.eTitle,
        image: event.eProfile,
        status: 'pending'
    });
});

            const eventsPerPage = 3;
            let currentPage = 1;

            function renderEvents(page) {
                eventStatusContainer.innerHTML = '';
                const startIndex = (page - 1) * eventsPerPage;
                const endIndex = startIndex + eventsPerPage;
                const eventsToRender = staticEvents.slice(startIndex, endIndex);

                eventsToRender.forEach(event => {
                    const eventBox = createEventBox(event);
                    eventStatusContainer.appendChild(eventBox);
                });
            }

            function createEventBox(event) {
                const eventBox = document.createElement('div');
                eventBox.className = 'event-status-box';

                // Create image element
                const img = document.createElement('img');
                            img.src = 'images/event/profile/' + event.image;
                            img.alt = event.name;
                            eventBox.appendChild(img);

                const eventName = document.createElement('div');
                eventName.textContent = event.name;
                eventName.className = 'event-name';
                eventBox.appendChild(eventName);

                const status = document.createElement('label');
                status.textContent = event.status.charAt(0).toUpperCase() + event.status.slice(1);
                status.className = 'status ' + getStatusClass(event.status);
                eventBox.appendChild(status);

                return eventBox;
            }

            function getStatusClass(status) {
                switch(status) {
                    case 'accepted':
                        return 'status-accepted';
                    case 'pending':
                        return 'status-pending';
                    case 'rejected':
                        return 'status-rejected';
                    default:
                        return '';
                }
            }

            function renderPagination() {
                paginationContainer.innerHTML = '';
                const totalPages = Math.ceil(staticEvents.length / eventsPerPage);

                const prevButton = document.createElement('button');
                prevButton.textContent = '<';
                prevButton.className = 'arrow';
                prevButton.addEventListener('click', () => {
                    if (currentPage > 1) {
                        currentPage--;
                        renderEvents(currentPage);
                        renderPagination();
                    }
                });

                const nextButton = document.createElement('button');
                nextButton.textContent = '>';
                nextButton.className = 'arrow';
                nextButton.addEventListener('click', () => {
                    if (currentPage < totalPages) {
                        currentPage++;
                        renderEvents(currentPage);
                        renderPagination();
                    }
                });

                paginationContainer.appendChild(prevButton);

                for (let i = 1; i <= totalPages; i++) {
                    const button = document.createElement('button');
                    button.textContent = i;
                    button.className = i === currentPage ? 'active' : '';
                    button.addEventListener('click', () => {
                        currentPage = i;
                        renderEvents(currentPage);
                        renderPagination();
                    });
                    paginationContainer.appendChild(button);
                }

                paginationContainer.appendChild(nextButton);
            }

            renderEvents(currentPage);
            renderPagination();
        });
        function logout() {
            alert('Logout button clicked');
            window.location.href = 'logIn_signUp.php';
        }

        function toHire() {
            window.location.href = 'h_status.php';
        }
        function toProfile(){
            alert('Entering Profile');
        }
    </script>
</body>
</html>
