<?php include 'includes/config.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services</title>
    <link rel="stylesheet" href="css/services.css"> <!-- Link to External CSS -->
    <link rel="stylesheet" href="css/nav.css" />
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<section class="services-section">
    <h2 class="section-title">OUR FACILITIES</h2>
    <p class="section-description">
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus incidunt odio quos dolore commodi repudiandae tenetur consequuntur et similique asperiores.
    </p>

    <div class="services-container">
        <?php
        // Array of services
        $services = [
            ["icon" => "wifi", "title" => "Wifi", "description" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Omnis quaerat non nam aperiam exercitationem neque a!"],
            ["icon" => "wifi", "title" => "Wifi", "description" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Omnis quaerat non nam aperiam exercitationem neque a!"],
            ["icon" => "wifi", "title" => "Wifi", "description" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Omnis quaerat non nam aperiam exercitationem neque a!"],
            ["icon" => "wifi", "title" => "Wifi", "description" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Omnis quaerat non nam aperiam exercitationem neque a!"],
            ["icon" => "wifi", "title" => "Wifi", "description" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Omnis quaerat non nam aperiam exercitationem neque a!"],
            ["icon" => "wifi", "title" => "Wifi", "description" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Omnis quaerat non nam aperiam exercitationem neque a!"],
        ];

        // Loop through services array to generate service cards
        foreach ($services as $service) {
            echo "<div class='service-box'>
                    <div class='service-icon'>📶</div>
                    <h3>{$service['title']}</h3>
                    <p>{$service['description']}</p>
                  </div>";
        }
        ?>
    </div>
</section>

</body>
</html>
