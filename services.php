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
            ["title" => "Fine Dining", "description" => "Enjoy a variety of gourmet dishes prepared by world-class chefs in a luxurious ambiance."],
            ["title" => "Spa & Wellness", "description" => "Rejuvenate with our exclusive spa treatments, massages, and wellness programs."],
            ["title" => "Swimming Pool", "description" => "Dive into our crystal-clear pool and enjoy a refreshing swim with poolside service."],
            ["title" => "Fitness Center", "description" => "Stay fit with our state-of-the-art gym featuring the latest workout equipment."],
            ["title" => "24/7 Room Service", "description" => "Enjoy round-the-clock room service, ensuring your stay is seamless and convenient."],
            ["title" => "Free Wifi", "description" => "Stay connected with high-speed internet access throughout our hotel."],
        ];

        // Loop through services array to generate service cards
        foreach ($services as $service) {
            echo "<div class='service-box'>
                    <h3>{$service['title']}</h3>
                    <p>{$service['description']}</p>
                  </div>";
        }
        ?>
    </div>
</section>

</body>
</html>
