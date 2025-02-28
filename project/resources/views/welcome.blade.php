<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portail de Candidature - E-Recruitment</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.3/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                            950: '#172554',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Poppins', 'sans-serif'],
                    },
                    animation: {
                        'bounce-slow': 'bounce 3s infinite',
                        'float': 'float 3s ease-in-out infinite',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'slide-right': 'slideRight 0.5s ease-out',
                        'slide-left': 'slideLeft 0.5s ease-out',
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'spin-slow': 'spin 8s linear infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        slideRight: {
                            '0%': { transform: 'translateX(-20px)', opacity: '0' },
                            '100%': { transform: 'translateX(0)', opacity: '1' },
                        },
                        slideLeft: {
                            '0%': { transform: 'translateX(20px)', opacity: '0' },
                            '100%': { transform: 'translateX(0)', opacity: '1' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                    },
                    boxShadow: {
                        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                        'inner-soft': 'inset 0 2px 15px -3px rgba(0, 0, 0, 0.1)',
                    },
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .clip-path-wave {
                clip-path: polygon(0 0, 100% 0, 100% 85%, 75% 85%, 50% 100%, 25% 85%, 0 85%);
            }
            .clip-path-diagonal {
                clip-path: polygon(0 0, 100% 0, 100% 90%, 0% 100%);
            }
            .text-gradient {
                @apply bg-clip-text text-transparent bg-gradient-to-r from-primary-600 to-primary-800;
            }
        }
    </style>
