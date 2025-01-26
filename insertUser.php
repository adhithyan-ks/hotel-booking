
<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hotel-booking";
    //Ceate connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    //Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    mysqli_set_charset($conn, "utf8");

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $phone = $_POST['phone'];

        $query = "INSERT INTO users (name, email, password, phone) VALUES ('$name', '$email', '$password', '$phone')";
        mysqli_query($conn,$query);

        header("Location: login.html");
        exit(); /*
        if ($conn->query($query) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }*/
    } else {
        echo "Invalid request";
    }
?>