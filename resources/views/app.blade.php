<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Hospital HIS') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<style>
  /* Botón Flotante Messenger */
  #messenger-btn {
    position: fixed; bottom: 20px; right: 20px; width: 60px; height: 60px;
    background: linear-gradient(135deg, #00ff55, #00ff3c); color: white;
    border-radius: 50%; border: none; cursor: pointer;
    box-shadow: 0 4px 15px rgba(0, 255, 98, 0.4); z-index: 9999;
    display: flex; align-items: center; justify-content: center;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  #messenger-btn:hover { transform: scale(1.08); box-shadow: 0 6px 20px rgba(0, 132, 255, 0.6); }

  /* Ventana del Chat */
  #messenger-box {
    display: none; position: fixed; bottom: 90px; right: 20px; width: 350px; height: 480px;
    background-color: #FFFFFF; border-radius: 18px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15); flex-direction: column;
    overflow: hidden; z-index: 9999; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    border: 1px solid rgba(0,0,0,0.08);
  }

  /* Encabezado Messenger */
  .msg-header {
    background: #FFFFFF; color: #050505; padding: 12px 16px; font-weight: 600;
    display: flex; align-items: center; justify-content: space-between;
    border-bottom: 1px solid #E4E6EB;
  }
  .msg-header-info { display: flex; align-items: center; gap: 10px; }
  .avatar-container { position: relative; }
  .avatar { width: 38px; height: 38px; background: #0084FF; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 16px; }
  .status-dot { position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; background: #31A24C; border: 2px solid white; border-radius: 50%; }
  .msg-header-text { display: flex; flex-direction: column; }
  .msg-title { font-size: 15px; font-weight: 700; color: #050505; }
  .msg-subtitle { font-size: 12px; color: #65676B; }
  .close-btn { background: #E4E6EB; border: none; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; color: #050505; font-weight: bold; display: flex; align-items: center; justify-content: center; }
  .close-btn:hover { background: #D8DADF; }

  /* Área de Mensajes */
  .msg-body { flex: 1; padding: 15px; overflow-y: auto; display: flex; flex-direction: column; gap: 8px; background: #171abd; }
  .bubble { max-width: 75%; padding: 10px 14px; border-radius: 18px; font-size: 14px; line-height: 1.4; word-wrap: break-word; }
  .bubble-bot { background: #F0F2F5; color: #050505; align-self: flex-start; border-bottom-left-radius: 4px; }
  .bubble-user { background: #0084FF; color: #FFFFFF; align-self: flex-end; border-bottom-right-radius: 4px; }

  /* Botones estilo Quick Replies de Messenger */
  .msg-footer { padding: 10px; display: flex; flex-wrap: wrap; gap: 6px; background: #FFFFFF; border-top: 1px solid #1660d1; }
  .quick-reply { background: #FFFFFF; border: 1px solid #0084FF; color: #0084FF; padding: 8px 14px; border-radius: 18px; font-size: 13px; font-weight: 600; cursor: pointer; transition: background 0.2s, color 0.2s; }
  .quick-reply:hover { background: #0084FF; color: #FFFFFF; }
</style>

<!-- Botón Flotante con Logo de Messenger -->
<button id="messenger-btn" onclick="toggleMessenger()">
  <svg width="28" height="28" viewBox="0 0 24 24" fill="white">
    <path d="M12 2C6.477 2 2 6.145 2 11.258c0 2.91 1.455 5.51 3.733 7.182V22l3.418-1.876c.91.253 1.873.39 2.849.39 5.523 0 10-4.145 10-9.256S17.523 2 12 2zm1.09 12.392l-2.587-2.76-5.05 2.76 5.552-5.897 2.65 2.76 4.986-2.76-5.551 5.897z"/>
  </svg>
</button>

<!-- Ventana de Chat Estilo Messenger -->
<div id="messenger-box">
  <div class="msg-header">
    <div class="msg-header-info">
      <div class="avatar-container">
        <div class="avatar">🏥</div>
        <div class="status-dot"></div>
      </div>
      <div class="msg-header-text">
        <span class="msg-title">Asistente Médico</span>
        <span class="msg-subtitle">Activo(a) ahora</span>
      </div>
    </div>
    <button class="close-btn" onclick="toggleMessenger()">✕</button>
  </div>

  <div class="msg-body" id="msg-body">
    <div class="bubble bubble-bot">¡Hola! 👋 Bienvenido a nuestro centro médico. ¿Qué deseas consultar hoy?</div>
  </div>

  <div class="msg-footer">
    <button class="quick-reply" onclick="consultarAPI('especialidades', 'Especialidades')">Especialidades</button>
    <button class="quick-reply" onclick="consultarAPI('medicos', 'Nuestros Médicos')">Médicos</button>
    <button class="quick-reply" onclick="consultarAPI('disponibilidad', 'Disponibilidad')">Disponibilidad</button>
  </div>
</div>

<script>
  function toggleMessenger() {
    const box = document.getElementById('messenger-box');
    box.style.display = (box.style.display === 'flex') ? 'none' : 'flex';
  }

  async function consultarAPI(clave, etiqueta) {
    const chat = document.getElementById('msg-body');

    // 1. Burbuja del usuario
    chat.innerHTML += `<div class="bubble bubble-user">${etiqueta}</div>`;
    chat.scrollTop = chat.scrollHeight;

    // 2. Consulta al backend de Laravel
    try {
      const response = await fetch('/api/chatbot/consulta', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ opcion: clave })
      });

      const data = await response.json();

      // 3. Burbuja de respuesta del bot
      chat.innerHTML += `<div class="bubble bubble-bot">${data.respuesta}</div>`;
    } catch (error) {
      chat.innerHTML += `<div class="bubble bubble-bot">Ocurrió un problema al conectar con el servidor.</div>`;
    }

    chat.scrollTop = chat.scrollHeight;
  }
</script>
<body class="antialiased">
<div id="app"></div>
</body>
</html>
