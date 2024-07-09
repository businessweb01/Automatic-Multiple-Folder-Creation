<?php
include 'conn.php';
session_start();
// Retrieve the user's name from the session
$name = $_SESSION['name'] ?? 'default_user';

// Define the path to the user's folder
$userFolderPath = __DIR__ . "/courses/$name";

// Fetch course details from the database
$sql = "SELECT coursename, description, lessons FROM courses WHERE instructor = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $name);
$stmt->execute();
$stmt->bind_result($coursename, $description, $lessons);

// Prepare an associative array to hold course details
$courses = [];
while ($stmt->fetch()) {
    $courses[$coursename] = [
        'description' => $description,
        'lessons' => $lessons
    ];
}

$stmt->close();

// Check if a course is selected and store it in the session
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['coursename'])) {
//     $_SESSION['selected_course'] = $_POST['coursename'];
// }

// Check if the directory exists
if (is_dir($userFolderPath)) {
    // Open the directory
    $dir = opendir($userFolderPath);

    // Display the folders in the user's directory
    while (($file = readdir($dir)) !== false) {
        // Skip the current (.) and parent (..) directories
        if ($file != '.' && $file != '..') {
            // Fetch the corresponding course details
            $courseDetails = $courses[$file] ?? ['description' => 'No description available', 'lessons' => '0'];

            // Check if this is the selected course
            $isSelected = ($file === ($_SESSION['selected_course'] ?? '')) ? 'selected' : '';

            // Display the folder with course details inside a form
            echo "<form method='post' action='view_folder.php?coursename=$file'>";
            echo "<button class='folder-button $isSelected' name='viewfolder' type='submit'>";
            echo "<input type='hidden' name='coursename' value='$file'>";
            echo "<div class='folder'>";
            echo "<h3>$file</h3>";
            echo "<p>Lessons: {$courseDetails['lessons']}</p>";
            echo "<p>Description: {$courseDetails['description']}</p>";
            echo "</div>";
            echo "</button>";
            echo "</form>";
        }
    }

    // Close the directory
    closedir($dir);
} else {
    echo "<div class='NoFolders'>";
    echo "<p>No courses found for $name</p>";
    echo "</div>";
}

// Close the database connection
$conn->close();

// Indicate that display_folders.php was included
echo "<script>var isFoldersDisplayed = true;</script>";
?>