</head>
<body class="font-sans bg-gray-50 text-gray-800 overflow-x-hidden">
<!-- Announcement Bar -->
<div class="bg-primary-900 text-white py-2 px-4 text-center text-sm">
    <div class="max-w-7xl mx-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center">
            <span class="mr-2">🎉</span>
            <span class="font-medium">Nouvelle version de la plateforme disponible!</span>
            <span class="mx-2">-</span>
            <a href="#" class="underline hover:text-primary-200 transition-colors">Découvrir les nouveautés</a>
            <button @click="show = false" class="ml-4 text-white/80 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Header Navigation -->
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm shadow-sm transition-all duration-300" x-data="{ mobileMenuOpen: false, scrolled: false }" x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20" :class="{ 'h-16': scrolled }">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <span class="ml-2 text-xl font-heading font-bold text-primary-700">E-Recruitment</span>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex space-x-8">
                <a href="#features" class="text-gray-600 hover:text-primary-600 px-3 py-2 font-medium transition duration-150 relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-primary-600 after:transition-all after:duration-300">Processus</a>
                <a href="#benefits" class="text-gray-600 hover:text-primary-600 px-3 py-2 font-medium transition duration-150 relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-primary-600 after:transition-all after:duration-300">Avantages</a>
                <a href="#testimonials" class="text-gray-600 hover:text-primary-600 px-3 py-2 font-medium transition duration-150 relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-primary-600 after:transition-all after:duration-300">Témoignages</a>
                <a href="#faq" class="text-gray-600 hover:text-primary-600 px-3 py-2 font-medium transition duration-150 relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-primary-600 after:transition-all after:duration-300">FAQ</a>
                <a href="#contact" class="text-gray-600 hover:text-primary-600 px-3 py-2 font-medium transition duration-150 relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-primary-600 after:transition-all after:duration-300">Contact</a>
            </nav>

            <!-- Authentication Buttons -->
            <div class="hidden md:flex items-center space-x-4">
                <a href="/public/login" class="text-primary-600 hover:text-primary-800 px-3 py-2 font-medium transition duration-150">Connexion</a>
                <a href="/public/register" class="bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-lg font-medium transition duration-300 transform hover:-translate-y-0.5 hover:shadow-lg flex items-center group">
                    <span>Inscription</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 group-hover:translate-x-1 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-primary-600 focus:outline-none">
                    <svg class="h-6 w-6" x-show="!mobileMenuOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6" x-show="mobileMenuOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div class="md:hidden" x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" style="display: none;">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white shadow-lg rounded-b-lg">
            <a href="#features" class="block px-3 py-2 rounded-md font-medium hover:bg-primary-50 hover:text-primary-600">Processus</a>
            <a href="#benefits" class="block px-3 py-2 rounded-md font-medium hover:bg-primary-50 hover:text-primary-600">Avantages</a>
            <a href="#testimonials" class="block px-3 py-2 rounded-md font-medium hover:bg-primary-50 hover:text-primary-600">Témoignages</a>
            <a href="#faq" class="block px-3 py-2 rounded-md font-medium hover:bg-primary-50 hover:text-primary-600">FAQ</a>
            <a href="#contact" class="block px-3 py-2 rounded-md font-medium hover:bg-primary-50 hover:text-primary-600">Contact</a>
            <div class="mt-4 flex flex-col space-y-2">
                <a href="/login" class="px-3 py-2 rounded-md font-medium text-primary-600 hover:bg-primary-50">Connexion</a>
                <a href="/register" class="bg-primary-600 hover:bg-primary-700 text-white px-3 py-2 rounded-md font-medium text-center flex items-center justify-center">
                    <span>Inscription</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-r from-primary-700 to-primary-900">
    <!-- Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-spin-slow"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-primary-600 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-spin-slow" style="animation-direction: reverse;"></div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32 relative z-10">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="animate-slide-up">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-heading font-bold text-white leading-tight">Simplifiez votre <span class="text-primary-200">processus de candidature</span></h1>
                <p class="mt-6 text-xl text-primary-100 max-w-lg">Une plateforme intuitive pour gérer vos candidatures et évaluations du début à la fin, avec une expérience utilisateur optimisée.</p>
                <div class="mt-8 flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="/register" class="px-8 py-4 bg-white text-primary-700 font-medium rounded-lg text-center hover:bg-gray-100 transition duration-300 transform hover:-translate-y-1 hover:shadow-xl flex items-center justify-center">
                        <span>Créer un compte</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#features" class="px-8 py-4 border-2 border-white text-white font-medium rounded-lg text-center hover:bg-white/10 transition duration-300 flex items-center justify-center group">
                        <span>Découvrir le processus</span>
                        <svg class="w-5 h-5 ml-2 transform group-hover:translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="mt-12">
                    <p class="text-primary-200 font-medium text-sm uppercase tracking-wide mb-4">Ils nous font confiance</p>
                    <div class="flex flex-wrap items-center space-x-8">
                        <img src="https://res.cloudinary.com/comparadise/image/upload/v1665397291/vehiculier/toyota.png" alt="Partner" class="h-8 opacity-75 grayscale hover:grayscale-0 hover:opacity-100 transition-all">
                        <img src="https://res.cloudinary.com/comparadise/image/upload/v1665397292/vehiculier/bmw.png" alt="Partner" class="h-8 opacity-75 grayscale hover:grayscale-0 hover:opacity-100 transition-all">
                        <img src="https://res.cloudinary.com/comparadise/image/upload/v1665397291/vehiculier/mercedes.png" alt="Partner" class="h-8 opacity-75 grayscale hover:grayscale-0 hover:opacity-100 transition-all">
                    </div>
                </div>
            </div>

            <!-- Hero Image -->
            <div class="hidden md:block relative">
                <div class="absolute inset-0 bg-white opacity-10 rounded-xl"></div>
                <img src="https://cdn.tailgrids.com/2.0/image/marketing/images/hero/hero-image-01.png" alt="Platform Demo" class="relative z-10 animate-float rounded-xl shadow-2xl border border-white/20">

                <!-- Floating Elements -->
                <div class="absolute -right-8 top-1/4 bg-white p-4 rounded-lg shadow-xl animate-float" style="animation-delay: 0.5s;">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium">Quiz validé</div>
                            <div class="text-xs text-gray-500">il y a 5 minutes</div>
                        </div>
                    </div>
                </div>

                <div class="absolute -left-8 bottom-1/4 bg-white p-4 rounded-lg shadow-xl animate-float" style="animation-delay: 1s;">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium">Entretien planifié</div>
                            <div class="text-xs text-gray-500">Aujourd'hui</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave -->
    <div class="absolute bottom-0 left-0 right-0 h-16 bg-white w-full -mb-0.5" style="clip-path: polygon(0 100%, 100% 100%, 100% 0, 75% 50%, 50% 0, 25% 50%, 0 0);"></div>
</section>

<!-- Partners Section -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-xl text-gray-600 font-medium">Reconnu et utilisé par des institutions de premier plan</h2>
        </div>
        <div class="mt-8 flex flex-wrap justify-center gap-x-16 gap-y-8">
            <img src="https://res.cloudinary.com/comparadise/image/upload/v1665397291/vehiculier/mercedes.png" alt="Partner Logo" class="h-12 object-contain opacity-60 hover:opacity-100 transition-opacity">
            <img src="https://res.cloudinary.com/comparadise/image/upload/v1665397292/vehiculier/bmw.png" alt="Partner Logo" class="h-12 object-contain opacity-60 hover:opacity-100 transition-opacity">
            <img src="https://res.cloudinary.com/comparadise/image/upload/v1665397291/vehiculier/jaguar.png" alt="Partner Logo" class="h-12 object-contain opacity-60 hover:opacity-100 transition-opacity">
            <img src="https://res.cloudinary.com/comparadise/image/upload/v1665397291/vehiculier/porsche.png" alt="Partner Logo" class="h-12 object-contain opacity-60 hover:opacity-100 transition-opacity">
            <img src="https://res.cloudinary.com/comparadise/image/upload/v1665397291/vehiculier/audi.png" alt="Partner Logo" class="h-12 object-contain opacity-60 hover:opacity-100 transition-opacity">
        </div>
    </div>
