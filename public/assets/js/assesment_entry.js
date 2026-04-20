
document.addEventListener('DOMContentLoaded', async function () {
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
    let min = 60 * 3;
    let timer = 0;
    let timeInterval;

    interestCards.forEach(card => {
        card.addEventListener('click', function () {
            // Remove 'selected' class from all cards
            inputValue = this.querySelector('h3').dataset.value;
            if (this.classList.contains('selected')) {
                this.classList.remove('selected');
                selectedInterest = selectedInterest.filter(item => item !== inputValue);

                timer -= min;
            } else {
                this.classList.add('selected');
                selectedInterest.push(inputValue);
                
                timer += min;
            }

            // if (interestOther.value.trim() !== '') {
            //     if (!interestOther.value.trim() in selectedInterest) {
            //         selectedInterest.push(interestOther.value.trim());
            //     }
            // }
            interestInput.value = selectedInterest.join(', ');
            console.log('Selected: ', selectedInterest, timer);
            // countInterest = selectedInterest.length + otherInterest.length
            // console.log('interest count', countInterest);

        });
    });

    function normalizeInterest(value) {
        return value.trim().toLowerCase();
    };

    otherInterestList.addEventListener('click', function (event) {
        if (event.target.classList.contains('other-interest-item')) {
            const valueToRemove = event.target.textContent;
            otherInterest = otherInterest.filter(item => item !== valueToRemove);
            otherInterestList.removeChild(event.target);

            timer -= min
            console.log("Removing: ", valueToRemove);
            
            console.log('Other Interests: ', otherInterest, timer);
        }
    });

    interestOther.addEventListener('keypress', function(event) {
        if (event.key === "Enter") {
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
                const div = document.createElement('div');
                div.classList.add('other-interest-item');
                div.textContent = otherValue;
                div.title = 'Click to remove';
                otherInterestList.appendChild(div);
                interestOther.value = '';

                timer += min
                console.log('Other Interests: ', otherInterest, timer);
            }

            console.log('enter')
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
    const progressSteps = document.querySelectorAll('.progress-step')
    const formSteps = this.documentElement.querySelectorAll('.form-step')

    const interest_next_btn = document.getElementById('interest-next-btn');
    const skill_next_btn = document.getElementById('skill-next-btn');
    const submit_btn = document.getElementById('submit-btn');
    const basic_info = document.getElementById('basic-info-next-btn');

    
    const emailInput = document.getElementById('email');
    const nameInput = document.getElementById('name');

    nameInput.addEventListener('keypress', function(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            if (nameInput.value.trim() !== "" && emailInput.value.trim() !== "") {
                basic_info.click();
            }
        }
    });

    emailInput.addEventListener('keypress', function(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            if (nameInput.value.trim() !== "" && emailInput.value.trim() !== "") {
                basic_info.click();
            }
        }
    });

    
    const { generateMiniTestQuestions } = await import('./assessment_helper.js');

    nextBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            if (btn === basic_info) {
                const isValid = validateBasicInfo();

                if (!isValid) return;
            }
            if (btn === interest_next_btn) {
                if (!isInterestEmpty()) return;
                allInterest = getAllInterest();
                console.log(allInterest);
                createLikertScale();
            }

            if (btn === skill_next_btn) {
                collectBasicInfo();
                collectSkillRatings();

                if (!validatePreMiniTest()) return;
                console.log("validating ")
                const interest = getAllInterest()
                const question = generateMiniTestQuestions(interest);
                renderMiniTest(question);
                getInputs();
                // autoSubmit(submit_btn);
                startCountDown();
                document.getElementById('seconds').innerText = timer;
            }

            if (btn === submit_btn) {
                if (!validateMiniTest()) {
                    if (e) e.preventDefault();
                    return;
                }
                return;
            }
            formStepsNum++;
            updateFormSteps();
            updateProgressStep();
            

        })
    });

    prevBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            formStepsNum--;
            updateFormSteps();
            updateProgressStep();
        })
    })

    function validateBasicInfo() {
        if (nameInput.value.trim() === "") {
            Toast.create(document.body, "err", "Please enter a Username");
            return false;
        }

        if (emailInput.value.trim() === "") {
            Toast.create(document.body, "err", "Please enter an Email");
            return false;
        }

        if (!validateEmail(emailInput.value)) {
            Toast.create(document.body, "err", "Please enter a valid email");
            return false;
        }

        return true;
    }
    function validateEmail(email) {
        const pattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        console.log(pattern.test(email));
        
        return pattern.test(email);
    }





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
            updateHeader();

            const progressActive = document.querySelectorAll('.progress-step.active');

            // progress.style.width = (progressActive.length - 1) / (progressSteps.length - 1) * 100 + '%';
        })
    }

    function updateHeader() {
        const step = document.querySelector('.form-step.active');
        const header = document.querySelector('.header h3');
        if (header) {
            header.textContent = `${step.getAttribute('data-title')}`;
        }
        
        const subHeader = document.querySelector('.header p');
        if (subHeader) {
            subHeader.textContent = step.getAttribute('data-subtitle');
        }
    }

    
    const { getSkillsForInterest } = await import('./assessment_helper.js');

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

    function countSkillRating() {
        const radio = document.querySelectorAll('.likert-row');
        return radio.length;
    }
    function countSkillRatingChecked() {
        const checked = document.querySelectorAll('input[type="radio"]:checked');
        return checked.length;
    }
    function validatePreMiniTest() {
        console.log(countSkillRating(), + " " + countSkillRatingChecked());
        if (countSkillRatingChecked() !== countSkillRating()) {
            Toast.create(document.body, 'err', 'Please rate your skills.');
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

    
    function isInterestEmpty() {
        console.log(getAllInterest().length);
        if (!getAllInterest().length) {
            // alert('Please select at least one interest.');
            
            Toast.create(document.body, 'err', 'Please select at least one interest.');
            return false;
        }
        return true;
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

            const header = document.createElement('div');
            header.className = 'interest-header';
            likrt_interest.appendChild(header);

            // Interest title
            const title = document.createElement('h4');
            title.textContent = interest.toLowerCase().replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase());
            header.appendChild(title);

            const scaleHint = document.createElement('p');
            scaleHint.className = 'scale-hint';

            scaleHint.textContent =
                INTEREST_SCALE_TYPE[interest] === 'exposure'
                    ? 'Scale based on familiarity and exposure'
                    : 'Scale based on skill level';

            header.appendChild(scaleHint);

            const hidden_inp = document.createElement('input');
            hidden_inp.type = 'hidden';
            hidden_inp.name = `scale_type[${interest}]`;
            hidden_inp.value = INTEREST_SCALE_TYPE[interest] ?? 'skill_based';
            likrt_interest.appendChild(hidden_inp);


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

                    const likert_c = document.createElement('div');
                    likert_c.className = 'likert-choices';

                    const input = document.createElement('input');
                    input.type = 'radio';
                    input.name = `skills[${interest}][${skill}]`;
                    input.dataset.scaleType = INTEREST_SCALE_TYPE[interest] ?? 'skill_based'
                    input.value = value;
                    input.id = radioId;
                    input.required = true;

                    const radioLabel = document.createElement('label');
                    radioLabel.setAttribute('for', radioId);
                    radioLabel.textContent = scaleConfig.labels[index];

                    scale.appendChild(likert_c);
                    likert_c.appendChild(input);
                    likert_c.appendChild(radioLabel);
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


    let formStepsNum = 0;


    function getMiniTestTotals() {
        const totalQuestions = document.querySelectorAll('#mini-test-container .mini-question').length;
        const answeredQuestions = document.querySelectorAll('#mini-test-container input[type="radio"]:checked').length;
        return { totalQuestions, answeredQuestions };
    }

    function updateMiniTestSubmitState() {
        if (!submit_btn) return;
        const { totalQuestions, answeredQuestions } = getMiniTestTotals();
        const complete = totalQuestions > 0 && answeredQuestions === totalQuestions;
        submit_btn.disabled = !complete;
        submit_btn.classList.toggle('disabled', !complete);
    }

    function validateMiniTest() {
        const { totalQuestions, answeredQuestions } = getMiniTestTotals();

        if (totalQuestions === 0) {
            Toast.create(document.body, 'err', 'Mini test is not ready yet.');
            return false;
        }

        if (answeredQuestions !== totalQuestions) {
            Toast.create(document.body, 'err', 'Please answer all mini test questions.');

            const firstUnanswered = Array.from(
                document.querySelectorAll('#mini-test-container .mini-question')
            ).find(q => !q.querySelector('input[type="radio"]:checked'));

            if (firstUnanswered) {
                firstUnanswered.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            return false;
        }

        return true;
    }

    const miniTestContainer = document.getElementById('mini-test-container');
    if (miniTestContainer) {
        miniTestContainer.addEventListener('change', (e) => {
            if (e.target && e.target.matches('input[type="radio"]')) {
                updateMiniTestSubmitState();
            }
        });
    }


    


    function renderMiniTest(questions) {
        const container = document.getElementById('mini-test-container');
        container.innerHTML = '';

        questions.forEach((q, idx) => {
            const block = document.createElement('div');
            block.classList.add('mini-question');

            const quetionText = document.createElement('div');
            quetionText.className = 'question-container';

            const numberDiv = document.createElement('div');
            numberDiv.className = 'question-number';
            numberDiv.textContent = `${idx + 1}`;

            const optionsDiv = document.createElement('div');
            optionsDiv.className = 'mini-test-options';


            quetionText.innerHTML = `
                ${numberDiv.outerHTML}
                <p>${q.question}</p>
            `;

            block.appendChild(quetionText);
            block.appendChild(optionsDiv);
            
            optionsDiv.innerHTML = `
                ${q.options.map((opt, i) => `
                    <label>
                        <input type="radio"
                            name="minitest_answers[${idx}]"
                            data-interest="${q.interest}"
                            data-question-id="${q.question_id}"
                            data-correct="${q.correct ?? ''}"
                            value="${i}"
                            required>
                        <p>${opt}</p>
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

    let isSubmitting = false;

    async function handleFormSubmit(buttonID) {
        if (isSubmitting) return;

        try {
            isSubmitting = true;
            buttonID.disabled = true;
            showLoadingScreen();

            collectBasicInfo();
            collectSkillRatings();

            collectMiniTestAnswers();

            // inject JSON into hidden input
            document.getElementById('minitest-input').value =
                JSON.stringify(assessmentState.miniTest);

            const formData = new FormData(form);
            getInputs();
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

            const isJson = (res.headers.get('content-type') || '').includes('application/json');
            const data = isJson ? await res.json() : await res.text();


            console.log('Generate exam response:', data);

            

            startPolling(data.job_id);

        } catch (err) {
            console.error('Error starting exam generation:', err);
            alert('Error', err);
        }
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        handleFormSubmit(submit_btn);        
    });

    function autoSubmit(submit_btn) {
        if (timer > 0) {
            setTimeout(() => {
                console.log("Times up!")
                handleFormSubmit(submit_btn)
            }, timer * 1000);
        }
    }

    function startCountDown() {
        if (!timeInterval) {
            timeInterval = setInterval(() => {
                timer--
                updateDisplayTime();
            }, 1000)
        }
    }

    function updateDisplayTime() {
        const minutes = Math.floor(timer / 60);
        const remainingSeconds = timer % 60;
        document.getElementById('seconds').innerText = `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
    }
    
    function startPolling(jobId) {
        const interval = setInterval(() => {
            fetch(`/exam/status/${jobId}`)
                .then(res => res.json())
                .then(job => {
                    if (job.status === "pending") {
                        statusText.textContent = "Getting Ready..."
                    } else {
                        statusText.textContent = job.message + " " + job.progress + "%";
                    }
                    console.log('Polling job status:', job.status);
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