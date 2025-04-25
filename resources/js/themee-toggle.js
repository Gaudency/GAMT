document.addEventListener('DOMContentLoaded', function() {
    // Verificar y aplicar el tema al cargar la página
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        document.getElementById('theme-toggle-light-icon')?.classList.remove('hidden');
    } else {
        document.documentElement.classList.remove('dark');
        document.getElementById('theme-toggle-dark-icon')?.classList.remove('hidden');
    }

    // Configurar el evento de clic para el botón de toggle
    const themeToggleBtn = document.getElementById('theme-toggle');
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function() {
            // Cambiar tema
            document.documentElement.classList.toggle('dark');
            
            // Cambiar iconos
            document.getElementById('theme-toggle-dark-icon')?.classList.toggle('hidden');
            document.getElementById('theme-toggle-light-icon')?.classList.toggle('hidden');
            
            // Guardar preferencia
            if (document.documentElement.classList.contains('dark')) {
                localStorage.theme = 'dark';
            } else {
                localStorage.theme = 'light';
            }
        });
    }
}); 