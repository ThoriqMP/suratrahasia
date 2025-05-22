<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thoriq Muhammad Pasya - Portfolio</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    @vite('resources/css/app.css')
</head>
<body class="bg-white">
    <!-- Navigation -->
    <nav class="fixed w-full bg-white shadow-md z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-blue-600 animate-fadeIn">
                    <span class="text-blue-900">THORIQ</span>PASYA
                </h1>
                <div class="hidden md:flex space-x-8">
                    <a href="#home" class="text-gray-600 hover:text-blue-600 transition-colors duration-300">Home</a>
                    <a href="#projects" class="text-gray-600 hover:text-blue-600 transition-colors duration-300">Projects</a>
                    <a href="#skills" class="text-gray-600 hover:text-blue-600 transition-colors duration-300">Skills</a>
                    <a href="#contact" class="text-gray-600 hover:text-blue-600 transition-colors duration-300">Contact</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="pt-32 pb-20 px-6">
        <div class="container mx-auto flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 animate-slideInLeft">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    Hi, I'm <span class="text-blue-600">Thoriq Muhammad Pasya</span>
                </h2>
                <p class="text-gray-600 text-lg mb-8">Fullstack Developer & UI/UX Enthusiast</p>
                <div class="flex space-x-4">
                    <a href="#contact" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors shadow-lg">
                        Hire Me
                    </a>
                    <a href="#projects" class="border-2 border-blue-600 text-blue-600 px-6 py-3 rounded-lg hover:bg-blue-50 transition-colors">
                        View Projects
                    </a>
                </div>
            </div>
            <div class="md:w-1/2 mt-12 md:mt-0 animate-float">
                <img src="profile.jpg" 
                     class="rounded-2xl w-80 h-80 mx-auto shadow-xl border-4 border-blue-100 object-cover">
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="py-20 bg-blue-50 px-6">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold text-gray-800 mb-12 text-center">Featured Projects</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="h-48 bg-blue-100 rounded-xl mb-4"></div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">E-Commerce Platform</h3>
                    <p class="text-gray-600">Modern shopping platform with AI recommendations</p>
                    <div class="mt-4 flex space-x-2">
                        <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm">React</span>
                        <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm">Node.js</span>
                    </div>
                </div>
                <!-- Tambahkan project lainnya -->
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section id="skills" class="py-20 px-6">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold text-gray-800 mb-12 text-center">Technical Skills</h2>
            <div class="grid grid-cols-3 md:grid-cols-6 gap-8">
                <div class="flex flex-col items-center p-4 hover:transform hover:scale-110 transition-transform">
                    <i class="fab fa-react text-5xl text-blue-600 mb-4"></i>
                    <p class="text-gray-600 font-medium">React</p>
                </div>
                <!-- Tambahkan skill lainnya -->
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-gray-100 px-6">
        <div class="container mx-auto max-w-2xl">
            <h2 class="text-3xl font-bold text-gray-800 mb-12 text-center">Let's Connect</h2>
            <form class="space-y-6 bg-white p-8 rounded-xl shadow-lg">
                <div>
                    <input type="text" placeholder="Your Name" 
                           class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <input type="email" placeholder="Email Address" 
                           class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <textarea rows="5" placeholder="Your Message"
                           class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>
                <button class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    Send Message
                </button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-900 text-white py-8">
        <div class="container mx-auto text-center">
            <p class="mb-4">© 2024 Thoriq Muhammad Pasya. All rights reserved</p>
            <div class="flex justify-center space-x-6">
                <a href="#" class="hover:text-blue-300 transition-colors">
                    <i class="fab fa-linkedin text-2xl"></i>
                </a>
                <a href="#" class="hover:text-blue-300 transition-colors">
                    <i class="fab fa-github text-2xl"></i>
                </a>
                <a href="#" class="hover:text-blue-300 transition-colors">
                    <i class="fab fa-instagram text-2xl"></i>
                </a>
            </div>
        </div>
    </footer>

    @vite('resources/js/app.js')
</body>
</html>