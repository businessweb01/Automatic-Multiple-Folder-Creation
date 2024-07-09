<?php
session_start();
include 'conn.php';
$name = $_SESSION['name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['videoFile']) && isset($_POST['courseName']) && isset($_POST['lessonNumber']) && isset($_POST['videoName'])) {
        $coursename = $_POST['courseName'];
        $lessonNumber = $_POST['lessonNumber'];
        $videoName = $_POST['videoName'];
        $uploadDir = "courses/$name/$coursename/$lessonNumber/";

        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                echo "Failed to create lesson directory.";
                exit;
            }
        }

        $uploadedFile = $_FILES['videoFile']['tmp_name'];
        $fileExtension = pathinfo($_FILES['videoFile']['name'], PATHINFO_EXTENSION);
        $newFileName = $videoName . '.' . $fileExtension;
        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($uploadedFile, $destination)) {
            echo "Video uploaded successfully.";
            $videoPath = mysqli_real_escape_string($conn, $destination); // Escape the file path for database insertion
            $query = "INSERT INTO videolessons (instructor, courseName, videoPath) VALUES ('$name', '$coursename', '$videoPath')";
            $result = mysqli_query($conn, $query);

            if (!$result) {
                echo "Failed to insert video information into the database.";
            }
        } else {
            echo "Failed to upload the video.";
        }
    } else {
        echo "No video file, course name, lesson number, or video name provided.";
    }
} else {
    echo "Invalid request method.";
}
?>
