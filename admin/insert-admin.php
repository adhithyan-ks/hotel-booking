
<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hotel_db";
    //Ceate connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    //Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    mysqli_set_charset($conn, "utf8");

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['adminName'];
        $email = $_POST['adminEmail'];
        $password = $_POST['password'];
        $query = "SELECT * FROM admins WHERE email = '$email'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
          echo "<script>alert('Email already exists');</script>";
          header('Location: login.php');
        } else {
            $query = "INSERT INTO admins (admin_name, admin_email, password) VALUES ('$name', '$email', '$password')";
            mysqli_query($conn,$query);
            header("Location: index.php");
            exit(); 
            if ($conn->query($query) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $query . "<br>" . $conn->error;
            } 
        // Redirect to the home page
        header('Location: home.php');
        exit();
        }
        
    } else {
        echo "Invalid request";
    }
?>