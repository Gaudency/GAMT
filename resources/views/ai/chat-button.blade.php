<!-- Botón flotante para el chat IA -->
<div x-data="{ aiChatOpen: false }"
     x-init="window.addEventListener('toggle-ai-chat', () => {
         console.log('Evento toggle-ai-chat recibido');
         aiChatOpen = !aiChatOpen;
         console.log('Estado del chat:', aiChatOpen ? 'abierto' : 'cerrado');
     })"
     class="relative">
    <!-- Botón para abrir el chat -->
    <button
        @click="aiChatOpen = true; console.log('Botón flotante clickeado');"
        class="fixed bottom-4 right-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-full p-3 shadow-lg hover:shadow-xl transition-all z-50"
        title="Asistente IA">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714a2.25 2.25 0 001.5 2.25M9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z" />
        </svg>
    </button>

    <!-- Ventana del chat -->
    <div
        x-show="aiChatOpen"
        @click.away="aiChatOpen = false"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90"
        class="fixed bottom-20 right-4 w-96 bg-white dark:bg-gray-800 rounded-lg shadow-2xl overflow-hidden z-50 border border-gray-200 dark:border-gray-700"
    >
        <!-- Encabezado del chat -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-4 flex justify-between items-center">
            <h3 class="font-bold">Asistente IA</h3>
            <button @click="aiChatOpen = false" class="text-white hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Área de mensajes -->
        <div id="ai-chat-messages" class="h-80 overflow-y-auto p-4 bg-gray-50 dark:bg-gray-900 space-y-4">
            <div class="flex items-start">
                <div class="flex-shrink-0 bg-blue-500 rounded-full p-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714a2.25 2.25 0 001.5 2.25M9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z"></path>
                    </svg>
                </div>
                <div class="ml-3 bg-white dark:bg-gray-800 rounded-lg p-3 shadow">
                    <p class="text-gray-800 dark:text-gray-200">¡Hola! Soy el asistente IA del sistema de gestión documental. ¿En qué puedo ayudarte hoy?</p>
                </div>
            </div>

            <!-- Aquí se añadirán dinámicamente los mensajes -->
        </div>

        <!-- Indicador de escritura -->
        <div id="ai-typing-indicator" class="hidden px-4 py-2 bg-gray-50 dark:bg-gray-900">
            <div class="flex items-center text-gray-500 dark:text-gray-400 text-sm">
                <div class="flex space-x-1">
                    <div class="w-2 h-2 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 0s"></div>
                    <div class="w-2 h-2 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 0.2s"></div>
                    <div class="w-2 h-2 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 0.4s"></div>
                </div>
                <span class="ml-2">El asistente está escribiendo...</span>
            </div>
        </div>

        <!-- Formulario de entrada -->
        <form id="ai-chat-form" class="p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <div class="flex">
                <input
                    type="text"
                    id="ai-chat-input"
                    class="flex-1 rounded-l-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Escribe tu pregunta..."
                    autocomplete="off"
                >
                <button
                    type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-r-lg transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script para manejar el chat -->
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('ai-chat-form');
    const input = document.getElementById('ai-chat-input');
    const messages = document.getElementById('ai-chat-messages');
    const typingIndicator = document.getElementById('ai-typing-indicator');

    // Manejar envío del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const message = input.value.trim();
        if (!message) return;

        // Añadir mensaje del usuario
        addMessage(message, 'user');

        // Limpiar input
        input.value = '';

        // Mostrar indicador de escritura
        typingIndicator.classList.remove('hidden');

        // Enviar mensaje a la API
        sendMessageToAI(message);
    });

    // Función para añadir mensaje al chat
    function addMessage(message, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex items-start ${sender === 'user' ? 'justify-end' : ''}`;

        let icon = '';
        let bubbleClass = '';

        if (sender === 'user') {
            icon = `<div class="flex-shrink-0 bg-green-500 rounded-full p-2 ml-3 order-2">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>`;
            bubbleClass = 'bg-green-100 dark:bg-green-900 order-1 mr-3';
        } else {
            icon = `<div class="flex-shrink-0 bg-blue-500 rounded-full p-2">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714a2.25 2.25 0 001.5 2.25M9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z"></path>
                </svg>
            </div>`;
            bubbleClass = 'bg-white dark:bg-gray-800 ml-3';
        }

        messageDiv.innerHTML = `
            ${icon}
            <div class="${bubbleClass} rounded-lg p-3 shadow">
                <p class="text-gray-800 dark:text-gray-200">${message}</p>
            </div>
        `;

        messages.appendChild(messageDiv);

        // Scroll to bottom
        messages.scrollTop = messages.scrollHeight;
    }

    // Función para enviar mensaje a la API
    function sendMessageToAI(message) {
        console.log('Enviando mensaje a la API:', message);
        console.log('URL de la API:', '{{ route("ai.chat") }}');

        fetch('{{ route("ai.chat") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                message: message
            })
        })
        .then(response => {
            console.log('Respuesta recibida:', response.status, response.statusText);
            if (!response.ok) {
                throw new Error('Error en la respuesta: ' + response.status + ' ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            // Ocultar indicador de escritura
            typingIndicator.classList.add('hidden');
            console.log('Datos procesados:', data);

            if (data.success) {
                // Añadir respuesta de la IA
                addMessage(data.message, 'ai');
            } else {
                // Mostrar error
                addMessage('Lo siento, ha ocurrido un error al procesar tu solicitud: ' + data.message, 'ai');
                console.error('Error en la respuesta:', data.message);
            }
        })
        .catch(error => {
            // Ocultar indicador de escritura
            typingIndicator.classList.add('hidden');
            console.error('Error en la petición:', error);

            // Mostrar error
            addMessage('Lo siento, ha ocurrido un error de conexión. Por favor, inténtalo de nuevo más tarde.', 'ai');
        });
    }
});
</script>
@endpush
