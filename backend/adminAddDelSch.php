<?php
session_start();

// Check user validity
if (!isset($_SESSION['currentUserID']) || empty($_SESSION['currentUserID'])) {
    header("Location: ../index.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "sms");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deladd'])) {
        if ($_POST['deladd'] == 'Submit Scholarship >') {
            $flag = 0;
            $currentUserID = $_SESSION['currentUserID'];
            $name = $conn->real_escape_string($_POST['schname']);
            $schlocation = $conn->real_escape_string($_POST['schlocation']);
            $schlocationfrom = $conn->real_escape_string($_POST['schlocationfrom']);
            $degree = $conn->real_escape_string($_POST['degree']);
            $gender = $conn->real_escape_string($_POST['gender']);
            $scholarshipp = $conn->real_escape_string($_POST['scholarship']);
            $appdeadline = $conn->real_escape_string($_POST['appdeadline']);
            $granteesNum = $conn->real_escape_string($_POST['granteesNum']);
            $funding = $conn->real_escape_string($_POST['funding']);
            $description = $conn->real_escape_string($_POST['description']);
            $eligibility = $conn->real_escape_string($_POST['eligibility']);
            $benefits = $conn->real_escape_string($_POST['benefits']);
            $apply = $conn->real_escape_string($_POST['apply']);
            $links = $conn->real_escape_string($_POST['links']);
            $contact = $conn->real_escape_string($_POST['contact']);
            $adminapproval = $conn->real_escape_string($_POST['adminapproval']);
            $religion = isset($_POST['religion']) ? implode(',', $_POST['religion']) : '';

            $sql = "INSERT INTO scholarship (sigID, schname, schlocation, schlocationfrom, degree, gender, religion, sch, appDeadline, granteesNum, funding, description, eligibility, benefits, apply, links, contact, adminapproval) 
                    VALUES ('$currentUserID', '$name', '$schlocation', '$schlocationfrom', '$degree', '$gender', '$religion', '$scholarshipp', '$appdeadline', '$granteesNum', '$funding', '$description', '$eligibility', '$benefits', '$apply', '$links', '$contact', '$adminapproval')";

            if ($conn->query($sql) === TRUE) {
                $schID = $conn->insert_id;
                // XML handling code
                // Assuming XML code works fine
                $flag = 1;
            } else {
                throw new Exception("Error: " . $sql . "<br>" . $conn->error);
            }

            if ($flag == 1) {
                // File upload handling code
                // Assuming file upload code works fine
                ?>
                <script type="text/javascript">
                    alert("Scholarship added successfully and will be further processed by Admin to validate!");
                    location.replace("../signatory/tempSigScholarship.php");
                </script>
                <?php
            }
        } elseif ($_POST['deladd'] == 'EDIT Scholarship >') {
            // Update scholarship
            // Similar implementation as above for update operation
        }
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

$conn->close();
?>
