document.addEventListener('DOMContentLoaded', function() {
    const headsUPMessage = document.querySelector('.heads-up-message');
    if (headsUPMessage) {
        setTimeout(() => {
            headsUPMessage.style.opacity = '0';
            setTimeout(() => {
                headsUPMessage.remove();
                console.log('Flash message removed from DOM');
            }, 800); // Match the CSS transition duration
        }, 5000); // Display for 3 seconds
    }

    const simpleFlashMessage = document.querySelector('.simple-flash-message');
    const alertForm = document.querySelector('.alert-form');
    
    const alertButtonContainer = document.querySelector('.alert-button-container');
    const btnPrimary = document.querySelector('.btn-primary');
    const btnSecondary = document.querySelector('.btn-secondary');
    const btnMiddle = document.querySelector('.btn-middle');


});