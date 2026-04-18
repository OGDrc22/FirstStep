document.addEventListener('DOMContentLoaded', function() {
  


    const headsUPMessage = document.querySelector('.toast-container');
    if (!headsUPMessage) return;
    
    const elem = document.getElementById("progress-t");
    if (!elem) return;

    // 1. Define the logic to run
    function startSequence() {
        setTimeout(() => {
            move(4);
        }, 1000);

        setTimeout(() => {
            headsUPMessage.style.opacity = '0';
            setTimeout(() => {
                headsUPMessage.style.display = 'none';
                console.log('Flash message removed from DOM');
                elem.style.width = "100%";
            }, 800);
        }, 5000);
    }

    // 2. Create an observer to watch for style changes
    const observer = new MutationObserver(() => {
        const currentStyle = window.getComputedStyle(headsUPMessage).display;
        if (currentStyle === 'flex') {        
            startSequence();
            observer.disconnect(); 
        }
    });

    // 3. Start observing the element
    observer.observe(headsUPMessage, { attributes: true, attributeFilter: ['style', 'class'] });

    // Initial check (in case it's already flex on load)
    if (window.getComputedStyle(headsUPMessage).display === 'flex') {
        startSequence();
        observer.disconnect();
    }

    let progressInterval;
    function move(seconds) {

        console.log(window.getComputedStyle(elem).width);
        
        clearInterval(progressInterval);

        var width = 100;
        var speed = seconds * 10;
        progressInterval = setInterval(() => {
            if (width <= 0) {
                clearInterval(progressInterval);
            } else {
                width--;
                elem.style.width = width + "%";
            }
        }, speed);
    }
});
// Function to handle the progress bar for a SPECIFIC element
function move(seconds, barElement) {
    let width = 100;
    const speed = (seconds * 1000) / 100;
    
    const interval = setInterval(() => {
        if (width <= 0) {
            clearInterval(interval);
        } else {
            width--;
            barElement.style.width = width + "%";
        }
    }, speed);
}

// Remove 'export' if not using type="module"
window.Toast = {
    activeMessages: new Set(),
    create(context, type, message) {

        if (this.activeMessages.has(message)) return;

        this.activeMessages.add(message);
        
        let toast; 
        if (toast) {
            toast.remove();
        }

        let notifCon = context.querySelector('.notif-container');

        // 2. If it doesn't exist, create and append it ONCE
        if (!notifCon) {
            notifCon = document.createElement('div');
            notifCon.className = "notif-container";
            context.appendChild(notifCon);

            // 3. Attach the observer ONLY when the container is first created
            const observer = new MutationObserver(() => {
                if (notifCon.childElementCount === 0) {
                    observer.disconnect(); // Clean up the observer
                    notifCon.remove();     // Remove container from DOM
                }
            });
            observer.observe(notifCon, { childList: true });
        }

        toast = document.createElement('div');
        toast.className = "toast-container";
        toast.style.display = "flex"; // Ensure it is visible
        toast.style.opacity = "1";

        let msgType = type;
        if (msgType === 'err' | msgType === 'error') {
            msgType = 'close'
        } else {
            msgType = type;
        }

        toast.innerHTML = `
            <span></span>
            <div class="message-container">
                <i class="icon icon-${msgType}"></i>
                <p>${message}</p>
            </div>
            <div id="timerProgress">
                <div class="progress-t" style="width: 100%;"></div>
            </div>
        `;

        
        notifCon.appendChild(toast);

        const progressBar = toast.querySelector('.progress-t');
        
        // Start progress bar after 1s
        setTimeout(() => {
            move(4, progressBar);
        }, 1000);

        // Auto-remove toast after 5s
        setTimeout(() => {
            toast.style.opacity = "0";
            setTimeout(() => {
                toast.remove();
                this.activeMessages.delete(message);
        }, 1000);
        }, 5000);
    }
}
