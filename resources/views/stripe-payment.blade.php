<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }

        .navbar {
            background: #0077ff;
        }

        .navbar-brand {
            color: #fff !important;
            font-size: 1.5rem;
        }

        .navbar-nav .nav-link {
            color: #fff !important;
        }

        .navbar-nav .nav-link:hover {
            text-decoration: underline;
        }

        .hero-section {
            background: url('https://source.unsplash.com/1600x900/?health,doctor') no-repeat center center/cover;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-section h1 {
            color: #fff;
            font-size: 1.5rem;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
        }

        .service-card:hover {
            transform: scale(1.05);
            transition: 0.3s;
        }

        footer {
            background-color: #0077ff;
            color: #fff;
        }

        footer p {
            margin: 0;
        }
    </style>
</head>

<body class="bg-light">
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="/">HealthCare+</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Main Content -->
    <div class="container my-5">
    <div class="row align-items-center">
        <!-- Left Image Section -->
        <div class="col-lg-3 mb-4 mb-lg-0">
   
</div>
        
        <!-- Right Payment Form Section -->
        <div class="col-lg-6">
            <div class="max-w-l mx-auto p-5 bg-white rounded-lg shadow-lg border border-light">
                <h3 class="font-semibold text-center text-primary mb-4">Pay with Stripe</h3>
                <form action="{{ route('payments.charge', $payment->id) }}" method="POST" id="payment-form">
                    @csrf
                    <div class="mb-4">
                        <label for="card-element" class="form-label text-lg">Card Details</label>
                        <div id="card-element" class="form-control p-3 rounded-md" style="box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);">
                            <!-- A Stripe Element will be inserted here. -->
                        </div>
                    </div>
                    <div id="card-errors" role="alert" class="text-danger mb-3"></div>
                    <div class="d-grid">
                        <button type="submit" 
                                class="btn btn-primary py-3 fw-bold rounded-pill shadow-sm">
                            Submit Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <script>
        var stripe = Stripe('pk_test_51QZqxJIoDLHFiZtIF9PZS8jpREZ0wQ2BQPQoDTfIHAz0e6yF633mSPOCWT3sCiLL6VV3LfRPfiI5ibhRl2d7w2Cv00ezuzp8OF'); // Your Stripe Public Key
        var elements = stripe.elements();

        var style = {
            base: {
                color: "#32325d",
                lineHeight: "18px",
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: "antialiased",
                fontSize: "16px",
                "::placeholder": {
                    color: "#aab7c4"
                }
            },
            invalid: {
                color: "#fa755a",
                iconColor: "#fa755a"
            }
        };

        var card = elements.create("card", { style: style });
        card.mount("#card-element");

        // Handle form submission
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    // Display error.message in your UI
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Send the token to your server
                    var token = result.token.id;
                    var hiddenTokenInput = document.createElement('input');
                    hiddenTokenInput.setAttribute('type', 'hidden');
                    hiddenTokenInput.setAttribute('name', 'stripeToken');
                    hiddenTokenInput.setAttribute('value', token);
                    form.appendChild(hiddenTokenInput);
                    form.submit();
                }
            });
        });
    </script>



    <!-- Services Section -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="text-primary mb-4">Our Healthcare Services</h2>
            <p class="text-muted">Explore a wide range of specialized healthcare services designed for you.</p>

            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="service-card bg-white p-4 rounded shadow-lg">
                        <i class="fas fa-ambulance fa-2x text-primary mb-3"></i>
                        <h4>Emergency Care</h4>
                        <p>24/7 emergency support and care for urgent needs.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="service-card bg-white p-4 rounded shadow-lg">
                        <i class="fas fa-heartbeat fa-2x text-primary mb-3"></i>
                        <h4>Cardiology</h4>
                        <p>Advanced heart care services with expert cardiologists.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="service-card bg-white p-4 rounded shadow-lg">
                        <i class="fas fa-brain fa-2x text-primary mb-3"></i>
                        <h4>Neurology</h4>
                        <p>Comprehensive care for neurological disorders.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="service-card bg-white p-4 rounded shadow-lg">
                        <i class="fas fa-user-md fa-2x text-primary mb-3"></i>
                        <h4>Primary Care</h4>
                        <p>Personalized treatment plans for routine check-ups.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
<!-- About Us Section -->
<section class="py-5 bg-white">
    <div class="container text-center">
        <h2 class="text-primary mb-4">About Us</h2>
        <p class="text-muted">At HealthCare+, we are committed to providing exceptional healthcare services tailored to your needs. With a team of experienced professionals and state-of-the-art facilities, we strive to deliver excellence in every aspect of care.</p>
        <p class="text-muted">Our mission is to improve lives by offering compassionate and high-quality medical services. From routine check-ups to advanced treatments, we are here to support you on your journey to better health.</p>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="bg-light p-4 rounded shadow">
                    <i class="fas fa-user-md fa-3x text-primary mb-3"></i>
                    <h4>Expert Team</h4>
                    <p>Highly qualified doctors and medical staff.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light p-4 rounded shadow">
                    <i class="fas fa-hospital-alt fa-3x text-primary mb-3"></i>
                    <h4>Advanced Facilities</h4>
                    <p>Modern equipment and state-of-the-art facilities.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light p-4 rounded shadow">
                    <i class="fas fa-hand-holding-heart fa-3x text-primary mb-3"></i>
                    <h4>Compassionate Care</h4>
                    <p>Personalized treatment and patient-centered approach.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Us Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-primary text-center mb-4">Contact Us</h2>
        <p class="text-center text-muted">We’d love to hear from you! Reach out to us for any inquiries, feedback, or support.</p>
        <div class="row mt-4">
            <div class="col-md-1"></div>
            <div class="col-md-4">
                <div class="bg-white p-4 rounded shadow">
                    <h4 class="text-primary">Our Address</h4>
                    <p>123 Healthcare Lane<br>Cityville, Healthstate 45678</p>
                    <h4 class="text-primary">Call Us</h4>
                    <p><a href="tel:+1234567890" class="text-primary text-decoration-none">+1 (234) 567-890</a></p>
                    <h4 class="text-primary">Email Us</h4>
                    <p><a href="mailto:support@healthcareplus.com" class="text-primary text-decoration-none">support@healthcareplus.com</a></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bg-white p-4 rounded shadow">
                    <h4 class="text-primary">Send Us a Message</h4>
                    <form action="#" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Your Email</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Your Message</label>
                            <textarea id="message" name="message" rows="4" class="form-control" placeholder="Enter your message" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Footer -->
    <footer class="py-4">
        <div class="container text-center">
            <p>&copy; 2024 HealthCare+. All rights reserved.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
