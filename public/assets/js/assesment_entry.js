document.addEventListener('DOMContentLoaded', function() {

    // showLoadingScreen();

    // let targetX = -1000;
    // let targetY = -1000;

    // // Current is where the glow currently is
    // let currentX = -1000;
    // let currentY = -1000;

    // window.addEventListener('mousemove', (e) => {
    // targetX = e.clientX;
    // targetY = e.clientY;
    // });

    // function animate() {
    // // The '0.1' is the speed/delay. 
    // // Lower = slower/more trail. Higher = snappier.
    // currentX += (targetX - currentX) * 0.1;
    // currentY += (targetY - currentY) * 0.1;

    // document.body.style.setProperty('--mouse-x', `${currentX}px`);
    // document.body.style.setProperty('--mouse-y', `${currentY}px`);

    // requestAnimationFrame(animate);
    // }

    // // Start the loop
    // animate();




    const interestCards = document.querySelectorAll('.interest-card');
    const interestInput = document.getElementById('interest-input');
    const interestOther = document.getElementById('other-interest-input');
    const addOtherInterestBtn = document.querySelector('.add-other-interest');
    const otherInterestList = document.getElementById('other-interest');

    let selectedInterest = [];
    let otherInterest = [];

    interestCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove 'selected' class from all cards
            inputValue = this.querySelector('h3').dataset.value;
            if (this.classList.contains('selected')) {
                this.classList.remove('selected');
                selectedInterest = selectedInterest.filter(item => item !== inputValue);
            } else {
                this.classList.add('selected');
                selectedInterest.push(inputValue);
            }

            // if (interestOther.value.trim() !== '') {
            //     if (!interestOther.value.trim() in selectedInterest) {
            //         selectedInterest.push(interestOther.value.trim());
            //     }
            // }
            interestInput.value = selectedInterest.join(', ');
            console.log('Selected: ', selectedInterest);

        });
    });

    addOtherInterestBtn.addEventListener('click', function(event) {
        event.preventDefault();
        const otherValue = interestOther.value.trim();
        if (otherValue !== '' && !otherInterest.includes(otherValue)) {
            otherInterest.push(otherValue);
            const li = document.createElement('li');
            li.textContent = otherValue;
            li.title = 'Click to remove';
            otherInterestList.appendChild(li);
            interestOther.value = '';
            console.log('Other Interests: ', otherInterest);
        }
    });

    otherInterestList.addEventListener('click', function(event) {
        if (event.target.tagName === 'LI') {
            const valueToRemove = event.target.textContent;
            otherInterest = otherInterest.filter(item => item !== valueToRemove);
            otherInterestList.removeChild(event.target);
            console.log('Other Interests: ', otherInterest);
        }
    });


    // const form = document.getElementById('assessment-form');
    // form.addEventListener('submit', function(event) {
    //     event.preventDefault();
    //     showLoadingScreen();
    //     const allInterests = [...new Set([...selectedInterest, ...otherInterest])];
    //     allInterestsData = allInterests.join(', ');
    //     interestInput.value = allInterestsData;
    //     // interestInput.value = JSON.stringify(allInterestsData);
    //     console.log('Final Interests on Submit: ', interestInput.value);
    //     form.submit();
    //});

    function showLoadingScreen() {
        console.log('Showing loading screen...');
        const loadingScreen = document.getElementById('loading-screen');
        loadingScreen.style.display = 'grid';
        const loadingText = loadingScreen.querySelector('p');
        // let dotCount = 0;
        // const maxDots = 3;
        // const interval = setInterval(() => {
        //     dotCount = (dotCount + 1) % (maxDots + 1);
        //     loadingText.textContent = 'Loading Exam' + '.'.repeat(dotCount);
        // }, 500);
    }

const loadingScreen = document.getElementById('loading-screen');
const statusText = loadingScreen.querySelector('p');
const form = document.getElementById('assessment-form');

// form.addEventListener('submit', function (e) {
//     e.preventDefault();

//     showLoadingScreen();

//     const formData = new FormData(form);

//     fetch('/generate-exam', {
//         method: 'POST',
//         headers: {
//             'X-CSRF-TOKEN': document
//                 .querySelector('meta[name="csrf-token"]')
//                 .content
//         },
//         body: formData
//     }).then(res => {
//         if (!res.ok) {
//             throw new Error('Network response was not ok');
//         }
//         return res.json();
//     }).then(data => {
//         console.log('Exam generation started:', data)
        
//         if (data.status === 'started') {
//             startPolling();
//         } else {
//             throw new Error('Failed to start exam generation');
//         }
//     }).catch(error => {
//         console.error('Error starting exam generation:', error);
//         statusText.textContent = 'An error occurred while starting exam generation. Please try again.';
//     });
// });


form.addEventListener('submit', async function (e) {
    e.preventDefault();

    showLoadingScreen();

    const formData = new FormData(form);

    try {
        const res = await fetch('/generate-exam', {
            method: 'POST',
            credentials: 'same-origin', // ⭐ THIS FIXES AUTH
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .content
            },
            body: formData
        });

        const data = await res.json();
        console.log('Generate exam response:', data);

        if (!data.job_id) {
            throw new Error('Job ID not returned from server');
        }

        startPolling(data.job_id);

    } catch (err) {
        console.error('Error starting exam generation:', err);
        alert('Error', err);
    }
});


function startPolling(jobId) {
    const interval = setInterval(() => {
    fetch(`/exam/status/${jobId}`)
        .then(res => res.json())
        .then(job => {
            if (job.status == null || job.message == null) {
                statusText.textContent = "Getting Ready..."
            } else if (job.status === 'null' || job.message === 'null') {
                statusText.textContent = "Getting Ready..."
            }
            statusText.textContent = job.message + " " + job.progress + "%";
            console.log('Polling job status:', job.message || job.status);
            if (job.status === 'done') {
                clearInterval(interval);
                setTimeout(() => {
                    window.location.href = `/show-exam/${job.id}`;
                }, 500)
            }
            if (job.status === 'failed') {
                clearInterval(interval);
                        statusText.textContent = job.error || 'An error occurred during exam generation. Please try again.';
            }
        });
    }, 2000);
}





});