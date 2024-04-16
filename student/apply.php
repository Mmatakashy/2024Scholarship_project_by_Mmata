<?php
session_start();
$_SESSION['selectedAppID'] = 0;
$_SESSION['appList'] = NULL;

// Check validity of the user
$currentUserID = $_SESSION['currentUserID'];
if ($currentUserID == NULL) {
    header("Location:../index.php");
    exit(); // Always exit after header redirection
}

// Connect to database
$conn = new mysqli("localhost", "root", "", "sms");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user's name
$getNameQuery = "SELECT firstName, middleName, lastName FROM student WHERE studentID = ?";
$stmt = $conn->prepare($getNameQuery);
$stmt->bind_param("s", $currentUserID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['currentUserName'] = $row['firstName'] . " " . $row['middleName'] . ". " . $row['lastName'];
} else {
    $_SESSION['currentUserName'] = "Unknown"; // Default value if user not found
}
$stmt->close();

// Close previous connection
$conn->close();
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/main.css" rel="stylesheet">
</head>

<body class="no-sidebar">
    <div id="page-wrapper">
        <!-- Header -->
        <header id="header">
            <h1 id="logo"><a href="javascript:history.back()" class="button special">Back</a></h1>
            <nav id="nav">
                <ul>
                    <li><a href="tempUserHome.php">Home</a></li>
                    <li><a href="tempUserProfile.php">User Profile</a></li>
                    <li class="current"><a href="#">Apply</a></li>
                    <li><a href="tempUserView.php">View Scholarship Status</a></li>
                    <li><?php echo $_SESSION['currentUserName'] . " (ID:" . $_SESSION['currentUserID'] . ")" ?></li>
                    <li><a href="../backend/logout.php" class="button special">Logout</a></li>
                </ul>
            </nav>
        </header>
        <!-- Main -->
        <article id="main">
            <header class="special container">
                <span class="icon fa-mobile"></span>
            </header>
            <!-- One -->
            <section class="wrapper style4 container">
                <?php
                // Connect to database
                $conn = new mysqli("localhost", "root", "", "sms");
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $schid = $_SESSION["schid"];
                $sigID = $_POST['sigID'];
                $_SESSION['sigID'] = $sigID;
                $sql = "SELECT * FROM application WHERE scholarshipID=? AND studentID=? AND sigID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iii", $schid, $currentUserID, $sigID);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    ?>
                    <script type="text/javascript">
                        alert("You Have Already Applied for this scholarship!");
                        location.replace("tempUserView.php");
                    </script>
                <?php
                } else {
                ?>
                    <h1>Dear&nbsp;&nbsp;<b><?php echo $_SESSION['currentUserName'] ?></b>,</h1>
                    <h1>Make sure you have your Profile Completed.<br>Your Profile details will be submitted in this application.<br></h1>
                    <form style="padding-left: 20%; display: inline;" method="post">
                        <input type="submit" id="apply" name="apply" value="Check Your Profile Here >>" title="User Profile" formaction="tempUserProfile.php">
                        &nbsp;&nbsp;&nbsp;
                        <input type="submit" id="apply" name="apply" value="Continue Otherwise >>" title="Click here only if your Profile is Completed!!" formaction="applyprocess.php">
                    </form>
                <?php
                }
                $stmt->close();
                $conn->close();
                ?>
            </section>
        </article>
        <!-- Footer -->
        <footer id="footer">
            <ul class="icons">
                <li><a href="#" class="icon circle fa-twitter"><span class="label">Twitter</span></a></li>
                <li><a href="#" class="icon circle fa-facebook"><span class="label">Facebook</span></a></li>
                <li><a href="#" class="icon circle fa-google-plus"><span class="label">Google+</span></a></li>
                <li><a href="#" class="icon circle fa-github"><span class="label">Github</span></a></li>
                <li><a href="#" class="icon circle fa-dribbble"><span class="label">Dribbble</span></a></li>
            </ul>
            <ul class="copyright">
                <li>&copy; KGL&SS</li>
                <li>Design: mosesmmata70@gmail.com <a href="http://html5up.net"></a></li>
            </ul>
        </footer>
    </div>
    <!-- Scripts -->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/jquery.scrolly.min.js"></script>
    <script src="../js/jquery.dropotron.min.js"></script>
    <script src="../js/skel.min.js"></script>
    <script src="../js/jquery.scrollgress.min.js"></script>
    <script src="../js/util.js"></script>
    <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
    <script src="../js/main.js"></script>
</body>

</html>
