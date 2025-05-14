<footer class="bg-gradient-to-r from-red-600/30 to-white/30 dark:from-violet-900/30 dark:to-white/20 backdrop-blur-sm text-white py-8 mt-12 border-t border-white/10">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <!-- Logo y Título -->
            <div class="mb-6 md:mb-0 flex items-center group">
                <img src="{{ asset('images/logo.png') }}" alt="GAMT Logo" class="h-12 w-auto block dark:hidden transform transition-transform group-hover:scale-110 duration-300">
                <img src="{{ asset('images/bandera.png') }}" alt="GAMT Logo" class="h-12 w-auto hidden dark:block transform transition-transform group-hover:scale-110 duration-300">
                <span class="ml-3 font-bold text-2xl bg-clip-text text-transparent bg-gradient-to-r from-red-400 to-red-600 dark:from-purple-400 dark:to-purple-600">
                    GAMT<span class="text-white">Docs</span>
                </span>
            </div>

            <!-- Información -->
            <div class="text-center md:text-right space-y-2">
                <p class="text-white/90">&copy; {{ date('Y') }} Sistema de Gestión Documental</p>
                <p class="text-sm text-white/70 max-w-md">
                    Desarrollado para optimizar la gestión de documentos del Gobierno Autónomo Municipal de Tomave
                </p>
            </div>
        </div>

        <!-- Enlaces adicionales y redes sociales -->
        <div class="mt-8 pt-6 border-t border-white/10">
            <div class="flex flex-col items-center space-y-6">
               <!-- Enlaces -->
                <div class="flex flex-wrap justify-center gap-6">
                    <!-- Acerca de -->
                    <a href="#" class="relative group text-white/80 hover:text-white transition-colors duration-300">
                        <i class="fas fa-info-circle mr-1"></i> Acerca de
                        <span class="absolute bottom-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-sm rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            Más información sobre nosotros GAMT
                        </span>
                    </a>

                    <!-- Contacto -->
                    <a href="#" class="relative group text-white/80 hover:text-white transition-colors duration-300">
                        <i class="fas fa-envelope mr-1"></i> Contacto
                        <span class="absolute bottom-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-sm rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            Contáctanos para más detalles WhatsApp
                        </span>
                    </a>

                    <!-- Privacidad -->
                    <a href="#" class="relative group text-white/80 hover:text-white transition-colors duration-300">
                        <i class="fas fa-shield-alt mr-1"></i> Privacidad
                        <span class="absolute bottom-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-sm rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            Consulta nuestra política de privacidad dentro las instalaciones
                        </span>
                    </a>

                    <!-- Términos -->
                    <a href="no hay terminos" class="relative group text-white/80 hover:text-white transition-colors duration-300">
                        <i class="fas fa-file-alt mr-1"></i> Términos
                        <span class="absolute bottom-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-sm rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            Lee nuestros términos y condiciones del GAMT
                        </span>
                    </a>
                </div>

                <!-- Redes Sociales -->
                <div class="flex space-x-6 items-center">
                    <!-- Sitio Web -->
                    <a href="https://gamtomave.gob.bo/" class="text-blue-400 hover:text-blue-300 transition-colors transform hover:scale-110 duration-300">
                        <i class="fas fa-globe text-xl"></i>
                    </a>

                    <!-- Facebook -->
                    <a href="https://www.facebook.com/GAMTOMAVE2025" class="text-blue-500 hover:text-blue-400 transition-colors transform hover:scale-110 duration-300">
                        <i class="fab fa-facebook-f text-xl"></i>
                    </a>

                    <!-- Instagram -->
                    <a href="#" class="text-pink-400 hover:text-pink-300 transition-colors transform hover:scale-110 duration-300">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>

                    <!-- X (Twitter) -->
                    <a href="#" class="text-white hover:text-gray-200 transition-colors transform hover:scale-110 duration-300">
                        <span class="font-bold text-xl">X</span>
                    </a>

                    <!-- WhatsApp -->
                    <a href="https://wa.me/59167900876" class="text-green-400 hover:text-green-300 transition-colors transform hover:scale-110 duration-300">
                        <i class="fab fa-whatsapp text-xl"></i>
                    </a>

                    <!-- TikTok -->
                    <a href="http://www.tiktok.com/@gam_tomave" class="text-white hover:text-gray-200 transition-colors transform hover:scale-110 duration-300">
                        <i class="fab fa-tiktok text-xl"></i>
                    </a>
                     <!-- Correo Electrónico -->
                    <a href="mailto:gob.aut.mun.tomave@gmail.com" class="text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-300">
                    <i class="fas fa-envelope text-lg"></i>
                </a>
                </div>
            </div>
        </div>
    </div>
</footer>
