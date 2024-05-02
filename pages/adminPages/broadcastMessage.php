<h2 style="text-align: center;">Send message to all online users</h2>
<div id="input-user-login-div">
    <div style="display: flex; flex-direction: column; gap: 10px; padding: 0 50px;">
        <label style="display: flex; flex-direction: column">
            Input the message you want to send
            <input type="text" id="broadcast-text" placeholder="Message..." required>
        </label>
        <p id="broadcast-status" style="text-align: center"></p>
        <button type="button" id="broadcast-btn" onclick="broadcastMessage();">Send</button>
    </div>
</div>

<script>
    window.WS_ENDPOINT = "ws://127.0.0.1:8089";
    window._WS = null;
    const bcBtn = document.getElementById("broadcast-btn");
    const bcText = document.getElementById("broadcast-text");
    const bcStatus = document.getElementById("broadcast-status");

    bcBtn.disabled = "disabled";

    function broadcastMessage() {
        if(window._WS === null) {
            bcBtn.disabled = "disabled";
            return;
        }

        const text = bcText.value.trim();
        if(!text)
            return

        bcStatus.innerText = `Sending message...`;
        bcBtn.disabled = "disabled";
        bcText.disabled = "disabled";
        window._WS.send(JSON.stringify({
            "op": "broadcast",
            "d": {
                "text": text,
            },
        }))
    }

    function processWsReady(data) {
        bcStatus.innerText = "Connected to server! You can now send messages!";
        bcBtn.disabled = "";
    }

    function processWsBroadcast(data) {
        bcStatus.innerText = `Message sent: ${data.text}`;
        bcText.value = "";
        bcText.disabled = "";
        bcBtn.disabled = "";
    }

    function initWs() {
        bcStatus.innerText = "Connecting to server...";
        let ws = window._WS = new WebSocket(window.WS_ENDPOINT);

        ws.addEventListener("open", async (event) => {
            bcStatus.innerText = "Connected to server! Authenticating...";
            let resp = await fetch("/ws-token.php");
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
                case "broadcast": return processWsBroadcast(data["d"]);
                default: return;
            }
        });

        ws.addEventListener("close", (event) => {
            bcStatus.innerText = "Lost connection! Trying to reconnect...";
            window._WS = null;
            if(event.code === 4001) {
                location.href = "/auth";
                return;
            }
            setTimeout(initWs, 1000);
        });
    }

    initWs();
</script>