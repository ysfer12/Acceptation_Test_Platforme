<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portail de Candidature</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.3/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'bounce-slow': 'bounce 3s infinite',
                        'float': 'float 3s ease-in-out infinite',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'slide-right': 'slideRight 0.5s ease-out',
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
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
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .clip-path-wave {
                clip-path: polygon(0 0, 100% 0, 100% 85%, 75% 85%, 50% 100%, 25% 85%, 0 85%);
            }
        }
    </style>
</head>
<body class="font-sans bg-gray-50 text-gray-800">
<!-- Header Navigation -->
<header class="sticky top-0 z-50 bg-white shadow-sm" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <img class="h-10 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg" alt="Logo">
                <span class="ml-2 text-xl font-semibold text-primary-700">E-Recruitment</span>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex space-x-8">
                <a href="#features" class="text-gray-600 hover:text-primary-600 px-3 py-2 font-medium transition duration-150">Processus</a>
                <a href="#faq" class="text-gray-600 hover:text-primary-600 px-3 py-2 font-medium transition duration-150">FAQ</a>
                <a href="#contact" class="text-gray-600 hover:text-primary-600 px-3 py-2 font-medium transition duration-150">Contact</a>
            </nav>

            <!-- Authentication Buttons -->
            <div class="hidden md:flex items-center space-x-4">
                <a href="/public/login" class="text-primary-600 hover:text-primary-800 px-3 py-2 font-medium transition duration-150">Connexion</a>
                <a href="/public/register" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md font-medium transition duration-150 transform hover:-translate-y-0.5 hover:shadow-md">Inscription</a>
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
            <a href="#faq" class="block px-3 py-2 rounded-md font-medium hover:bg-primary-50 hover:text-primary-600">FAQ</a>
            <a href="#contact" class="block px-3 py-2 rounded-md font-medium hover:bg-primary-50 hover:text-primary-600">Contact</a>
            <div class="mt-4 flex flex-col space-y-2">
                <a href="/login" class="px-3 py-2 rounded-md font-medium text-primary-600 hover:bg-primary-50">Connexion</a>
                <a href="/register" class="bg-primary-600 hover:bg-primary-700 text-white px-3 py-2 rounded-md font-medium text-center">Inscription</a>
            </div>
        </div>
    </div>
</header>

<!-- Hero Section -->
<section class="relative bg-gradient-to-r from-primary-600 to-primary-800 clip-path-wave">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="animate-slide-up">
                <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight">Simplifiez votre processus de candidature</h1>
                <p class="mt-4 text-xl text-primary-100">Une plateforme intuitive pour gérer vos candidatures et évaluations du début à la fin.</p>
                <div class="mt-8 flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="/register" class="px-6 py-3 bg-white text-primary-700 font-medium rounded-lg text-center hover:bg-gray-100 transition duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                        Créer un compte
                    </a>
                    <a href="#features" class="px-6 py-3 border-2 border-white text-white font-medium rounded-lg text-center hover:bg-white/10 transition duration-300">
                        Découvrir le processus
                    </a>
                </div>
            </div>
            <div class="hidden md:block relative">
                <div class="absolute inset-0 bg-white opacity-10 rounded-xl"></div>
                <img src="https://cdn.tailgrids.com/2.0/image/marketing/images/hero/hero-image-01.png" alt="Platform Demo" class="relative z-10 animate-float rounded-xl shadow-2xl">
            </div>
        </div>
    </div>
    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white animate-bounce-slow">
        <a href="#features">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </a>
    </div>
</section>

