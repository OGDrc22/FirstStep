document.addEventListener('DOMContentLoaded', function () {

    // showLoadingScreen();


    function getInputs() {
        const form = document.getElementById('assessment-form');

        const formData = new FormData(form);

        for (const [key, value] of formData.entries()) {
            console.log(key, value);
        }
    }




    const interestCards = document.querySelectorAll('.interest-card');
    const interestInput = document.getElementById('interest-input');
    const interestOther = document.getElementById('other-interest-input');
    const addOtherInterestBtn = document.querySelector('.add-other-interest');
    const otherInterestList = document.getElementById('other-interest');

    let selectedInterest = [];
    let otherInterest = [];

    interestCards.forEach(card => {
        card.addEventListener('click', function () {
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

    function normalizeInterest(value) {
        return value.trim().toLowerCase();
    }

    addOtherInterestBtn.addEventListener('click', function (event) {
        event.preventDefault();
        const otherValue = interestOther.value.trim();
        const otherNormalized = normalizeInterest(otherValue);
        const selectedNormalized = selectedInterest.map(normalizeInterest);
        const otherListNormalized = otherInterest.map(normalizeInterest);

        if (
            otherValue !== '' &&
            !otherListNormalized.includes(otherNormalized) &&
            !selectedNormalized.includes(otherNormalized)
        ) {
            otherInterest.push(otherValue);
            const li = document.createElement('li');
            li.textContent = otherValue;
            li.title = 'Click to remove';
            otherInterestList.appendChild(li);
            interestOther.value = '';
            console.log('Other Interests: ', otherInterest);
        }
    });

    otherInterestList.addEventListener('click', function (event) {
        if (event.target.tagName === 'LI') {
            const valueToRemove = event.target.textContent;
            otherInterest = otherInterest.filter(item => item !== valueToRemove);
            otherInterestList.removeChild(event.target);
            console.log('Other Interests: ', otherInterest);
        }
    });


    const assessmentState = {
        basicInfo: {},
        interests: [],
        skills: {}
    };

    function collectBasicInfo() {
        const form = document.getElementById('assessment-form');
        const formData = new FormData(form);

        assessmentState.basicInfo = {
            name: formData.get('name'),
            email: formData.get('email')
        };

        assessmentState.interests = getAllInterest();
    }



    const prevBtns = document.querySelectorAll('.prev-btn');
    const nextBtns = document.querySelectorAll('.next-btn');
    const progress = document.getElementById('progress')
    const progressSteps = document.querySelectorAll('.progress-step')
    const formSteps = this.documentElement.querySelectorAll('.form-step')

    const interest_next_btn = document.getElementById('interest-next-btn');
    const skill_next_btn = document.getElementById('skill-next-btn')

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

            if (btn === skill_next_btn) {
                collectBasicInfo();
                collectSkillRatings();

                if (!validatePreMiniTest()) return;
                const interest = getAllInterest()
                const question = generateMiniTestQuestions(interest);
                renderMiniTest(question);
                getInputs();
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

            progress.style.width = (progressActive.length - 1) / (progressSteps.length - 1) * 100 + '%';
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
        ai_ml: ['Algorithmic Thinking', 'Data Understanding', 'Model Interpretation']
    };


    const genericSkillSet = [
        'Basic Knowledge',
        'Practical Experience',
        'Problem Solving',
        'Tool Familiarity'
    ];


    let miniTestQuestions = [];

    miniTestQuestions = [
        {
            interest: 'coding',
            question: 'What does a loop do?',
            options: ['Repeat code', 'End program', 'Store data', 'Debug errors'],
            correct: 0
        },
        {
            interest: 'game_development',
            question: 'What is a "Game Engine" primarily used for?',
            options: [
                'Playing music in the background',
                'Providing a framework to build and render games',
                'Lowering the price of games',
                'Cleaning the computer hardware'
            ],
            correct: 1
        },
        {
            interest: 'software_mobile_dev',
            question: 'Which of these allows an app to run on both iOS and Android using one codebase?',
            options: [
                'Native Development',
                'Cross-platform Framework',
                'Binary Translation',
                'Cloud Storage'
            ],
            correct: 1
        },
        {
            interest: 'cybersec_hacking',
            question: 'What is the main purpose of "Encryption"?',
            options: [
                'To make the internet faster',
                'To delete suspicious files',
                'To secure data by making it unreadable to unauthorized users',
                'To bypass hardware firewalls'
            ],
            correct: 2
        },
        {
            interest: 'networking',
            question: 'What does a Router do in a network?',
            options: [
                'It stores all the website passwords',
                'It directs data packets between different networks',
                'It increases the physical screen resolution',
                'It acts as the main power supply'
            ],
            correct: 1
        },
        {
            interest: 'building_robots',
            question: 'Which component acts as the "muscles" of a robot to create movement?',
            options: [
                'Sensors',
                'Microcontrollers',
                'Actuators/Servos',
                'Batteries'
            ],
            correct: 2
        },
        {
            interest: 'data_analytics',
            question: 'What is the primary goal of Data Visualization?',
            options: [
                'To make data look pretty but unreadable',
                'To help identify patterns, trends, and outliers',
                'To hide errors in the dataset',
                'To increase the storage size of a file'
            ],
            correct: 1
        },
        {
            interest: 'ui_ux_designer',
            question: 'What is wireframing mainly used for?',
            options: [
                'Visual layout planning',
                'Writing code',
                'Testing performance',
                'Deploying apps'
            ],
            correct: 0
        },
        {
            interest: 'videographer',
            question: 'What does "FPS" (Frames Per Second) affect in a video?',
            options: [
                'The brightness of the image',
                'The smoothness of motion',
                'The volume of the audio',
                'The file name'
            ],
            correct: 1
        },
        {
            interest: 'editor',
            question: 'In video editing, what is "Color Grading"?',
            options: [
                'Fixing the sound levels',
                'The process of enhancing or altering the color of a motion picture',
                'The speed at which the video exports',
                'Organizing clips in alphabetical order'
            ],
            correct: 1
        },
        {
            interest: 'graphic_design',
            question: 'Which principle relates to text readability?',
            options: [
                'Typography',
                'Contrast',
                'Balance',
                'Proximity'
            ],
            correct: 0
        },
        {
            interest: 'ai_ml',
            question: 'What is "Machine Learning"?',
            options: [
                'Building a computer from scratch',
                'Giving computers the ability to learn from data without explicit programming',
                'A way to make robots move faster',
                'Repairing broken hard drives'
            ],
            correct: 1
        }
    ];

    let genericMiniTest = [];

    genericMiniTest = [
        {
            question: 'How do you usually approach learning a new technical skill?',
            options: [
                'I wait for formal instruction',
                'I follow tutorials step-by-step',
                'I experiment and practice',
                'I research and build projects'
            ]
        },
        {
            question: 'When facing a problem you don’t understand, what do you do first?',
            options: [
                'Skip it',
                'Ask for help immediately',
                'Search for information',
                'Break it into smaller parts'
            ]
        },
        {
            question: 'What kind of work environment do you prefer?',
            options: [
                'Working alone on deep tasks',
                'Collaborating in a fast-paced team',
                'Leading and organizing projects',
                'Creative brainstorming sessions'
            ]
        },
        {
            question: 'Which part of a project is most satisfying to you?',
            options: [
                'The initial planning and logic',
                'Designing how it looks and feels',
                'Solving the technical bugs',
                'Seeing the final finished product'
            ]
        },
        {
            question: 'How do you handle repetitive tasks?',
            options: [
                'I do them manually and carefully',
                'I try to find a way to automate them',
                'I delegate them to others',
                'I prefer to avoid them entirely'
            ]
        }
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

    function collectSkillRatings() {
        assessmentState.skills = {};

        document
            .querySelectorAll('input[type="radio"]:checked')
            .forEach(input => {
                const match = input.name.match(/skills\[(.*?)\]\[(.*?)\]/);
                if (!match) return;

                const interest = match[1];
                const skill = match[2];

                if (!assessmentState.skills[interest]) {
                    assessmentState.skills[interest] = {};
                }

                assessmentState.skills[interest][skill] = Number(input.value);
            });
    }

    function validatePreMiniTest() {
        if (!assessmentState.interests.length) {
            alert('Please select at least one interest.');
            return false;
        }

        if (!Object.keys(assessmentState.skills).length) {
            alert('Please rate your skills.');
            return false;
        }

        return true;
    }




    function getAllInterest() {
        // Prefer selectedInterest values when duplicates exist (case-insensitive).
        const merged = [...selectedInterest, ...otherInterest];
        const seen = new Map();
        merged.forEach(item => {
            const key = normalizeInterest(item);
            if (!seen.has(key)) {
                seen.set(key, item);
            }
        });
        return Array.from(seen.values());
        // allInterestsData = allInterests.join(', ');
        // interestInput.value = allInterestsData;
        // interestInput.value = JSON.stringify(allInterestsData);
        // console.log('Final Interests on Submit: ', interestInput.value);
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



    function collectMiniTestAnswers() {
        assessmentState.miniTest = [];

        document
            .querySelectorAll('#mini-test-container input[type="radio"]:checked')
            .forEach(input => {
                assessmentState.miniTest.push({
                    interest: input.dataset.interest,
                    question_id: input.dataset.questionId,
                    selected: Number(input.value),
                    correct: input.dataset.correct === '' ? null : Number(input.dataset.correct)
                });
                console.log("interest: " + input.dataset.interest,
                    "selected: " + Number(input.value))
            });

    }





    function generateMiniTestQuestions(interests) {
        const selected = [];
        interests.forEach(interest => {
            // Try to find a matching question
            const match = miniTestQuestions.find(q => q.interest === interest);

            if (match) {
                selected.push({
                    ...match,
                    question_id: `${interest}`});
            } else {
                // 👇 OTHER INTEREST → generic question
                const generic = genericMiniTest[
                    Math.floor(Math.random() * genericMiniTest.length)
                ];

                selected.push({
                    ...generic,
                    interest: 'other',
                    original_interest: interest,
                    question_id: 'generic',
                    correct: null
                });
            }
        });

        return selected;
    }



    function renderMiniTest(questions) {
        const container = document.getElementById('mini-test-container');
        container.innerHTML = '';

        questions.forEach((q, idx) => {
            const block = document.createElement('div');
            block.classList.add('mini-question');

            block.innerHTML = `
                <p>${idx + 1}. ${q.question}</p>
                ${q.options.map((opt, i) => `
                    <label>
                        <input type="radio"
                            name="minitest_answers[${idx}]"
                            data-interest="${q.interest}"
                            data-question-id="${q.question_id}"
                            data-correct="${q.correct ?? ''}"
                            value="${i}"
                            required>
                        ${opt}
                    </label>
                `).join('<br>')}
            `;

            container.appendChild(block);
            console.log('Rendering:', q.question_id);

        });
    }




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

        collectBasicInfo();
        collectSkillRatings();

        collectMiniTestAnswers();

        // inject JSON into hidden input
        document.getElementById('minitest-input').value =
            JSON.stringify(assessmentState.miniTest);



        const formData = new FormData(form);


        getInputs();
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
