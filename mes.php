<?php include 'config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Community Chat</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="style.css">
</head>
<script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: { primary: "#3b82f6", secondary: "#10b981" },
            borderRadius: {
              none: "0px",
              sm: "4px",
              DEFAULT: "8px",
              md: "12px",
              lg: "16px",
              xl: "20px",
              "2xl": "24px",
              "3xl": "32px",
              full: "9999px",
              button: "8px",
            },
          },
        },
      };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css"
    />
    <style>
      :where([class^="ri-"])::before { content: "\f3c2"; }
      body {
          font-family: 'Inter', sans-serif;
      }
      .sidebar {
          width: 260px;
          transition: all 0.3s;
      }
      .main-content {
          width: calc(100% - 260px);
          transition: all 0.3s;
      }
      .investor-scroll::-webkit-scrollbar,
      .message-container::-webkit-scrollbar {
          height: 6px;
          width: 6px;
      }
      .investor-scroll::-webkit-scrollbar-thumb,
      .message-container::-webkit-scrollbar-thumb {
          background-color: #cbd5e1;
          border-radius: 999px;
      }
      .investor-scroll::-webkit-scrollbar-track,
      .message-container::-webkit-scrollbar-track {
          background-color: #f1f5f9;
      }
      .message-container {
          height: calc(100vh - 240px);
          overflow-y: auto;
      }
      input[type="range"]::-webkit-slider-thumb {
          appearance: none;
          width: 20px;
          height: 20px;
          background-color: #3b82f6;
          border-radius: 50%;
          cursor: pointer;
      }
      input[type="checkbox"] {
          appearance: none;
      }
      .custom-checkbox {
          position: relative;
          display: inline-block;
          width: 18px;
          height: 18px;
          background-color: white;
          border: 2px solid #cbd5e1;
          border-radius: 4px;
          cursor: pointer;
      }
      .custom-checkbox.checked {
          background-color: #3b82f6;
          border-color: #3b82f6;
      }
      .custom-checkbox.checked::after {
          content: "";
          position: absolute;
          top: 2px;
          left: 6px;
          width: 5px;
          height: 10px;
          border: solid white;
          border-width: 0 2px 2px 0;
          transform: rotate(45deg);
      }
      .custom-switch {
          position: relative;
          display: inline-block;
          width: 44px;
          height: 24px;
      }
      .custom-switch-input {
          opacity: 0;
          width: 0;
          height: 0;
      }
      .custom-switch-slider {
          position: absolute;
          cursor: pointer;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background-color: #e2e8f0;
          transition: .4s;
          border-radius: 34px;
      }
      .custom-switch-slider:before {
          position: absolute;
          content: "";
          height: 18px;
          width: 18px;
          left: 3px;
          bottom: 3px;
          background-color: white;
          transition: .4s;
          border-radius: 50%;
      }
      .custom-switch-input:checked + .custom-switch-slider {
          background-color: #3b82f6;
      }
      .custom-switch-input:checked + .custom-switch-slider:before {
          transform: translateX(20px);
      }
      @media (max-width: 1024px) {
          .sidebar {
              width: 80px;
          }
          .main-content {
              width: calc(100% - 80px);
          }
          .sidebar-text {
              display: none;
          }
      }
      @media (max-width: 768px) {
          .sidebar {
              width: 0;
              padding: 0;
          }
          .main-content {
              width: 100%;
          }
      }
    </style>

<body class="bg-gray-50 flex flex-col min-h-screen">
<header class="header">
        <div class="logo">Pitchus<span>.ai</span></div>
        
        <nav class="nav-links">
            <a href="index.php" style="text-decoration: none;"><div class="nav-item ">Home</div></a>
            <a href="mes.php" style="text-decoration: none;"><div class="nav-item active">Message</div></a>
            <a href="news.php" style="text-decoration: none;"><div class="nav-item ">News</div></a>
            <a href="jobs.php" style="text-decoration: none;"><div class="nav-item ">Jobs</div></a>
            <a href="ai.php" style="text-decoration: none;"><div class="nav-item ">AI</div></a>
            <a href="doc.php" style="text-decoration: none;"><div class="nav-item ">Documents</div></a>
        </nav>
        
        <div class="user-controls">
            <div class="search-bar">
                <div class="search-icon">🔍</div>
                <input type="text" placeholder="Search startups...">
            </div>
            <div class="user-avatar">PD</div>
        </div>
    </header>

<section id="message" class="flex-1 flex flex-col p-6 md:p-8">
  <div class="max-w-5xl mx-auto w-full flex flex-col flex-1">
    
    <!-- Heading -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">Community Chat</h1>
      <p class="text-gray-600">Connect with entrepreneurs and investors in real-time.</p>
    </div>

    <!-- Chat Box -->
    <div class="flex-1 bg-white rounded-lg shadow-sm overflow-hidden flex flex-col">
      <!-- Messages -->
      <div id="chat-box" class="flex-1 p-6 overflow-y-scroll bg-gray-50" style="max-height: 70vh;">
        <div class="flex flex-col gap-4" id="messages-area">
          <!-- Messages will be dynamically inserted here -->
        </div>
      </div>

      <!-- Message Input -->
      <div class="p-4 border-t border-gray-200 bg-white">
        <form id="chat-form" class="flex gap-3">
          <input
            type="text"
            id="sender"
            required
            class="w-1/4 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Your name"
          />
          <input
            type="text"
            id="message"
            required
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Type your message..."
          />
          <button
            type="submit"
            class="bg-blue-600 text-white py-2 px-6 rounded-lg font-medium hover:bg-blue-700 transition"
          >
            Send
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

<script>
function fetchMessages() {
  fetch('get_messages.php')
    .then(res => res.json())
    .then(data => {
      const messagesArea = document.getElementById('messages-area');
      messagesArea.innerHTML = '';
      data.forEach(msg => {
        const initials = msg.sender_name.trim().split(" ").map(w => w[0]).join("").toUpperCase().substring(0, 2);
        const messageHTML = `
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-full bg-blue-200 text-blue-800 flex items-center justify-center font-bold">${initials}</div>
            <div>
              <div class="flex items-center gap-2 mb-1">
                <span class="font-medium text-gray-900">${msg.sender_name}</span>
              </div>
              <p class="text-gray-700 bg-white p-3 rounded-lg shadow-sm">${msg.message}</p>
            </div>
          </div>
        `;
        messagesArea.innerHTML += messageHTML;
      });
      const chatBox = document.getElementById('chat-box');
      chatBox.scrollTop = chatBox.scrollHeight;
    });
}

document.getElementById('chat-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const sender = document.getElementById('sender').value;
  const message = document.getElementById('message').value;

  fetch('submit_message.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `sender=${encodeURIComponent(sender)}&message=${encodeURIComponent(message)}`
  }).then(() => {
    document.getElementById('message').value = '';
    fetchMessages();
  });
});

setInterval(fetchMessages, 2000);
fetchMessages();
</script>

</body>
</html>