<!-- Application Process Steps -->
<section id="features" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 animate-fade-in">
            <h2 class="text-3xl font-bold text-gray-900">Processus de Candidature</h2>
            <p class="mt-4 text-xl text-gray-600 max-w-3xl mx-auto">Un parcours simple en 4 étapes pour postuler et évaluer vos compétences</p>
        </div>

        <div class="relative">
            <!-- Progress Bar -->
            <div class="hidden md:block absolute top-24 left-0 w-full h-1 bg-gray-200">
                <div class="absolute top-0 left-0 h-full bg-primary-500 w-1/4" id="progress-bar"></div>
            </div>

            <!-- Steps -->
            <div class="grid md:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="relative flex flex-col items-center text-center group" x-data="{hover: false}" @mouseenter="hover = true" @mouseleave="hover = false">
                    <div class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center mb-4 text-primary-600 border-4 border-white shadow-lg z-10 transition-all duration-300 group-hover:bg-primary-600 group-hover:text-white animate-pulse-slow">
                        <i class="fas fa-user-plus text-xl"></i>
                    </div>
                    <div class="rounded-xl bg-white shadow-lg p-6 hover:shadow-xl transition-all duration-300 h-full border-t-4 border-primary-500 animate-slide-up">
                        <h3 class="text-xl font-semibold mb-2">1. Inscription</h3>
                        <p class="text-gray-600">Créez votre compte avec votre email et mot de passe pour commencer votre parcours.</p>
                        <div class="mt-4 text-primary-600" x-show="hover" x-transition>
                            <i class="fas fa-arrow-right mr-1"></i> Vérification par email
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="relative flex flex-col items-center text-center group" x-data="{hover: false}" @mouseenter="hover = true" @mouseleave="hover = false">
                    <div class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center mb-4 text-primary-600 border-4 border-white shadow-lg z-10 transition-all duration-300 group-hover:bg-primary-600 group-hover:text-white animate-pulse-slow">
                        <i class="fas fa-file-upload text-xl"></i>
                    </div>
                    <div class="rounded-xl bg-white shadow-lg p-6 hover:shadow-xl transition-all duration-300 h-full border-t-4 border-primary-500 animate-slide-up" style="animation-delay: 0.2s">
                        <h3 class="text-xl font-semibold mb-2">2. Documents</h3>
                        <p class="text-gray-600">Téléchargez votre carte d'identité et complétez vos informations personnelles.</p>
                        <div class="mt-4 text-primary-600" x-show="hover" x-transition>
                            <i class="fas fa-arrow-right mr-1"></i> Validation automatique
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="relative flex flex-col items-center text-center group" x-data="{hover: false}" @mouseenter="hover = true" @mouseleave="hover = false">
                    <div class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center mb-4 text-primary-600 border-4 border-white shadow-lg z-10 transition-all duration-300 group-hover:bg-primary-600 group-hover:text-white animate-pulse-slow">
                        <i class="fas fa-tasks text-xl"></i>
                    </div>
                    <div class="rounded-xl bg-white shadow-lg p-6 hover:shadow-xl transition-all duration-300 h-full border-t-4 border-primary-500 animate-slide-up" style="animation-delay: 0.4s">
                        <h3 class="text-xl font-semibold mb-2">3. Quiz En Ligne</h3>
                        <p class="text-gray-600">Passez un quiz chronométré pour évaluer vos connaissances et compétences de base.</p>
                        <div class="mt-4 text-primary-600" x-show="hover" x-transition>
                            <i class="fas fa-arrow-right mr-1"></i> Évaluation automatique
                        </div>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="relative flex flex-col items-center text-center group" x-data="{hover: false}" @mouseenter="hover = true" @mouseleave="hover = false">
                    <div class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center mb-4 text-primary-600 border-4 border-white shadow-lg z-10 transition-all duration-300 group-hover:bg-primary-600 group-hover:text-white animate-pulse-slow">
                        <i class="fas fa-calendar-check text-xl"></i>
                    </div>
                    <div class="rounded-xl bg-white shadow-lg p-6 hover:shadow-xl transition-all duration-300 h-full border-t-4 border-primary-500 animate-slide-up" style="animation-delay: 0.6s">
                        <h3 class="text-xl font-semibold mb-2">4. Test Présentiel</h3>
                        <p class="text-gray-600">Planification automatique d'un entretien présentiel avec notification par email.</p>
                        <div class="mt-4 text-primary-600" x-show="hover" x-transition>
                            <i class="fas fa-arrow-right mr-1"></i> Convocation automatique
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics -->
<section class="py-16 bg-gradient-to-r from-gray-50 to-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-xl shadow-lg text-center animate-slide-up">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-100 text-primary-600 mb-4">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
                <h3 class="text-4xl font-bold text-gray-900" x-data="{ count: 0 }" x-init="setInterval(() => { count = count < 80 ? count + 1 : count }, 20)">
                    <span x-text="count"></span>%
                </h3>
                <p class="mt-2 text-lg text-gray-600">Gain de temps</p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-lg text-center animate-slide-up" style="animation-delay: 0.2s">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-100 text-primary-600 mb-4">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <h3 class="text-4xl font-bold text-gray-900" x-data="{ count: 0 }" x-init="setInterval(() => { count = count < 5000 ? count + 50 : count }, 20)">
                    <span x-text="count.toLocaleString()"></span>+
                </h3>
                <p class="mt-2 text-lg text-gray-600">Candidats évalués</p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-lg text-center animate-slide-up" style="animation-delay: 0.4s">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-100 text-primary-600 mb-4">
                    <i class="fas fa-star text-2xl"></i>
                </div>
                <h3 class="text-4xl font-bold text-gray-900" x-data="{ count: 0 }" x-init="setInterval(() => { count = count < 95 ? count + 1 : count }, 20)">
                    <span x-text="count"></span>%
                </h3>
                <p class="mt-2 text-lg text-gray-600">Taux de satisfaction</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section id="faq" class="py-20 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900">Questions Fréquemment Posées</h2>
            <p class="mt-4 text-xl text-gray-600">Réponses aux questions les plus courantes sur notre processus</p>
        </div>

        <div class="space-y-4" x-data="{activeQuestion: null}">
            <!-- Question 1 -->
            <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
                <button
                    @click="activeQuestion = activeQuestion === 1 ? null : 1"
                    class="flex justify-between items-center w-full px-6 py-4 text-left bg-white hover:bg-gray-50"
                >
                    <span class="text-lg font-medium text-gray-900">Comment fonctionne la vérification de l'email ?</span>
                    <svg :class="{'rotate-180': activeQuestion === 1}" class="w-5 h-5 text-gray-500 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="activeQuestion === 1" x-collapse x-cloak class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <p class="text-gray-700">Après votre inscription, un email de confirmation sera envoyé à l'adresse que vous avez fournie. Cliquez sur le lien dans cet email pour vérifier votre compte et activer votre accès à la plateforme.</p>
                </div>
            </div>

            <!-- Question 2 -->
            <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
                <button
                    @click="activeQuestion = activeQuestion === 2 ? null : 2"
                    class="flex justify-between items-center w-full px-6 py-4 text-left bg-white hover:bg-gray-50"
                >
                    <span class="text-lg font-medium text-gray-900">Quels formats de documents sont acceptés ?</span>
                    <svg :class="{'rotate-180': activeQuestion === 2}" class="w-5 h-5 text-gray-500 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="activeQuestion === 2" x-collapse x-cloak class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <p class="text-gray-700">Nous acceptons les formats JPEG, PNG et PDF pour votre carte d'identité. Assurez-vous que le document est bien lisible et que toutes les informations sont clairement visibles.</p>
                </div>
            </div>

            <!-- Question 3 -->
            <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
                <button
                    @click="activeQuestion = activeQuestion === 3 ? null : 3"
                    class="flex justify-between items-center w-full px-6 py-4 text-left bg-white hover:bg-gray-50"
                >
                    <span class="text-lg font-medium text-gray-900">Comment se déroule le quiz en ligne ?</span>
                    <svg :class="{'rotate-180': activeQuestion === 3}" class="w-5 h-5 text-gray-500 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="activeQuestion === 3" x-collapse x-cloak class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <p class="text-gray-700">Le quiz est disponible une fois vos documents validés. Il contient des questions à choix multiples avec un temps limité. Votre score est calculé automatiquement et détermine si vous passez à l'étape suivante du processus de recrutement.</p>
                </div>
            </div>

            <!-- Question 4 -->
            <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
                <button
                    @click="activeQuestion = activeQuestion === 4 ? null : 4"
                    class="flex justify-between items-center w-full px-6 py-4 text-left bg-white hover:bg-gray-50"
                >
                    <span class="text-lg font-medium text-gray-900">Comment est planifié le test présentiel ?</span>
                    <svg :class="{'rotate-180': activeQuestion === 4}" class="w-5 h-5 text-gray-500 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="activeQuestion === 4" x-collapse x-cloak class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <p class="text-gray-700">Si vous réussissez le quiz en ligne, le système planifie automatiquement un entretien présentiel en fonction des disponibilités du personnel. Vous recevrez une convocation par email avec la date, l'heure et le lieu précis.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-primary-600">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white animate-slide-up">Prêt à commencer votre processus de candidature ?</h2>
        <p class="mt-4 text-xl text-primary-100 animate-slide-up" style="animation-delay: 0.2s">Créez votre compte dès maintenant et rejoignez des milliers de candidats qui ont simplifié leur processus de recrutement.</p>
        <a href="/register" class="mt-8 inline-block px-8 py-4 bg-white text-primary-700 font-medium rounded-lg shadow-lg hover:bg-gray-100 transition duration-300 transform hover:-translate-y-1 animate-slide-up" style="animation-delay: 0.4s">
            Commencer maintenant
            <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>
</section>

<!-- Footer -->
<footer id="contact" class="bg-gray-900 text-white pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-8 mb-8">
            <div>
                <div class="flex items-center mb-4">
                    <img class="h-10 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-indigo-500.svg" alt="Logo">
                    <span class="ml-2 text-xl font-semibold text-white">E-Recruitment</span>
                </div>
                <p class="text-gray-400 mb-4">Plateforme innovante pour simplifier et automatiser votre processus de recrutement.</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition duration-150">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-150">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-150">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">Navigation</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 hover:text-white transition duration-150">Accueil</a></li>
                    <li><a href="#features" class="text-gray-400 hover:text-white transition duration-150">Processus</a></li>
                    <li><a href="#faq" class="text-gray-400 hover:text-white transition duration-150">FAQ</a></li>
                    <li><a href="#contact" class="text-gray-400 hover:text-white transition duration-150">Contact</a></li>
                </ul>
            </div>
             <div>
                <h3 class="text-lg font-semibold mb-4">Services</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 hover:text-white transition duration-150">Création de comptes</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition duration-150">Gestion des candidatures</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition duration-150">Analyse de données</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition duration-150">Intégration avec votre CMS</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
