<?php
session_start();
include 'conn.php';
$name = $_SESSION['name'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $courseName = $_POST['courseName'];
    $numberOfLessons = $_POST['NumberofLessons'];
    $courseDescription = $_POST['courseDescription'];

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO courses (instructor, coursename, lessons, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $name, $courseName, $numberOfLessons, $courseDescription);

    if ($stmt->execute()) {
        // Create course directory
        $courseDir = __DIR__ . "/courses/" . $name . "/" . $courseName;
        if (!is_dir($courseDir)) {
            mkdir($courseDir, 0777, true);
        }

        // Create lesson folders
        for ($i = 1; $i <= $numberOfLessons; $i++) {
            $lessonDir = $courseDir . "/Lesson " . $i;
            if (!is_dir($lessonDir)) {
                mkdir($lessonDir, 0777, true);
            }
        }

        echo "Course created successfully with $numberOfLessons lessons!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
