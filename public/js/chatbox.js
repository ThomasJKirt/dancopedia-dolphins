function initializeChatbox() {
    console.log("Chatbox initializing...");

    // Toggle functionality
    const toggleChat = document.getElementById("toggleChat");
    const toggleChatControl = document.getElementById("toggleChatControl");
    const chatCard = document.querySelector(".card");

    if (!toggleChat || !toggleChatControl || !chatCard) {
        console.error("Chatbox elements not found.");
        return;
    }

    function toggleChatbox() {
        chatCard.classList.toggle("minimized");
        const cardBody = chatCard.querySelector(".card-body");
        const inputArea = chatCard.querySelector(".input-area");
        cardBody.classList.toggle("d-none");
        inputArea.classList.toggle("d-none");

        const toggleChatIcon = document.getElementById("toggleChatIcon");
        if (toggleChatIcon) {
            toggleChatIcon.classList.toggle("fa-window-maximize");
            toggleChatIcon.classList.toggle("fa-window-minimize");
        }
    }

    toggleChat.addEventListener("click", toggleChatbox);
    toggleChatControl.addEventListener("click", toggleChatbox);

    // Message sending functionality
    const inputField = chatCard.querySelector(".input-area .form-control");
    const sendButton = document.querySelector(".btn-primary");
    const messageContainer = document.querySelector(".card-body");

    console.log("Checking elements...");
    console.log("Input Field:", inputField);
    console.log("Send Button:", sendButton);
    console.log("Message Container:", messageContainer);


    if (!inputField || !sendButton || !messageContainer) {
        console.error("Message-related elements not found.");
        return;
    }

    async function sendMessage() {
        const messageText = inputField.value.trim();
        if (!messageText) return;

        const userMessage = document.createElement("div");
        userMessage.classList.add("d-flex", "align-items-baseline", "mb-4");
        userMessage.innerHTML = `<div class="pe-2"><div class="card d-inline-block p-2 px-3 m-1 outgoing">${messageText}</div></div>`;
        messageContainer.appendChild(userMessage);
        inputField.value = "";

        try {
            const response = await fetch("http://dancopedia-brazil.com/chat", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ message: messageText }),
            });

            const data = await response.json();
            const aiReply = data.response || "No response.";

            const aiMessage = document.createElement("div");
            aiMessage.classList.add("d-flex", "align-items-baseline", "mb-4");
            aiMessage.innerHTML = `<div class="position-relative avatar">
                <img src="assets/images/chatbox_face.jpg" class="img-fluid" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
            </div>
            <div class="pe-2"><div class="card d-inline-block p-2 px-3 m-1 incoming">${aiReply}</div></div>`;
            messageContainer.appendChild(aiMessage);
            messageContainer.scrollTop = messageContainer.scrollHeight;
        } catch (error) {
            console.error("Error sending message:", error);
        }
    }

    sendButton.addEventListener("click", sendMessage);
    inputField.addEventListener("keypress", (event) => {
        if (event.key === "Enter") sendMessage();
    });

    console.log("Chatbox initialized.");
}

// Manually call the function after chatbox.html loads
initializeChatbox();
