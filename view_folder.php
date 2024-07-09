<?php
session_start();
include 'conn.php';

// Retrieve the user's name from the session
$name = $_SESSION['name'] ?? 'default_user';

// Retrieve the selected course from the POST request or session
$coursename = $_POST['coursename'] ?? $_SESSION['selected_course'] ?? null;

if ($coursename) {
    // Store the selected course in the session
    $_SESSION['selected_course'] = $coursename;

    // Fetch the course details from the database
    $sql = "SELECT description, lessons FROM courses WHERE coursename = ? AND instructor = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $coursename, $name);
    $stmt->execute();
    $stmt->bind_result($description, $lessons);
    $stmt->fetch();
    $stmt->close();
} else {
    $description = 'No description available';
    $lessons = '0';
}
?>
<style>
    *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}
body{
    background:linear-gradient(180deg, #C8C1AC,#FFE794);
    min-height: 100vh;
    width: 100%;
    background-repeat: no-repeat;
}
/* Navbar */
.header{
    width: 100%;
    height: fit-content;
}
.navigation{
    display: flex;
    background-color: #1a1a1a;
    padding: 20px 10px;
    justify-content: space-between;
    align-items: center;
}
.nav-links{
    display: flex;
    gap: 2rem;
    margin-inline: 2rem;
}
.nav-link{
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    font-size: clamp(0.7rem, 1vw, 1.5rem);
    transition: 0.5s ease;
}
.nav-link:hover{
    color:rgba(255, 255, 255, 1);
}
.nav-icons{
    display: flex;
    gap: 1rem;
    margin-right: 2rem;
}
.bell-icon:hover{
    animation: tada 1s;
}
@keyframes tada {
    0% {
        transform: scale(1);
    }
    10%, 20% {
        transform: scale(0.9) rotate(-3deg);
    }
    30%, 50%, 70%, 90% {
        transform: scale(1.1) rotate(3deg);
    }
    40%, 60%, 80% {
        transform: scale(1.1) rotate(-3deg);
    }
    100% {
        transform: scale(1) rotate(0);
    }
}
@media screen and (max-width: 768px) {
   .nav-links{
       margin-inline: 1rem;
       gap: 1rem;
   }
    
}

.popup {
    display: none;
    position: fixed;
    top: 0;
    right: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

.popup-content {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    width: 300px;
    
}
.close {
    top:0;
    left:0;

    position: absolute;
    font-size: 24px;
    cursor: pointer;
    color: #000000;
}

.userpicname {
    display: flex;
    flex-direction: row;
    align-items: center;
    margin-bottom: 1rem;

}
.userpic {
    width: 50px;
    height: 50px;
    margin-top:1rem;
    background-image: url('dp.png');
    background-size: cover;
    background-position: center;
    border-radius: 50%;
    margin-bottom: 10px;
}

.username {
    margin-left: 1rem;
    font-size: 20px;
    font-weight: bold;
}

.divider {
    width: 100%;
    align-self: center;
    background-color: #7CBE28;
    border: none;
    border-radius: 1rem;
    height: 0.2rem;
}
.settings{
    margin-top: 1rem;
    margin-bottom: 1rem;
}

.IconLink{
    text-decoration: none;
    color: #000000;
}   
.editprofile{
    display: flex;
    flex-direction: row;
    align-items: center;
    margin-bottom: 1rem;
    gap: 0.5rem;
}
.lightdark {
    width: fit-content;
    border-radius: 50px;
    display: flex;
   gap: 0.5rem;
}
.lightdark p{
    margin-right: 0.2rem;

}
.light{
    padding: 5px 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    height: fit-content;
    width: fit-content;
    border-radius: 50px;
    border:none;
    background: #ffffff;
    box-shadow: inset 5px 5px 10px #b3b3b3,
                inset -5px -5px 10px #ffffff;
}
.dark{
    padding: 5px 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    height: fit-content;
    width: fit-content;
    border-radius: 50px;
    border:none;
    background: #ffffff;
    box-shadow: inset 5px 5px 10px #b3b3b3,
                inset -5px -5px 10px #ffffff;
}
.sun{
    border-radius: 50px;
    margin-right: 0.2rem;
}   
.moon{
    border-radius: 50px;
}
.exitdashboard{
    margin-top: 1rem;
    display: flex;
    flex-direction: row;
    align-items: center;
    margin-bottom: 1rem;
    gap: 0.5rem;
}
/* End of Navbar */

/* course content */
.courses{
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    
}
.course-content{
    width: 90%;
    height:fit-content;
    max-height: 650px;
    display: flex;
    flex-direction: column;
    margin-top: 2rem;
    margin-bottom: 2rem;
    background: #EEF0F4;
    border-radius: 20px;
    box-shadow: inset 4.2px 4.2px 9px #B1B3B6, inset -4.2px -4.2px 9px #FFFFFF;
    overflow-y: scroll;
}
.logo{
    margin-top: 1rem;
    margin-left: 2rem;
    /* margin-top: 1rem; */
    font-weight: 600;
    font-size: clamp(2rem, 2vw, 3rem);
    width: fit-content;
    height: fit-content;
    border-radius: 2rem;
    padding: 10px 20px;
    background: #e3e3e3;
    box-shadow: 0.6px 0.6px 6px #757575, -0.6px -0.6px 6px #FFFFFF;

}
.Agri{
    background: url(grass.jpg);
    background-size: contain;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    
}
.course-name{
    margin-top: 1rem;
    margin-left: 2rem;
    font-size: clamp(1.5rem, 3vw, 5rem);
}
.lesson{
    margin-top: 2rem;
    margin-left: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.upload-video{
    width: fit-content; 
    height: fit-content;
    border-radius: 10px;
    padding: 10px 20px;
    background-color: #007BFF;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-left: auto;
    margin-right: 2rem;
    font-size:clamp(0.7rem, 1vw, 1.5rem);
}


.popup-form {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 20px;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    z-index: 1001;
}

/* Style for the overlay */
.overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}
.video-gallery {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    gap: 10px;
    margin-left: 5rem;
}
@media screen and (max-width: 768px) {
    .video-gallery {
        margin-left: 0;
        justify-content: center;
    }
    
}
.video-item {
    display: flex;
    flex-direction: column;
    flex: 1 1 300px; /* Flex-grow, flex-shrink, flex-basis */
    box-sizing: border-box;
    max-width: 320px; /* Max width to keep videos uniform */
    margin-bottom: 10px;
    
}

.video-item p {
    text-align: center;
    margin-top: 1rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

video {

    display: block;
    max-width: 100%; /* Ensure video fits the container */
    height: 180px; /* Set a uniform height */
    object-fit: cover; /* Crop the video to fit the dimensions */
    border-radius: 10px;
}

.lesson {
    margin-bottom: 1rem;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

h3 {
    font-size:clamp(1rem, 2vw, 2rem);
    margin-bottom: 10px;
}

h4 {
    margin-top: 0;
    margin-bottom: 0.5rem;
}
.video-controls {
    display: flex;
    justify-content: center;
    gap: 10px;
}
.video-edit{
    font-size:clamp(0.7rem, 1vw, 1.5rem);
    padding: 5px 15px;
    border-radius: 20px;
    height: fit-content;
    width: fit-content;
    color: #fff;
    background-color: #CA8F4A;
    border: none;
}
.video-delete{
    font-size:clamp(0.7rem, 1vw, 1.5rem);
    padding: 5px 15px;
    border-radius: 20px;
    height: fit-content;
    width: fit-content;
    color: #fff;
    background-color: #EF6464;
    border: none;
}
.upload-assessment{
    margin-top: 2rem;
    align-items: center;
    justify-content: center;
    display: flex;
    flex-direction: column;
}
.assessment{
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    font-size:clamp(0.8rem, 1.5vw, 2rem);
    padding: 5px 15px;
    border-radius: 20px;
    height: fit-content;
    width: fit-content;
    margin-top: 1rem;
    margin-bottom: 1rem;
    cursor: pointer;
    gap: 1rem;
    font-weight: 600;
    background: #3A61CF;
    border:1px solid #3A61CF;
    color: white;
    border-radius: 1rem;
    transition: all 300ms;
}
.assessment:hover{
    ransform: scale(1.1);
    letter-spacing: 2px;
    background: white;
    color: #3A61CF;
    box-shadow: 2px 2px 3px rgba(0,0,0,0.57);
}
/* General styling for popup form */
.popup-form {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    padding: 20px;
    width: 300px;
    max-width: 90%;
    z-index: 1000;
    font-family: 'Arial', sans-serif;
}

/* Form elements styling */
.popup-form form {
    display: flex;
    flex-direction: column;
}

.popup-form label {
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
}

.popup-form input[type="text"],
.popup-form input[type="file"] {
    margin-bottom: 15px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    transition: border-color 0.3s, box-shadow 0.3s;
}

.popup-form input[type="text"]:focus,
.popup-form input[type="file"]:focus {
    border-color: #007BFF;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    outline: none;
}

/* Button styling */
.popup-form button[type="submit"],
.popup-form button[type="button"] {
    padding: 10px;
    border: none;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s;
}

.popup-form button[type="submit"] {
    background-color: #007BFF;
    color: #fff;
    margin-bottom: 10px;
}

.popup-form button[type="submit"]:hover {
    background-color: #0056b3;
    transform: translateY(-2px);
}

.popup-form button[type="button"] {
    background-color: #6c757d;
    color: #fff;
}

.popup-form button[type="button"]:hover {
    background-color: #5a6268;
    transform: translateY(-2px);
}

/* Overlay styling */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
}

</style>
<script src="node_modules/boxicons/dist/boxicons.js"></script>
<header class="header">
    <nav class="navigation">
        <div class="nav-links">
            <a href="#" class="nav-link">Dashboard</a>
            <a href="course.php" class="nav-link">Courses</a>
            <a href="#" class="nav-link">History</a>
        </div>
    <div class="nav-icons">
        <box-icon name='bell' color='#fff' type="solid" size="max(1.5vw, 20px)" class="bell-icon" class="nav-icon"></box-icon>
        <box-icon name='user-circle' color='#fff' type="solid" size="max(1.5vw, 20px)" onclick="togglePopup(event)"class="nav-icon"></box-icon>
        <!-- Popup Profile -->
        <div class="popup" id="popup">
            <div class="popup-content">
            <box-icon name='x'class="close" onclick="closePopup(event)"></box-icon>
                <div class="userpicname">
                    <div class="userpic"></div>
                    <div class="username"><?php echo $name;?></div>
                </div>
                <hr class="divider">
                <div class="settings">
                    <div class="editprofile"><box-icon name='edit' color='#000' class="settingsIcons"></box-icon><a class="IconLink" href="#">Edit Profile</a></div>

                    <div class="lightdark">
                        <button class="light"><box-icon name='sun' type='solid' color='#ffad00' class="settingsIcons sun"></box-icon><p class="IconText">Light</p></button>
                        <button class="dark"><box-icon name='moon' type='solid' color='#000' class="settingsIcons moon"></box-icon><p class="IconText">Dark</p></button>
                    </div>

                    <div class="exitdashboard">
                        <box-icon name='exit' color='#000' type='solid'class="settingsIcons"></box-icon><a class="IconLink" href="#">Exit Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Popup -->
    </nav>
 </header>
 <!-- End of Header -->

 <div class="courses">
    <div class="course-content">
    <div class="logo"><span class="Agri">Agri</span><span>Learn</span></div>
        <h1 class="course-name"><?php if ($coursename) {
                // Fetch the course details from the database or perform any operations needed
                echo $coursename;
                // You can now use $coursename to display details or perform actions
            } else {
                echo "No course selected.";
            } ?></h1>
            
            <?php
             $name = $_SESSION['name'];
             $coursename = $_GET['coursename'];  // Ensure you get the course name from the request
             
             // Define the directory path for the course
             $courseDir = "courses/$name/$coursename/";
             
             // Get the list of lesson folders in the course directory
             if (is_dir($courseDir)) {
                 $lessons = array_filter(glob($courseDir . '*'), 'is_dir');
                 $lessonCount = count($lessons);
             
                 foreach ($lessons as $lessonDir) {
                     // Extract the lesson number from the directory name
                     $lessonNumber = basename($lessonDir);
             
                     echo "<div class='lesson'>";
                     echo "<h3>$lessonNumber</h3>";
                     // Button to open the pop-up form
                     echo "<button type='button' class='open-popup upload-video' data-lesson='$lessonNumber'>Upload Video</button>";
                     // Display videos for the lesson
                     $videos = glob("$lessonDir/*.mp4"); // Fetch all .mp4 files
                     if (count($videos) > 0) {
                        echo "<h4>Videos:</h4>";
                        echo "<div class='video-gallery'>";
                            foreach ($videos as $video) {
                                $videoName = pathinfo($video, PATHINFO_FILENAME);
                                echo "<div class='video-item'>";
                                echo "<video controls width='300'>";
                                    echo "<source src='$video' type='video/mp4'>";
                                    echo "Your browser does not support the video tag.";
                                echo "</video>";
                                echo "<p>$videoName</p>";
                                echo "<div class='video-controls'>";
                                    echo "<button class='video-edit' data-video='$video'>Update</button>";
                                    echo "<button class='video-delete' data-video='$video'>Delete</button>";
                                echo "</div>";
                                echo "</div>";
                            }
        
                        echo "</div>";

                        echo "<div class='upload-assessment'>";
                        echo "<button type='button' class='assessment'>Upload Assessment</button>";
                        echo "</div>";
                     } else {
                         echo "<p>No videos available for this lesson.</p>";
                     }
                     echo "</div>";
                 }
             } else {
                 echo "No lessons found for this course.";
             }
            ?>


           <!-- Pop-up Form -->
           <div id="popupForm" class="popup-form" style="display: none;">
                <form id="uploadForm" action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="lessonNumber" id="lessonNumber" value="">
                    <input type="hidden" name="courseName" value="<?php echo $coursename; ?>">
                    <label for="videoName">Video Name:</label>
                    <input type="text" name="videoName" id="videoName" required>
                    <label for="videoFile">Upload Video for <span id="lessonLabel"></span>:</label>
                    <input type="file" name="videoFile" id="videoFile" accept="video/*" required>
                    <button type="submit">Upload</button>
                    <button type="button" id="closePopup">Cancel</button>
                </form>
            </div>

            <!-- Overlay -->
            <div id="overlay" class="overlay" style="display: none;"></div>
    </div>
 </div>
 <script src="jquery.js"></script>
 <script>
    $(document).ready(function () {
        $('#uploadForm').submit(function (event) {
            event.preventDefault();

            var formData = new FormData(this);
            $.ajax({
                url: 'video_upload.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    alert(response);
                    location.reload();
                },
                error: function (xhr, status, error) {
                    alert(error);
                }
            });
        });
    });
     function togglePopup(event) {
        event.stopPropagation();
        var popup = document.getElementById('popup');
        popup.style.display = popup.style.display === 'none' ? 'block' : 'none';
    }

    function closePopup(event) {
        event.stopPropagation();
        var popup = document.getElementById('popup');
        popup.style.display = 'none';
    }
    document.addEventListener('DOMContentLoaded', function () {
    // Get the elements
    const popupForm = document.getElementById('popupForm');
    const overlay = document.getElementById('overlay');
    const closePopupButton = document.getElementById('closePopup');

    // Add event listeners for the open-popup buttons
    document.querySelectorAll('.open-popup').forEach(button => {
        button.addEventListener('click', function () {
            const lessonNumber = this.getAttribute('data-lesson');
            document.getElementById('lessonNumber').value = lessonNumber;
            document.getElementById('lessonLabel').textContent = lessonNumber;

            // Show the pop-up form and overlay
            popupForm.style.display = 'block';
            overlay.style.display = 'block';
        });
    });

    // Add event listener for the close-popup button
    closePopupButton.addEventListener('click', function () {
        popupForm.style.display = 'none';
        overlay.style.display = 'none';
    });

    // Add event listener for the overlay
    overlay.addEventListener('click', function () {
        popupForm.style.display = 'none';
        overlay.style.display = 'none';
    });
});
 </script>