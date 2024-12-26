<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Doctor Appointment System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        footer {
            background-color: #0077ff;
            color: #fff;
        }

        footer p {
            margin: 0;
        }

        .service-card:hover {
            transform: scale(1.05);
            transition: 0.3s;
        }
    </style>
</head>

<body class="bg-blue-50">

    <!-- Header -->
    <header class="bg-blue-600 shadow">
        <div class="container mx-auto px-4 py-6 flex justify-between items-center">
            <!-- Logo and Brand Name -->
            <div class="flex items-center">
                <img src="image/logo.jpg" alt="Doctor Logo" class="w-10 h-8 mr-2">
                <a href="/" class="text-2xl font-bold text-white">HealthCare+</a>
            </div>

            <!-- Navigation Links -->
            <div class="flex items-center space-x-4">
                <a href="/admin/login" class="text-white font-semibold">Home</a>
                <a href="/admin/login" class="text-white font-semibold">About</a>
                <a href="/admin/login" class="text-white font-semibold">Contact</a>
                <a href="/admin/login" class="text-white font-semibold">Service</a>
                <a href="/admin/login" class="text-white font-semibold">Log in</a>
                <a href="admin/register" class="text-white font-semibold">Sign up</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-white flex items-center justify-center mt-0">
        <div class="pl-3 container mx-auto flex flex-col lg:flex-row items-center">
            <!-- Left Side: Text Content -->
            <div class="lg:w-1/2 text-center lg:text-left">
                <a href="#" class="inline-block bg-blue-200 py-3 px-6 rounded-lg hover:bg-green-500 transition duration-300">List of Doctors</a>
                <br><br>
                <h1 class="text-4xl font-bold text-blue-700">Your Partner In Health and<br> Wellness</h1>
                <p class="text-lg text-gray-600 mt-4">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
            </div>

            <!-- Right Side: Image -->
            <div class="lg:w-1/2 mt-8 lg:mt-0">
                <img src="https://plus.unsplash.com/premium_photo-1733317206347-e6eeea03bf41?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8ZG9jdG9yJTIwYW5kJTIwaG9zcGl0YWx8ZW58MHx8MHx8fDA%3D" alt="Healthcare Image" class="w-full h-auto rounded-lg shadow-lg">
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-12 bg-blue-50">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-bold text-blue-700 mb-6">Our Healthcare Services</h2>
            <p class="text-gray-600 mb-8">Explore a wide range of specialized healthcare services designed for you.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="service-card bg-white p-6 rounded-lg shadow hover:shadow-lg">
                    <i class="fas fa-ambulance fa-3x text-blue-600 mb-4"></i>
                    <h4 class="text-xl font-semibold text-gray-800">Emergency Care</h4>
                    <p class="text-gray-600 mt-2">24/7 emergency support and care for urgent needs.</p>
                </div>
                <div class="service-card bg-white p-6 rounded-lg shadow hover:shadow-lg">
                    <i class="fas fa-heartbeat fa-3x text-blue-600 mb-4"></i>
                    <h4 class="text-xl font-semibold text-gray-800">Cardiology</h4>
                    <p class="text-gray-600 mt-2">Advanced heart care services with expert cardiologists.</p>
                </div>
                <div class="service-card bg-white p-6 rounded-lg shadow hover:shadow-lg">
                    <i class="fas fa-brain fa-3x text-blue-600 mb-4"></i>
                    <h4 class="text-xl font-semibold text-gray-800">Neurology</h4>
                    <p class="text-gray-600 mt-2">Comprehensive care for neurological disorders.</p>
                </div>
                <div class="service-card bg-white p-6 rounded-lg shadow hover:shadow-lg">
                    <i class="fas fa-user-md fa-3x text-blue-600 mb-4"></i>
                    <h4 class="text-xl font-semibold text-gray-800">Primary Care</h4>
                    <p class="text-gray-600 mt-2">Personalized treatment plans for routine check-ups.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-bold text-blue-700 mb-6">About Us</h2>
            <p class="text-gray-600 mb-8">At HealthCare+, we are committed to providing exceptional healthcare services tailored to your needs. With a team of experienced professionals and state-of-the-art facilities, we strive to deliver excellence in every aspect of care.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-blue-50 p-6 rounded-lg shadow">
                    <i class="fas fa-user-md fa-3x text-blue-600 mb-4"></i>
                    <h4 class="text-xl font-semibold text-gray-800">Expert Team</h4>
                    <p class="text-gray-600 mt-2">Highly qualified doctors and medical staff.</p>
                </div>
                <div class="bg-blue-50 p-6 rounded-lg shadow">
                    <i class="fas fa-hospital-alt fa-3x text-blue-600 mb-4"></i>
                    <h4 class="text-xl font-semibold text-gray-800">Advanced Facilities</h4>
                    <p class="text-gray-600 mt-2">Modern equipment and state-of-the-art facilities.</p>
                </div>
                <div class="bg-blue-50 p-6 rounded-lg shadow">
                    <i class="fas fa-hand-holding-heart fa-3x text-blue-600 mb-4"></i>
                    <h4 class="text-xl font-semibold text-gray-800">Compassionate Care</h4>
                    <p class="text-gray-600 mt-2">Personalized treatment and patient-centered approach.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Us Section -->
    <section class="py-12 bg-blue-50">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-bold text-blue-700 mb-6">Contact Us</h2>
            <p class="text-gray-600 mb-8">We’d love to hear from you! Reach out to us for any inquiries, feedback, or support.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h4 class="text-xl font-semibold text-blue-700 mb-4">Our Address</h4>
                    <p class="text-gray-600">123 Healthcare Lane<br>Cityville, Healthstate 45678</p>

                    <h4 class="text-xl font-semibold text-blue-700 mt-6">Call Us</h4>
                    <p class="text-gray-600"><a href="tel:+1234567890" class="text-blue-600 hover:underline">+1 (234) 567-890</a></p>

                    <h4 class="text-xl font-semibold text-blue-700 mt-6">Email Us</h4>
                    <p class="text-gray-600"><a href="mailto:support@healthcareplus.com" class="text-blue-600 hover:underline">support@healthcareplus.com</a></p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <h4 class="text-xl font-semibold text-blue-700 mb-4">Send Us a Message</h4>
                    <form action="#" method="POST">
                        <div class="mb-4">
                            <label for="name" class="block text-gray-600">Your Name</label>
                            <input type="text" id="name" name="name" class="w-full border border-gray-300 p-2 rounded" placeholder="Enter your name" required>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-gray-600">Your Email</label>
                            <input type="email" id="email" name="email" class="w-full border border-gray-300 p-2 rounded" placeholder="Enter your email" required>
                        </div>

                        <div class="mb-4">
                            <label for="message" class="block text-gray-600">Your Message</label>
                            <textarea id="message" name="message" rows="4" class="w-full border border-gray-300 p-2 rounded" placeholder="Enter your message" required></textarea>
                        </div>

                        <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 HealthCare+. All rights reserved.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
