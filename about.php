<?php include 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/about.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="icon" type="image/x-icon" href="images/logo/hotellogo.png">
    <title>About Us</title>
</head>
<body>

<?php include 'includes/header.php'; ?>

<main class="container">
    <h2>About Our Hotel</h2>

    <div class="about-container">
        <div class="about-image">
            <img src="images/home/home3.png" alt="Hotel Image">
        </div>
        <div class="about-text">
            <p>Welcome to H-Hotel, a luxurious retreat where comfort meets elegance. Established in 2015, we have been dedicated to offering exceptional hospitality and unforgettable experiences to our guests.</p>
            <p>Our hotel boasts modern rooms, fine dining, world-class amenities, and top-notch services, ensuring a perfect stay for every traveler.</p>
            <p>Whether you're here for business or leisure, our team is committed to making your stay memorable.</p>
        </div>
    </div>

    <section class="team-section">
        <h3>Meet Our Team</h3>
        <div class="team-members">
            <div class="team-member">
                <img src="images/profile.png" alt="CEO">
                <h4>John Doe</h4>
                <p>Founder & CEO</p>
            </div>
            <div class="team-member">
                <img src="images/profile.png" alt="Manager">
                <h4>Jane Smith</h4>
                <p>Hotel Manager</p>
            </div>
            <div class="team-member">
                <img src="images/profile.png" alt="Chef">
                <h4>Michael Brown</h4>
                <p>Head Chef</p>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>

<script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
<script src="js/navbar.js"></script>

</body>
</html>
