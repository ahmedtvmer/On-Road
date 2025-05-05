<?php
require_once '../Controllers/ValidationController.php';
ValidationController::validateSession('client');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>On-Road | Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../root/css/main.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #ff6b6b;">
        <div class="container">
            <a class="navbar-brand" href="home.php">On-Road</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">My Requests</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="avatar-circle me-2">
                                <img src="../root/img/Avatar.png" alt="Avatar" class="rounded-circle img-fluid border border-light" width="40" height="40">
                            </div>
                            <span class="text-white"><?php echo $_SESSION['username']; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../Controllers/LogoutController.php">Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="diagonal-line"></div>
                    <div class="hero-content">
                        <h1 class="hero-title">24/7 Onroad Breakdown Assistance in Egypt</h1>
                        <p class="hero-subtitle">DON'T WAIT ANYMORE. AVAILABLE ANYTIME ANYWHERE IN EGYPT.</p>
                        <div class="d-flex gap-3">
                            <a href="#" class="btn btn-primary">Make Your Request Now</a>
                            <a href="#" class="btn btn-outline-primary">Track Your Request</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="about-section">
        <div class="container">
            <h2 class="section-title">Who We Are?</h2>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <p class="about-text text-center">
                        On-Road Vehicle Breakdown Assistance is a Web Application which is definitely a good solution for the people who seek help on the road in the middle of a journey or in the remote locations with major or minor mechanical issues of their vehicle. In order to avail our service, users can jus make a request by filling a simple request form.
                    </p>
                    <p class="about-text text-center mt-4">
                        The users need not login or register into the system. The users can specify the service centre as they prefer. Once the request is made they will be assigned to a trustworthy and experienced service provider from On-Road. The Driver will be assigned by the administrator shortly. Once the request is assigned the driver from On-Road will reach your given destination with the customised towing trucks within 30-40 minutes. Ousr service Provider will now assist you to transport your vehicle to the specified location.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="features-section">
        <div class="container">
            <h2 class="section-title">Why Choose Us</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="feature-title">On-Time Service</h3>
                        <p class="feature-text">
                            On-Road ensures you fast and on time service after you make a request to us. You will connected with a service provider who will reach yor location and assist you. Team On-Road is able to reach you within 30-40 minutes.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h3 class="feature-title">Well-Trained Proffesionals</h3>
                        <p class="feature-text">
                            On-Road Onroad assistance will assign your request with a well-trained, trustworthy and approved Service Providers. With 50+ customised tow trucks. Our drivers are able to your location as fast as possible.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <h3 class="feature-title">Available across Egypt</h3>
                        <p class="feature-text">
                            TEAM On-Road provides a network of service providers who are available 24/7 across Egypt for your assistance. We provide trusted and reliable service anytime anywhere in Egypt when you need it the most.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>