document.addEventListener('DOMContentLoaded', function () {
    const sendEmail = document.getElementById('sendEmail');

    if (!sendEmail) return;

    sendEmail.addEventListener('click', function () {
        // Extract values from data attributes
        const url = sendEmail.getAttribute('data-url');
        const token = sendEmail.getAttribute('data-token');

        // Update UI
        sendEmail.disabled = true;
        const originalContent = sendEmail.innerHTML;
        sendEmail.innerText = "Sending...";

        fetch(url, {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token
            },
            body: JSON.stringify({})
        })
            .then(async res => {
                const text = await res.text(); // TEMP DEBUG

                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error("NOT JSON RESPONSE:", text);
                    throw new Error("Server did not return JSON");
                }
            })
            .then(data => {
                if (data.status === "success") {
                    Toast.create(document.body, "success", data.message);
                } else {
                    // If it's a rate limit error (from the previous step), it shows the message here
                    Toast.create(document.body, "error", data.message || "Something went wrong");
                }
            })
            .catch(err => {
                console.error(err);
                Toast.create(document.body, "error", "Request failed");
            })
            .finally(() => {
                // Re-enable and restore HTML
                sendEmail.disabled = false;
                sendEmail.innerHTML = originalContent;
            });
    });


    const sendQR = document.getElementById("send-qr");
    const qrUrl = sendQR.getAttribute('data-url');
    const qrToken = sendQR.getAttribute('data-token');
    sendQR.addEventListener('click', function () {
        // This targets the specific row containing the two cards in your image
        const target = document.getElementById("target-content");

        if (!target) {
            // Fallback in case the class name is different in your "All Results" view
            showResultQR_Fallback();
            return;
        }

        const btn = event.currentTarget;
        const originalText = btn.innerHTML;
        btn.innerHTML = "<span>Capturing Cards...</span>";

        html2canvas(target, {}).then(canvas => {
            const imageData = canvas.toDataURL("image/png");

            fetch(qrUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ image: imageData })
            })
            .then(async res => {
                const text = await res.text(); // TEMP DEBUG

                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error("NOT JSON RESPONSE:", text);
                    throw new Error("Server did not return JSON");
                }
            })
            .then(data => {
                if (data.success) {
                    document.getElementById("qrcode_canvas").innerHTML = "";

                    new QRCode(document.getElementById("qrcode_canvas"), {
                        text: data.url,
                        width: 200,
                        height: 200
                    });

                    document.getElementById("qrModal").style.display = "flex";
                }
            })
            .catch(err => {
                console.error(err);
            });

        });
    });

});