</section>

<!-- Application Process Steps -->
<section id="features" class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
            <span class="inline-block px-3 py-1 text-xs font-medium text-primary-700 bg-primary-100 rounded-full mb-4">PROCESSUS SIMPLIFIÉ</span>
            <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900">Processus de Candidature</h2>
            <p class="mt-4 text-xl text-gray-600 max-w-3xl mx-auto">Un parcours simple en 4 étapes pour postuler et évaluer vos compétences</p>
        </div>

        <div class="relative">
            <!-- Progress Bar -->
            <div class="hidden md:block absolute top-24 left-0 w-full h-1 bg-gray-200">
                <div class="absolute top-0 left-0 h-full bg-primary-500 w-1/4" id="progress-bar"></div>
            </div>

            <!-- Steps -->
            <div class="grid md:grid-cols-4 gap-8 relative z-10">
                <!-- Step 1 -->
                <div class="relative flex flex-col items-center text-center group" x-data="{hover: false}" @mouseenter="hover = true" @mouseleave="hover = false">
                    <div class="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center mb-4 text-primary-600 border-4 border-white shadow-lg z-10 transition-all duration-300 group-hover:bg-primary-600 group-hover:text-white animate-pulse-slow">
                        <i class="fas fa-user-plus text-2xl"></i>
                    </div>
                    <div class="rounded-xl bg-white shadow-soft p-8 hover:shadow-xl transition-all duration-300 h-full border-b-4 border-primary-500 animate-slide-up">
                        <h3 class="text-xl font-bold mb-3">1. Inscription</h3>
                        <p class="text-gray-600">Créez votre compte avec votre email et mot de passe pour commencer votre parcours.</p>
                        <div class="mt-4 text-primary-600 opacity-0 group-hover:opacity-100 transition-opacity" x-show="hover" x-transition>
                            <div class="flex items-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Vérification par email</span>
                            </div>
                            <div class="flex items-center text-sm mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Validation instantanée</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="relative flex flex-col items-center text-center group" x-data="{hover: false}" @mouseenter="hover = true" @mouseleave="hover = false">
                    <div class="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center mb-4 text-primary-600 border-4 border-white shadow-lg z-10 transition-all duration-300 group-hover:bg-primary-600 group-hover:text-white animate-pulse-slow">
                        <i class="fas fa-file-upload text-2xl"></i>
                    </div>
                    <div class="rounded-xl bg-white shadow-soft p-8 hover:shadow-xl transition-all duration-300 h-full border-b-4 border-primary-500 animate-slide-up" style="animation-delay: 0.2s">
                        <h3 class="text-xl font-bold mb-3">2. Documents</h3>
                        <p class="text-gray-600">Téléchargez votre carte d'identité et complétez vos informations personnelles.</p>
                        <div class="mt-4 text-primary-600 opacity-0 group-hover:opacity-100 transition-opacity" x-show="hover" x-transition>
                            <div class="flex items-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Validation automatique</span>
                            </div>
                            <div class="flex items-center text-sm mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Formats JPG, PNG et PDF</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="relative flex flex-col items-center text-center group" x-data="{hover: false}" @mouseenter="hover = true" @mouseleave="hover = false">
                    <div class="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center mb-4 text-primary-600 border-4 border-white shadow-lg z-10 transition-all duration-300 group-hover:bg-primary-600 group-hover:text-white animate-pulse-slow">
                        <i class="fas fa-tasks text-2xl"></i>
                    </div>
                    <div class="rounded-xl bg-white shadow-soft p-8 hover:shadow-xl transition-all duration-300 h-full border-b-4 border-primary-500 animate-slide-up" style="animation-delay: 0.4s">
                        <h3 class="text-xl font-bold mb-3">3. Quiz En Ligne</h3>
                        <p class="text-gray-600">Passez un quiz chronométré pour évaluer vos connaissances et compétences de base.</p>
                        <div class="mt-4 text-primary-600 opacity-0 group-hover:opacity-100 transition-opacity" x-show="hover" x-transition>
                            <div class="flex items-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Évaluation automatique</span>
                            </div>
                            <div class="flex items-center text-sm mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Résultats instantanés</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="relative flex flex-col items-center text-center group" x-data="{hover: false}" @mouseenter="hover = true" @mouseleave="hover = false">
                    <div class="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center mb-4 text-primary-600 border-4 border-white shadow-lg z-10 transition-all duration-300 group-hover:bg-primary-600 group-hover:text-white animate-pulse-slow">
                        <i class="fas fa-calendar-check text-2xl"></i>
                    </div>
                    <div class="rounded-xl bg-white shadow-soft p-8 hover:shadow-xl transition-all duration-300 h-full border-b-4 border-primary-500 animate-slide-up" style="animation-delay: 0.6s">
                        <h3 class="text-xl font-bold mb-3">4. Test Présentiel</h3>
                        <p class="text-gray-600">Planification automatique d'un entretien présentiel avec notification par email.</p>
                        <div class="mt-4 text-primary-600 opacity-0 group-hover:opacity-100 transition-opacity" x-show="hover" x-transition>
                            <div class="flex items-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Convocation automatique</span>
                            </div>
                            <div class="flex items-center text-sm mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Rappels intégrés</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Process CTA -->
        <div class="text-center mt-16">
            <a href="#" class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition duration-300">
                <span>Voir le processus complet</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section id="benefits" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-3 py-1 text-xs font-medium text-primary-700 bg-primary-100 rounded-full mb-4">AVANTAGES CLÉS</span>
            <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900">Pourquoi choisir notre plateforme</h2>
            <p class="mt-4 text-xl text-gray-600 max-w-3xl mx-auto">Découvrez les avantages qui font la différence pour votre processus de recrutement</p>
        </div>

        <div class="grid md:grid-cols-3 gap-10">
            <!-- Benefit 1 -->
            <div class="bg-gray-50 rounded-xl p-8 shadow-soft hover:shadow-lg transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-16 h-16 rounded-lg bg-primary-100 flex items-center justify-center text-primary-600 mb-6">
                    <i class="fas fa-bolt text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Processus Accéléré</h3>
                <p class="text-gray-600">Réduisez le temps de recrutement de 80% grâce à l'automatisation des étapes administratives et d'évaluation.</p>
                <ul class="mt-4 space-y-2">
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-primary-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Vérification automatique des documents</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-primary-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Planification intelligente des entretiens</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-primary-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Évaluation instantanée des compétences</span>
                    </li>
                </ul>
            </div>

            <!-- Benefit 2 -->
            <div class="bg-gray-50 rounded-xl p-8 shadow-soft hover:shadow-lg transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-16 h-16 rounded-lg bg-primary-100 flex items-center justify-center text-primary-600 mb-6">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Amélioration de la Qualité</h3>
                <p class="text-gray-600">Identifiez les meilleurs candidats grâce à un processus d'évaluation standardisé et objectif.</p>
                <ul class="mt-4 space-y-2">
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-primary-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Quiz adaptés à chaque poste</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-primary-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Analyse comparative des candidats</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-primary-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Rapports détaillés sur les compétences</span>
                    </li>
                </ul>
            </div>

            <!-- Benefit 3 -->
            <div class="bg-gray-50 rounded-xl p-8 shadow-soft hover:shadow-lg transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-16 h-16 rounded-lg bg-primary-100 flex items-center justify-center text-primary-600 mb-6">
                    <i class="fas fa-shield-alt text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Sécurité & Conformité</h3>
                <p class="text-gray-600">Protection des données personnelles et conformité aux réglementations en vigueur (RGPD).</p>
                <ul class="mt-4 space-y-2">
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-primary-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Chiffrement de bout en bout</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-primary-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Gestion des consentements</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-primary-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Purge automatique des données obsolètes</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- Statistics Section -->
<section class="py-20 bg-gradient-to-r from-primary-700 to-primary-900 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none opacity-10">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl animate-spin-slow"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-white rounded-full mix-blend-overlay filter blur-3xl opacity-20 animate-spin-slow" style="animation-direction: reverse;"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-heading font-bold text-white">Des résultats tangibles</h2>
            <p class="mt-4 text-xl text-primary-100 max-w-3xl mx-auto">Les chiffres qui prouvent l'efficacité de notre plateforme</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white/10 backdrop-blur-md p-10 rounded-xl shadow-lg text-center border border-white/20 transform hover:scale-105 transition-all">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/20 text-white mb-6">
                    <i class="fas fa-clock text-3xl"></i>
                </div>
                <h3 class="text-5xl font-bold text-white" x-data="{ count: 0 }" x-init="setInterval(() => { count = count < 80 ? count + 1 : count }, 20)">
                    <span x-text="count"></span>%
                </h3>
                <p class="mt-3 text-xl text-primary-100">Gain de temps</p>
                <p class="mt-2 text-sm text-primary-200">Réduction du temps de traitement des candidatures</p>
            </div>

            <div class="bg-white/10 backdrop-blur-md p-10 rounded-xl shadow-lg text-center border border-white/20 transform hover:scale-105 transition-all">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/20 text-white mb-6">
                    <i class="fas fa-users text-3xl"></i>
                </div>
                <h3 class="text-5xl font-bold text-white" x-data="{ count: 0 }" x-init="setInterval(() => { count = count < 5000 ? count + 50 : count }, 20)">
                    <span x-text="count.toLocaleString()"></span>+
                </h3>
                <p class="mt-3 text-xl text-primary-100">Candidats évalués</p>
                <p class="mt-2 text-sm text-primary-200">Des milliers de dossiers traités efficacement</p>
            </div>

            <div class="bg-white/10 backdrop-blur-md p-10 rounded-xl shadow-lg text-center border border-white/20 transform hover:scale-105 transition-all">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/20 text-white mb-6">
                    <i class="fas fa-star text-3xl"></i>
                </div>
                <h3 class="text-5xl font-bold text-white" x-data="{ count: 0 }" x-init="setInterval(() => { count = count < 95 ? count + 1 : count }, 20)">
                    <span x-text="count"></span>%
                </h3>
                <p class="mt-3 text-xl text-primary-100">Taux de satisfaction</p>
                <p class="mt-2 text-sm text-primary-200">Utilisateurs pleinement satisfaits de l'expérience</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section id="testimonials" class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-3 py-1 text-xs font-medium text-primary-700 bg-primary-100 rounded-full mb-4">TÉMOIGNAGES</span>
            <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900">Ce que nos utilisateurs disent</h2>
            <p class="mt-4 text-xl text-gray-600 max-w-3xl mx-auto">Découvrez les expériences de nos utilisateurs avec notre plateforme</p>
        </div>

        <!-- Testimonial Cards -->
        <div class="grid md:grid-cols-3 gap-8" x-data="{ activeTab: 1 }">
            <!-- Testimonial 1 -->
            <div class="bg-white rounded-xl p-8 shadow-soft hover:shadow-lg transition-all duration-300 relative">
                <div class="absolute -top-4 left-8 text-primary-500 text-5xl">"</div>
                <div class="pt-4">
                    <p class="text-gray-700 mb-6">La plateforme a complètement transformé notre processus de recrutement. Nous avons réduit de 70% le temps consacré à l'administratif pour nous concentrer sur les entretiens de qualité.</p>
                    <div class="flex items-center">
                        <img src="https://api.placeholder.com/50/50" alt="Portrait" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-bold text-gray-900">Marie Dupont</h4>
                            <p class="text-gray-600 text-sm">DRH, Entreprise ABC</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="bg-white rounded-xl p-8 shadow-soft hover:shadow-lg transition-all duration-300 relative">
                <div class="absolute -top-4 left-8 text-primary-500 text-5xl">"</div>
                <div class="pt-4">
                    <p class="text-gray-700 mb-6">En tant que candidat, j'ai trouvé le processus incroyablement fluide. Recevoir des réponses rapides à chaque étape m'a permis de rester engagé et motivé tout au long du processus.</p>
                    <div class="flex items-center">
                        <img src="https://api.placeholder.com/50/50" alt="Portrait" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-bold text-gray-900">Thomas Martin</h4>
                            <p class="text-gray-600 text-sm">Développeur Web</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="bg-white rounded-xl p-8 shadow-soft hover:shadow-lg transition-all duration-300 relative">
                <div class="absolute -top-4 left-8 text-primary-500 text-5xl">"</div>
                <div class="pt-4">
                    <p class="text-gray-700 mb-6">L'intégration de quiz personnalisés nous a permis d'identifier des talents que nous aurions probablement manqués avec notre ancien processus. Un vrai game-changer pour notre équipe.</p>
                    <div class="flex items-center">
                        <img src="https://api.placeholder.com/50/50" alt="Portrait" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-bold text-gray-900">Sophie Leroux</h4>
                            <p class="text-gray-600 text-sm">Responsable Recrutement, Tech Innovate</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- View All Testimonials Button -->
        <div class="text-center mt-12">
            <a href="#" class="inline-flex items-center text-primary-600 hover:text-primary-800 font-medium transition duration-150">
                <span>Voir tous les témoignages</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    </div>
</section>
</body>
</html>
