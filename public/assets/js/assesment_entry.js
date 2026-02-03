document.addEventListener('DOMContentLoaded', function() {

    // showLoadingScreen();

    const prevBtns = document.querySelectorAll('.prev-btn');
    const nextBtns = document.querySelectorAll('.next-btn');
    const progress = document.getElementById('progress')
    const progressSteps = document.querySelectorAll('.progress-step')
    const formSteps = this.documentElement.querySelectorAll('.form-step')

    const interest_next_btn = document.getElementById('interest-next-btn');

    let formStepsNum = 0;

    nextBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            formStepsNum++;
            updateFormSteps();
            updateProgressStep();
            if (btn === interest_next_btn) {
                allInterest = getAllInterest();
                console.log(allInterest);
                createLikertScale();
            }
        })
    })

    prevBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            formStepsNum--;
            updateFormSteps();
            updateProgressStep();
        })
    })


    function updateFormSteps() {
        formSteps.forEach(formStep => {
            if (formStep.classList.contains('active')) {
                formStep.classList.remove('active')
            }
        })
        if (formSteps[formStepsNum]) {
            formSteps[formStepsNum].classList.add('active');
        }
    }

    function updateProgressStep() {
        progressSteps.forEach((progressStep, idx) => {
            if (idx < formStepsNum + 1) {
                progressStep.classList.add('active');
            } else {
                progressStep.classList.remove('active')
            }

            const progressActive = document.querySelectorAll('.progress-step.active');

            progress.style.width = (progressActive.length -1) / (progressSteps.length -1) * 100 + '%';
        })
    }

    const interestSkillMap = {
        coding: ['Programming Logic', 'Syntax', 'Problem Solving'],
        game_development: ['Game Mechanics', 'Physics Logic', 'Problem Solving'],
        software_mobile_dev: ['App Architecture', 'Tool Familiarity', 'Debugging'],
        cybersec_hacking: ['Security Awareness', 'Threat Analysis', 'Ethical Hacking Basics'],
        networking: ['Network Fundamentals', 'Troubleshooting', 'Protocol Knowledge'],
        building_robots: ['Hardware Logic', 'Sensors & Actuators', 'Problem Solving'],
        data_analytics: ['Data Interpretation', 'Statistics', 'Tool Familiarity'],
        ui_ux_designer: ['User Research', 'Wireframing', 'Visual Design'],
        videographer: ['Camera Operation', 'Storytelling', 'Editing Workflow'],
        editor: ['Timeline Control', 'Pacing', 'Storytelling'],
        graphic_design: ['Typography', 'Layout Composition', 'Visual Communication'],
        ai_ml: ['Algorithmic Thinking', 'Data Understanding' ,'Model Interpretation']
    };


    const genericSkillSet = [
        'Basic Knowledge',
        'Practical Experience',
        'Problem Solving',
        'Tool Familiarity'
    ];

    function getRandomItems(array, count) {
        const shuffled = [...array].sort(() => 0.5 - Math.random());
        return shuffled.slice(0, count);
    }


    function getSkillsForInterest(interest) {
        const skills = interestSkillMap[interest] || genericSkillSet;

        // Unknown / Other interest
        return getRandomItems(skills, 2);
    }


    const SCALE_CONFIG = {
        default: {
            labels: ['Novice', 'Beginner', 'Intermediate', 'Advanced', 'Expert'],
            values: [1, 2, 3, 4, 5]
        },
        exposure: {
            labels: [
                'No Exposure',
                'Heard Of',
                'Basic Understanding',
                'Can Explain',
                'Applied in Practice'
            ],
            values: [1, 2, 3, 4, 5]
        }
    };

    const INTEREST_SCALE_TYPE = {
        ai_ml: 'exposure',
        data_analytics: 'exposure'
        // everything else defaults to skill-based
    };

    function getScaleForInterest(interest) {
        const type = INTEREST_SCALE_TYPE[interest] || 'default';
        return SCALE_CONFIG[type];
    }



    function createLikertScale() {
        const container = document.getElementById('likert-container');
        container.innerHTML = ''; // reset

        const allInterests = getAllInterest();

        allInterests.forEach(interest => {
            const skills = getSkillsForInterest(interest);


            const likrt_interest = document.createElement('div');
            likrt_interest.classList.add('likert-interest');
            container.appendChild(likrt_interest);


            const scaleHint = document.createElement('small');
            scaleHint.className = 'scale-hint';

            scaleHint.textContent =
                INTEREST_SCALE_TYPE[interest] === 'exposure'
                    ? 'Scale based on familiarity and exposure'
                    : 'Scale based on skill level';

            likrt_interest.appendChild(scaleHint);


            // Interest title
            const title = document.createElement('h4');
            title.textContent = interest.toLowerCase().replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase());
                likrt_interest.appendChild(title);


            skills.forEach(skill => {
                const skillRow = document.createElement('div');
                skillRow.className = 'likert-row';

                const label = document.createElement('label');
                label.textContent = `${skill}`;
                skillRow.appendChild(label);

                const scale = document.createElement('div');
                scale.className = 'likert-scale';

                
                const scaleConfig = getScaleForInterest(interest);

                scaleConfig.values.forEach((value, index) => {
                    const radioId = `${interest}-${skill}-${value}`.replace(/\s+/g, '-');

                    const input = document.createElement('input');
                    input.type = 'radio';
                    input.name = `skills[${interest}][${skill}]`;
                    input.value = value;
                    input.id = radioId;
                    input.required = true;

                    const radioLabel = document.createElement('label');
                    radioLabel.setAttribute('for', radioId);
                    radioLabel.textContent = scaleConfig.labels[index];

                    scale.appendChild(input);
                    scale.appendChild(radioLabel);
                });

                skillRow.appendChild(scale);
                likrt_interest.appendChild(skillRow);
            });
        });
    }



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

    function getAllInterest() {
        const allInterests = [...new Set([...selectedInterest, ...otherInterest])];
        return allInterests;
        // allInterestsData = allInterests.join(', ');
        // interestInput.value = allInterestsData;
        // interestInput.value = JSON.stringify(allInterestsData);
        // console.log('Final Interests on Submit: ', interestInput.value);
    }

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
    const form = document.getElementById('assessment-form')


    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        showLoadingScreen();

        const formData = new FormData(form);

        // for (const [key, value] of formData.entries()) {
        //     console.log(key, value);
        // }

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

            const text = await res.text();
            console.log(text);


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