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

function getRandomItems(array, count) {
    const shuffled = [...array].sort(() => 0.5 - Math.random());
    return shuffled.slice(0, count);
}


export function getSkillsForInterest(interest) {
    const skills = interestSkillMap[interest] || genericSkillSet;
    // if (skills.length() === 1) {
    //     return getRandomItems(skills, 5);
    // }
    // Unknown / Other interest
    return getRandomItems(skills, 2);
}


let miniTestQuestions = [];

miniTestQuestions = [
    // Coding interest questions
    {
        interest: 'coding',
        question: 'What does a loop do?',
        options: ['Repeat code', 'End program', 'Store data', 'Debug errors'],
        correct: 0
    },
    {
        interest: 'coding',
        question: 'What is a "Variable" used for?',
        options: ['Storing data values', 'Connecting to the internet', 'Formatting text', 'Increasing CPU speed'],
        correct: 0
    },
    {
        interest: 'coding',
        question: 'Which of these is a common Boolean value?',
        options: ['Maybe', 'True', 'Null', 'Undefined'],
        correct: 1
    },
    {
        interest: 'coding',
        question: 'What does an "If Statement" do?',
        options: ['Repeats code indefinitely', 'Defines a new variable', 'Executes code only if a condition is met', 'Deletes old files'],
        correct: 2
    },
    {
        interest: 'coding',
        question: 'What is a "Function" in programming?',
        options: ['A reusable block of code', 'A physical part of the keyboard', 'A type of computer virus', 'A mathematical error'],
        correct: 0
    },
    // Game Development interest questions
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
        interest: 'game_development',
        question: 'What does "NPC" stand for?',
        options: ['New Player Character', 'Non-Player Character', 'Network Protocol Controller', 'No Power Connection'],
        correct: 1
    },
    {
        interest: 'game_development',
        question: 'What is "Hitbox" used for?',
        options: ['Playing music', 'Calculating collision detection', 'Saving game progress', 'Adjusting brightness'],
        correct: 1
    },
    {
        interest: 'game_development',
        question: 'Which axis usually represents "depth" in a 3D game environment?',
        options: ['X-axis', 'Y-axis', 'Z-axis', 'W-axis'],
        correct: 2
    },
    {
        interest: 'game_development',
        question: 'What is a "Sprite" in 2D games?',
        options: ['A 3D model', 'A type of game console', 'A 2D bitmap/graphic', 'A sound effect'],
        correct: 2
    },
    // Software/Mobile Development interest questions
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
        interest: 'software_mobile_dev',
        question: 'What is an "API" used for?',
        options: ['Allowing two applications to communicate', 'Charging a phone battery', 'Taking photos', 'Changing the screen brightness'],
        correct: 0
    },
    {
        interest: 'software_mobile_dev',
        question: 'What does "UI" stand for?',
        options: ['User Interaction', 'Universal Integration', 'User Interface', 'Unit Identification'],
        correct: 2
    },
    {
        interest: 'software_mobile_dev',
        question: 'In mobile dev, what is "Beta Testing"?',
        options: ['Releasing the app to a small group for feedback', 'Writing the first line of code', 'Selling the app to a company', 'Deleting the app'],
        correct: 0
    },
    {
        interest: 'software_mobile_dev',
        question: 'What is the purpose of a "Push Notification"?',
        options: ['To turn off the phone', 'To send messages to the user even when the app is closed', 'To update the operating system', 'To clear the cache'],
        correct: 1
    },
    // Cybersecurity/Hacking interest questions
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
        interest: 'cybersec_hacking',
        question: 'What is "Phishing"?',
        options: ['Searching for fast internet', 'Fraudulent attempts to obtain sensitive info via email', 'A way to cool down servers', 'Fishing at a pond'],
        correct: 1
    },
    {
        interest: 'cybersec_hacking',
        question: 'What does a "Firewall" do?',
        options: ['Speeds up the CPU', 'Monitors and filters incoming/outgoing network traffic', 'Physically protects hardware from fire', 'Cleans the computer screen'],
        correct: 1
    },
    {
        interest: 'cybersec_hacking',
        question: 'What is "Two-Factor Authentication" (2FA)?',
        options: ['A password with two letters', 'Using two different computers', 'Requiring two forms of identification to log in', 'Deleting an account twice'],
        correct: 2
    },
    {
        interest: 'cybersec_hacking',
        question: 'What is "Malware"?',
        options: ['Software designed to cause damage to a computer or network', 'A tool for making websites', 'A type of hardware', 'A fast internet connection'],
        correct: 0
    },
    // Networking interest questions
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
        interest: 'networking',
        question: 'What does "IP" in IP Address stand for?',
        options: ['Internal Power', 'Internet Protocol', 'Instant Port', 'Interface Program'],
        correct: 1
    },
    {
        interest: 'networking',
        question: 'What is the "Cloud" primarily used for?',
        options: ['Weather forecasting', 'Storing and accessing data over the internet', 'Making hardware lighter', 'Filtering dust from fans'],
        correct: 1
    },
    {
        interest: 'networking',
        question: 'What does "LAN" stand for?',
        options: ['Large Area Network', 'Long Access Node', 'Local Area Network', 'Light Analog Network'],
        correct: 2
    },
    {
        interest: 'networking',
        question: 'What is the purpose of a "DNS"?',
        options: ['To translate domain names (like google.com) into IP addresses', 'To speed up the mouse cursor', 'To store images', 'To record audio'],
        correct: 0
    },
    // Robotics interest questions
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
        interest: 'building_robots',
        question: 'What does a "Proximity Sensor" do?',
        options: ['Detects how close an object is', 'Measures the weight of the robot', 'Changes the robot color', 'Plays music'],
        correct: 0
    },
    {
        interest: 'building_robots',
        question: 'Which part of the robot is considered the "Brain"?',
        options: ['The Wheels', 'The Battery', 'The Microcontroller', 'The Frame'],
        correct: 2
    },
    {
        interest: 'building_robots',
        question: 'What does "Degrees of Freedom" (DoF) refer to in robotics?',
        options: ['The cost of the robot', 'The number of independent ways a robot can move', 'The battery life', 'The size of the robot'],
        correct: 1
    },
    {
        interest: 'building_robots',
        question: 'What is "Teleoperation"?',
        options: ['A robot operating itself', 'Operating a robot remotely by a human', 'A robot building another robot', 'Charging a robot wirelessly'],
        correct: 1
    },
    // Data Analytics interest questions
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
        interest: 'data_analytics',
        question: 'What is a "Dataset"?',
        options: ['A collection of related data', 'A type of computer screen', 'A programming language', 'A way to fix a hard drive'],
        correct: 0
    },
    {
        interest: 'data_analytics',
        question: 'What is the "Mean" in a set of numbers?',
        options: ['The highest number', 'The middle number', 'The average value', 'The lowest number'],
        correct: 2
    },
    {
        interest: 'data_analytics',
        question: 'What is "Data Cleaning"?',
        options: ['Wiping the dust off a hard drive', 'Fixing or removing incorrect or incomplete data', 'Deleting all files', 'Formatting a disk'],
        correct: 1
    },
    {
        interest: 'data_analytics',
        question: 'What is a "Trend" in data?',
        options: ['A random error', 'A general direction in which something is developing or changing', 'A specific font style', 'A color scheme'],
        correct: 1
    },
    // UI/UX Design interest questions
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
        interest: 'ui_ux_designer',
        question: 'What does "UX" stand for?',
        options: ['User Experience', 'Unit X-factor', 'Universal Extension', 'User X-axis'],
        correct: 0
    },
    {
        interest: 'ui_ux_designer',
        question: 'What is a "Prototype"?',
        options: ['The final version of a product', 'A preliminary model used for testing', 'A type of code editor', 'A marketing plan'],
        correct: 1
    },
    {
        interest: 'ui_ux_designer',
        question: 'What is "Accessibility" in design?',
        options: ['How fast the site loads', 'Ensuring products can be used by everyone, including people with disabilities', 'The price of the app', 'The color of the logo'],
        correct: 1
    },
    {
        interest: 'ui_ux_designer',
        question: 'What is "White Space" in layout design?',
        options: ['Unused space that helps organize content', 'A mistake in the drawing', 'The background of the entire screen', 'A place to put ads'],
        correct: 0
    },
    // Video Production interest questions
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
        interest: 'videographer',
        question: 'What does "ISO" control in a camera?',
        options: ['The focus speed', 'The sensitivity of the sensor to light', 'The audio volume', 'The zoom level'],
        correct: 1
    },
    {
        interest: 'videographer',
        question: 'What is "B-Roll"?',
        options: ['The main interview footage', 'Supplemental or alternative footage intercut with the main shot', 'Deleted scenes', 'The credits at the end'],
        correct: 1
    },
    {
        interest: 'videographer',
        question: 'What is "White Balance" used for?',
        options: ['To make the video black and white', 'To ensure colors look natural under different lighting', 'To increase the frame rate', 'To adjust the audio pitch'],
        correct: 1
    },
    {
        interest: 'videographer',
        question: 'What does "Shutter Speed" affect?',
        options: ['Motion blur and exposure time', 'The camera weight', 'The storage format', 'The lens brand'],
        correct: 0
    },
    // Video Editing interest questions
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
        interest: 'editor',
        question: 'What is a "Jump Cut"?',
        options: ['A smooth transition between scenes', 'An abrupt transition that makes the subject appear to "jump"', 'Adding a title', 'Exporting a video'],
        correct: 1
    },
    {
        interest: 'editor',
        question: 'What is "Timeline" in video editing?',
        options: ['A list of filenames', 'The area where you arrange and layer your clips chronologically', 'The clock on the wall', 'A social media feed'],
        correct: 1
    },
    {
        interest: 'editor',
        question: 'What is "Foley" in post-production?',
        options: ['Creating custom sound effects for a film', 'Correcting the color', 'Adding subtitles', 'Cutting the video length'],
        correct: 0
    },
    {
        interest: 'editor',
        question: 'What does "Rendering" mean?',
        options: ['Deleting unused clips', 'Generating the final video file from the project', 'Recording new audio', 'Changing the camera settings'],
        correct: 1
    },
    // Graphic Design interest questions
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
        interest: 'graphic_design',
        question: 'What is a "Vector" image?',
        options: ['A photo made of pixels', 'An image based on mathematical paths that can be scaled infinitely', 'A blurry drawing', 'A video file'],
        correct: 1
    },
    {
        interest: 'graphic_design',
        question: 'What are "Complementary Colors"?',
        options: ['Colors that are next to each other', 'Colors that are opposite each other on the color wheel', 'Different shades of the same color', 'Colors that look boring'],
        correct: 1
    },
    {
        interest: 'graphic_design',
        question: 'What does "Hierarchy" mean in design?',
        options: ['Using only one font', 'Arranging elements to show importance', 'Using every color available', 'Saving a file as a PDF'],
        correct: 1
    },
    {
        interest: 'graphic_design',
        question: 'What is "Bleed" in print design?',
        options: ['A color error', 'Content that extends past the trim edge of the page', 'Ink leaking from the printer', 'A type of paper'],
        correct: 1
    },

    // AI/ML interest questions
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
    },
    {
        interest: 'ai_ml',
        question: 'What is a "Neural Network"?',
        options: ['A hardware repair tool', 'A computing system inspired by the human brain', 'A social media platform', 'A type of internet cable'],
        correct: 1
    },
    {
        interest: 'ai_ml',
        question: 'What is "Training" in AI?',
        options: ['Teaching a human how to use a computer', 'The process of providing data to an algorithm to help it learn', 'Fixing a broken monitor', 'Installing a new app'],
        correct: 1
    },
    {
        interest: 'ai_ml',
        question: 'What is "NLP" in the context of AI?',
        options: ['Normal Logic Processing', 'Natural Language Processing', 'New Laser Printer', 'Network Level Protocol'],
        correct: 1
    },
    {
        interest: 'ai_ml',
        question: 'What is a "Chatbot"?',
        options: ['A computer program that simulates human conversation', 'A person who types very fast', 'A mechanical robot in a factory', 'An email spam filter'],
        correct: 0
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

export function generateMiniTestQuestions(interests) {
    const selected = [];

    // SINGLE INTEREST → generate 5 questions
    if (interests.length === 1) {
        const interest = interests[0];

        const matches = miniTestQuestions.filter(
            q => q.interest === interest
        );

        const shuffled = [...matches].sort(() => 0.5 - Math.random());

        shuffled.slice(0, 5).forEach((q, index) => {
            selected.push({
                ...q,
                question_id: `${interest}-${index + 1}`
            });
        });

        while (selected.length < 5) {
            const generic = genericMiniTest[
                Math.floor(Math.random() * genericMiniTest.length)
            ];

            selected.push({
                ...generic,
                interest: 'other',
                original_interest: interest,
                question_id: `generic-${selected.length + 1}`,
                correct: null
            });
        }

        return selected;
    }

    // MULTIPLE INTERESTS → 1 question per interest
    interests.forEach((interest, i) => {
        // get ALL matching questions for this interest
        const matches = miniTestQuestions.filter(q => q.interest === interest);

        if (matches.length > 0) {
            // pick one random from matches
            const randomQ = matches[Math.floor(Math.random() * matches.length)];

            selected.push({
                ...randomQ,
                question_id: `${interest}-${i + 1}`
            });
        } else {
            const generic = genericMiniTest[
                Math.floor(Math.random() * genericMiniTest.length)
            ];

            selected.push({
                ...generic,
                interest: 'other',
                original_interest: interest,
                question_id: `generic-${i + 1}`,
                correct: null
            });
        }
    });


    return selected;
}

