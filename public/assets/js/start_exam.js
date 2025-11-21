document.addEventListener('DOMContentLoaded', function() {
    const qCard = document.querySelectorAll('.question-card');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const navBtn = Array.from(document.querySelectorAll('.nav-q-btn'));

    const choices = document.querySelectorAll('.choices');

    const answers = new Array(qCard.length).fill(null);

    const questionData = Array.from({length: qCard.length}, (_, index) => ({
        index: index,
        keyAnswer: null,
        answer: null,
        startTime: null,
        endTime: null,
        duration: null,
    }));

    let currentQuestionIndex = 0;

    let seconds = 0;
    let timerInterval;
    const timerDisplay = document.getElementById('timerD');
    const displayF = document.getElementById('timerDF');

    function onQuestionViewed(index) {
        if (questionData[index].startTime === null) {
            questionData[index].startTime = seconds;
        }
    }

    function onAnswerSelected(index, value) {
        questionData[index].answer = value;
        questionData[index].endTime = seconds;

        if (questionData[index].startTime !== null) {
            questionData[index].duration = questionData[index].endTime - questionData[index].startTime;
        }
        console.log(`Answered Q${index}:`, questionData[index]);
    }


    function updateDisplay() {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
        displayF.textContent = `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
    }

    function startTimer() {
        if (!timerInterval) { // Prevent multiple intervals
            timerInterval = setInterval(() => {
                    seconds++
                    updateDisplay();
            }, 1000);
        }
    }

    function stopTimer() {
        clearInterval(timerInterval);
        timerInterval = null;
    }

    function resetTimer() {
        stopTimer();
        seconds = 0;
        updateDisplay();
    }

    function getAnsweredIndexes() {
        return questionData
            .map((q, i) => q.answer !== null ? i : null)
            .filter(i => i !== null);
    }

    function updateNavButtonsStatus() {
        const answeredIndex = getAnsweredIndexes();
        navBtn.forEach((li, i) => {
            if (answeredIndex.includes(i)) {
                li.classList.add('q-done');
                console.log("called")
            } else {
                li.classList.remove('q-done');
            }
        });
    }

    qCard.forEach(card => {
        const cardIndex = parseInt(card.dataset.index, 10);
        card.addEventListener('click', function(e) {
            const choiceLi = e.target.closest('.choices');
            if (!choiceLi) return;
            const radio = choiceLi.querySelector('.radio');
            if (!radio) return;

            // toggle active on siblings
            const siblingLis = card.querySelectorAll('.choices');
            siblingLis.forEach(li => li.classList.remove('active'));
            choiceLi.classList.add('active');
            
            // set radio and store answer
            radio.checked = true;
            answers[cardIndex] = radio.value;

            onAnswerSelected(cardIndex, radio.value)
            // debug
            // console.log('Stored answer for', cardIndex, answers[cardIndex]);
            setCheck(cardIndex, answers[cardIndex])
            // recordAnswerTime(cardIndex);
        });
    });

    choices.forEach(choice => {
        choice.addEventListener('click', function() {
            choice.classList.add('active')

            const childElm = choice.querySelector('.radio')
            childElm.checked = true
        });
    })

    document.querySelector('.questions').addEventListener('change', function(e) {
        const target = e.target;
        if (!target || !target.classList.contains('radio')) return;
        const card = target.closest('.question-card')
        if (!card) return;
        const questionIndex = parseInt(card.dataset.index, 10)
        answers[questionIndex] = target.value;

        recordAnswerTime(questionIndex);
    });

    function update_choices(index) {
        const card = qCard[index];
        if (!card) return;
        const stored = answers[index];
        card.querySelectorAll('.choices').forEach(li => {
            const r = li.querySelector('.radio');
            const isSelected = (r.value === stored);
            if (!r) return;
            r.checked = isSelected;
            if (isSelected) {
                li.classList.add('active-choice')
                // console.log("isSelected: ", isSelected, "Val: ", stored, "li: ", li.classList)
            } else {
                li.classList.remove('active-choice')
            }
        });
    }


    function updateNavButtons(index) {
        if (!Array.isArray(navBtn) || navBtn.length === 0) {
            console.warn('No navigation buttons found to update');
            return;
        }
        navBtn.forEach((button, i) => {
            if (i === index) {
                button.classList.add('active');
                // console.log('Adding active class to button index:', i);
            } else {
                button.classList.remove('active');
                // console.log('Removing active class to button index:', i);
            }
            const card = qCard[index];
            card.querySelectorAll('.choices').forEach((li, indx) => {
                if (li.checked) {
                    console.log("li: ", li, indx)
                    button.classList.add("active-choice")
                }
            });
        });
    }

    function setCheck(cardIndex) {
        navBtn.forEach((btn, indx) => {
            if (indx === cardIndex) {
                btn.classList.add('active')
            }
            if (btn.classList.contains('active')) {
            // PASS A CALLBACK FUNCTION to findIndex()
                const activeIndex = navBtn.findIndex(
                    (element) => element.classList.contains('active')
                );
                
                // console.log("Active Btn index: ", activeIndex);
            }
        })
    }




    function showQuestion(index) {
        // const keyAnswer = document.querySelector('.k')
        qCard.forEach((card, i) => {
            card.classList.toggle('active-q', i === index);
            // questionData[index].keyAnswer = keyAnswer.value;
        });

        prevBtn.disabled = index === 0;
        nextBtn.disabled = index === qCard.length - 1;
        // submitBtn.style.display = index === qCard.length - 1 ? 'inline-block' : 'none';

        updateNavButtons(index);
        updateNavButtonsStatus();
        update_choices(index)

        // if (!startTimes[index]) {
        //     startTimes[index] = seconds;  // record start time once
        //     console.log(`Started Q${index} at ${startTimes[index]}s`);
        // }

        // if (questionData[index].startTime === null)

        onQuestionViewed(index);
        startTimer();
        // console.log("QData: ", questionData[index]);
        console.log("QData: ", questionData);

        // const radios = qCard[index].querySelectorAll('.radio');
        // radios.forEach(r => r.checked = r.value === answers[index]);
    }

    prevBtn.addEventListener('click', function() {
        if (currentQuestionIndex > 0) {
            currentQuestionIndex--;
            showQuestion(currentQuestionIndex);
            updateNavButtons(currentQuestionIndex);
        }
    });

    nextBtn.addEventListener('click', function() {
        if (currentQuestionIndex < qCard.length - 1) {
            // if (!answerTimes[currentQuestionIndex] && answers[currentQuestionIndex] !== null) {
            //     recordAnswerTime(currentQuestionIndex);
            // } else if (!answerTimes[currentQuestionIndex] && answers[currentQuestionIndex] === null) {
            //     answerTimes[currentQuestionIndex] = Date.now();
            //     if (startTimes[currentQuestionIndex]) {
            //         durationsMs[currentQuestionIndex] = answerTimes[currentQuestionIndex] - startTimes[currentQuestionIndex];
            //     }
            // }
            currentQuestionIndex++;
            showQuestion(currentQuestionIndex);
            updateNavButtons(currentQuestionIndex);

        }
    });
    showQuestion(currentQuestionIndex);

    navBtn.forEach((button, index) => {
        button.addEventListener('click', function() {
            currentQuestionIndex = index;
            showQuestion(parseInt(button.dataset.index, 10));

            if (button.classList.contains('active')) {
                button.classList.add('active');
            } else {
                button.classList.remove('active');
            }

            console.log('Navigated to question index:', index);
        });
    });

    
});