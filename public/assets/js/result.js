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
        .then(res => res.json())
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
});
