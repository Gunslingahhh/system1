<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "connection.php";

$userId = $_SESSION['userid'];
$sql = "SELECT user_profilePicture FROM user WHERE user_id = $userId";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) { // Check if a row is fetched
    $row = $result->fetch_assoc();
    $profilePicturePath = $row['user_profilePicture'];
} else {
    $profilePicturePath = ""; // Handle the case where no user is found
}


if ($profilePicturePath == "") {
    $profilePictureSrc = ""; // Default placeholder image
} else {
    $profilePictureSrc = $profilePicturePath;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Sidenav</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
    <link href="../bootstrap-icons-1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../adminkit-main/static/js/app.js"></script>

    <style>
        .sidebar {
            background-color: #12b65d;
            color: white;
            position: fixed; /* Fixed positioning */
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto; /* Scroll if content overflows */
            z-index: 100; /* Ensure it's on top */
            width: 250px; /* Set a width for the sidebar */
        }
        .main-content {
            margin-left: 250px; /* Match sidebar width */
            padding: 20px;
        }
        @media (max-width: 767.98px) {
            .sidebar {
                position: static;
                height: auto;
                width: auto;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <div class="col-auto col-md-3 col-xl-2 px-0 sidebar">
        <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-black min-vh-100">
            <img src="<?php echo $profilePictureSrc; ?>" class="rounded-circle mx-auto mt-3 bg-white" style="width: 150px; height: 150px; object-fit: cover;">
            <span class="fs-5 fw-bold mt-2 text-center w-100 text-main"><?php echo $_SESSION['fullname'] ?></span>
            <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100 mt-3" id="menu">
                <li class="nav-item w-100">
                    <a href="redirect.php" class="text-main nav-link align-middle px-0">
                        <i class="fs-4 bi-house"></i> <span class="ms-1 d-none d-sm-inline">Home</span>
                    </a>
                </li>
                <li class="nav-item w-100">
                    <a href="settings.php" class="text-main nav-link align-middle px-0">
                        <i class="fs-4 bi-gear"></i> <span class="ms-1 d-none d-sm-inline">Settings</span>
                    </a>
                </li>
                <li class="nav-item w-100">
                    <a href="logout.php" class="text-main nav-link align-middle px-0">
                        <i class="fs-4 bi-box-arrow-right"></i> <span class="ms-1 d-none d-sm-inline">Logout</span>
                    </a>
                </li>
            </ul>
            <hr>
        </div>
    </div>
</body>

</html>