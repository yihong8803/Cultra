<?php
session_start();
include("database.php");

if (isset($_SESSION['oID'])) {
    $oID = $_SESSION['oID'];
}

if (isset($_SESSION['eID'])) {
    $eID = $_SESSION['eID'];
}

$sql = "SELECT * FROM ORGANIZER WHERE oID = $oID";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);  
    $oName = $row['oName'];
    $oProfile = $row['oProfile'];
    $oDesc = $row['oDesc'];
}

$sql = "SELECT * FROM event WHERE eID = $eID";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);  
    $eTitle = $row['eTitle'];
    $eDate = $row['eDate'];
    $eTime = $row['eTime'];
    $eVenue = $row['eVenue'];
    $eOrganizer = $row['eOrganizer'];
    $eBanner = $row['eBanner'];
    $eProfile = $row['eProfile'];
    $eDesc = $row['eDesc'];
    $eReqArray = $row['eReq'];
    $eAbout = $row['eAbout'];
}

$sql = "SELECT e.*, p.*
        FROM EVENT e
        LEFT JOIN eventperformer ep ON e.eID = ep.eID
        LEFT JOIN PERFORMER p ON ep.pID = p.pID
        WHERE e.eID = $eID AND ep.type = 'hire' AND ep.status = 'pending'";

$result = mysqli_query($conn, $sql);

$performers = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $performers[] = [
            'pID' => $row['pID'],
            'pName' => $row['pName'],
            'pProfile' => $row['pProfile'],
            'pPosition' => $row['pPosition'],
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hire Status</title>
    <link rel="stylesheet" href="h_status.css">
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
                    <img src="images/organizer/profile/<?= htmlspecialchars($oProfile) ?>">
                </a>
                <h3><?= htmlspecialchars($oName) ?></h3>
            </div>
        </div>
        <ul class="menu-links">
            <li><a href="organizer_home.php">Home</a></li>
            <li><a href="organizer_home.php">Upcoming Event</a></li>
            <li><a href="organizer_home.php">Past Event</a></li>
            <li><a href="h_status.php">Status</a></li>
            <li><a href="logIn_signUp.php" id="logout-button">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <form method="post">
        <input type="hidden" name="eID" id="eID" value="<?= $eID ?>">
        <div class="event-status"></div>
        <div class="pagination"></div>
    </form>
</main>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const performerStatusContainer = document.querySelector('.event-status');
    const paginationContainer = document.querySelector('.pagination');

    var performers = <?php echo json_encode($performers); ?>;
    var staticPerformers = [];

    performers.forEach(performer => {
        staticPerformers.push({
            id: performer.pID,
            name: performer.pName,
            image: performer.pProfile,
            status: 'pending'
        });
    });

    const performersPerPage = 3;
    let currentPage = 1;

    function renderPerformers(page) {
        performerStatusContainer.innerHTML = '';
        const startIndex = (page - 1) * performersPerPage;
        const endIndex = startIndex + performersPerPage;
        const performersToRender = staticPerformers.slice(startIndex, endIndex);

        performersToRender.forEach((performer, index) => {
            const performerBox = createPerformerBox(performer, index);
            performerStatusContainer.appendChild(performerBox);
        });
    }

    function createPerformerBox(performer, index) {
        const performerBox = document.createElement('div');
        performerBox.className = 'performer-status-box';

        const img = document.createElement('img');
        img.src = 'images/performer/profile/' + performer.image;
        img.alt = performer.name;
        performerBox.appendChild(img);

        const performerName = document.createElement('div');
        performerName.textContent = performer.name;
        performerName.className = 'performer-name';
        performerBox.appendChild(performerName);

        const statusAccept = document.createElement('button');
        statusAccept.textContent = 'Accept';
        statusAccept.className = 'statusAccept';
        statusAccept.type = 'submit';
        statusAccept.name = 'statusAccept';
        statusAccept.value = performer.id;
        statusAccept.addEventListener('click', () => {
            document.getElementById('eID').value = statusAccept.value;
            document.getElementById("eID").submit();

            const gridItem = statusAccept.closest('.performer-status-box');
            gridItem.remove();
            alert('Event Accepted!');
        });

        const statusReject = document.createElement('button');
        statusReject.textContent = 'Reject';
        statusReject.className = 'statusReject';
        statusReject.type = 'submit';
        statusReject.name = 'statusReject';
        statusReject.value = performer.id;
        statusReject.addEventListener('click', () => {
            document.getElementById('eID').value = statusReject.value;
            document.getElementById("eID").submit();

            const gridItem = statusReject.closest('.performer-status-box');
            gridItem.remove();
        });

        performerBox.appendChild(statusAccept);
        performerBox.appendChild(statusReject);
        return performerBox;
    }

    function renderPagination() {
        paginationContainer.innerHTML = '';
        const totalPages = Math.ceil(staticPerformers.length / performersPerPage);

        const prevButton = document.createElement('button');
        prevButton.textContent = '<';
        prevButton.className = 'arrow';
        prevButton.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderPerformers(currentPage);
                renderPagination();
            }
        });

        const nextButton = document.createElement('button');
        nextButton.textContent = '>';
        nextButton.className = 'arrow';
        nextButton.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                renderPerformers(currentPage);
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
                renderPerformers(currentPage);
                renderPagination();
            });
            paginationContainer.appendChild(button);
        }

        paginationContainer.appendChild(nextButton);
    }

    renderPerformers(currentPage);
    renderPagination();
});
</script>

<?php
if (isset($_POST['statusReject'])) {
    echo '<script type="text/javascript">alert("' . $eID . '")</script>';
    $pID = $_POST['eID'];
    $sql = "UPDATE eventperformer SET status = 'reject' WHERE eID = '$eID' AND pID = '$pID'";
    mysqli_query($conn, $sql);
    echo "<script>location.href = 'h_status.php';</script>";
} elseif (isset($_POST['statusAccept'])) {
    $pID = $_POST['eID'];
    $sql = "UPDATE eventperformer SET status = 'approve' WHERE eID = '$eID' AND pID = '$pID'";
    mysqli_query($conn, $sql);
    echo "<script>location.href = 'h_status.php';</script>";
}
?>

</body>
</html>
