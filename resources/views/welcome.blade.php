<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Doctor Appointment System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Hero Section */
        .hero {
            background-image: url('https://via.placeholder.com/1200x500?text=Healthcare+Banner');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
            text-align: center;
            box-shadow: rgba(0, 0, 0, 0.2) 0px 0px 15px;
        }

        /* Grid layout for the cards */
        .doctors-list {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            /* 4 cards per row */
            gap: 20px;
            padding: 20px;
            margin-top: 50px;
        }

        /* Doctor card styling */
        .doctor-card {
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            text-align: center;
        }

        .doctor-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .doctor-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .doctor-card h3 {
            margin-top: 15px;
            font-size: 1.5em;
            color: #333;
        }

        .doctor-card p {
            margin: 5px 0;
            color: #555;
        }

        .doctor-card strong {
            color: #333;
        }

        /* Call to Action Button */
        .cta-btn {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .cta-btn:hover {
            background-color: #0056b3;
        }

        /* Responsive layout for smaller screens */
        @media (max-width: 1024px) {
            .doctors-list {
                grid-template-columns: repeat(3, 1fr);
                /* 3 cards per row */
            }
        }

        @media (max-width: 768px) {
            .doctors-list {
                grid-template-columns: repeat(2, 1fr);
                /* 2 cards per row */
            }
        }

        @media (max-width: 480px) {
            .doctors-list {
                grid-template-columns: 1fr;
                /* 1 card per row */
            }
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
                <a href="#" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-400">Sign up</a>
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

    <!-- Healthcare Services Section -->
    <section class="py-16 bg-blue-50">
        <div class="container mx-auto text-center px-4">
            <h2 class="text-3xl font-semibold text-blue-700">Our Healthcare Service</h2>
            <p class="text-gray-600 mt-4 mb-12">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>

            <!-- Service Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Emergency Department Card -->
                <div class="service-card bg-white p-6 rounded-lg shadow-lg hover:shadow-xl">
                    <div class="text-4xl text-blue-600 mb-4">
                        <i class="fas fa-hospital-alt"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-blue-700 mb-2">Emergency Department</h3>
                    <p class="text-gray-600">If you use this site regularly and would like to...</p>
                </div>

                <!-- Pediatric Department Card -->
                <div class="service-card bg-white p-6 rounded-lg shadow-lg hover:shadow-xl">
                    <div class="text-4xl text-blue-600 mb-4">
                        <i class="fas fa-child"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-blue-700 mb-2">Pediatric Department</h3>
                    <p class="text-gray-600">If you use this site regularly and would like to...</p>
                </div>

                <!-- Neurology Department Card -->
                <div class="service-card bg-white p-6 rounded-lg shadow-lg hover:shadow-xl">
                    <div class="text-4xl text-blue-600 mb-4">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-blue-700 mb-2">Neurology Department</h3>
                    <p class="text-gray-600">If you use this site regularly and would like to...</p>
                </div>

                <!-- Cardiology Department Card -->
                <div class="service-card bg-white p-6 rounded-lg shadow-lg hover:shadow-xl">
                    <div class="text-4xl text-blue-600 mb-4">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-blue-700 mb-2">Cardiology Department</h3>
                    <p class="text-gray-600">If you use this site regularly and would like to...</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white py-6 mt-16">
        <div class="container mx-auto text-center">
            <p class="text-gray-500">HealthCare+. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>