const dialogs = document.getElementById("dialogsContainer");
const dialog_title = document.getElementById("dialogTitle");
const messages = document.getElementById("messagesContainer");
const message_input = document.getElementById("messageInput");
const dUserName = document.getElementById("adddial_userName");
const selDialogContainer = document.getElementById("selDialogContainer");
const actualDialogContainer = document.getElementById("actualDialogContainer");
const newDialogModal = document.getElementById("newDialogModal");
const newDialogAlertContainer = document.getElementById("newDialogAlertContainer");
const sidebar = document.getElementById("sidebar");
const content = document.getElementById("content");


window.WS_ENDPOINT = "ws://127.0.0.1:8089";
window.DIALOGS = {};
window.DIALOGS_BY_USERS = {};
window.USERS = {};
window.CURRENT_MESSAGES = [];
window.DIALOGS_SORTED = [];
window.CURRENT_DIALOG = 0;
window._WS = null;


function hideSidebar() {
    if(sidebar.classList.contains("d-flex")) {
        sidebar.classList.add("d-none");
        sidebar.classList.remove("d-flex");
    }
    if(content.classList.contains("d-none")) {
        content.classList.remove("d-none");
    }
}

function showSidebar() {
    if(sidebar.classList.contains("d-none")) {
        sidebar.classList.add("d-flex");
        sidebar.classList.remove("d-none");
    }
    if(!content.classList.contains("d-none")) {
        content.classList.add("d-none");
    }
}

function sendMessage() {
    let text = message_input.value.trim();
    if(!text || window._WS === null)
        return;

    window._WS.send(JSON.stringify({"op": "message", "d": {"text": text}}));
    message_input.value = "";
}

function processWsReady(data) {
    messages.innerHTML += `<li class="message">
                                <div>
                                    <span class="message-time"></span>
                                    <span class="message-text">[${data.from}] ${data.text}</span>
                                </div>
                            </li>`;
}

function processWsMessage(data) {
    messages.innerHTML += `<li class="message ${data.me ? "my-message" : ""}">
                                <div>
                                    <span class="message-time">[${data.time}]</span>
                                    <span class="message-text">${data.me ? "" : "["+data.from+"]"} ${data.text}</span>
                                </div>
                            </li>`;
}

function processWsBroadcast(data) {
    alert(`Message from administrator: ${data.text}`);
    messages.innerHTML += `<li class="message">
                                <div>
                                    <span class="message-time">[${data.time}]</span>
                                    <span class="message-text">[Admin] ${data.text}</span>
                                </div>
                            </li>`;
}

function initWs() {
    let ws = window._WS = new WebSocket(window.WS_ENDPOINT);

    ws.addEventListener("open", async (event) => {
        let resp = await fetch("/scripts/ws-token.php");
        if(resp.status === 401) {
            location.href = "/auth";
        } else if(resp.status !== 200) {
            alert("Unexpected error occurred on authenticating!\nPlease, try again later.");
            location.href = "/";
        }
        let j = await resp.json();

        ws.send(JSON.stringify({
            "op": "identify",
            "d": {
                "token": j["auth_token"],
            }
        }));
    });

    ws.addEventListener("message", (event) => {
        const data = JSON.parse(event.data);
        switch(data["op"]) {
            case "ready": return processWsReady();
            case "message": return processWsMessage(data["d"]);
            case "broadcast": return processWsBroadcast(data["d"]);
            default: return;
        }
    });

    ws.addEventListener("close", (event) => {
        window._WS = null;
        if(event.code === 4001) {
            location.href = "/auth";
            return;
        }
        setTimeout(initWs, 1000);
    });
}

window.addEventListener("DOMContentLoaded",  initWs, false);
