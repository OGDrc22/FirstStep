import traceback
import google.generativeai as genai
import json, re, os, sys
from dotenv import load_dotenv
import pymysql
import time

db = None
cursor = None
job_id = None

sleep_t = 3
div3 = 0.3333

# ------------------ DB CONNECT ------------------
def ensure_db_connection():
    print("Connecting to TiDB...", flush=True)


    global db, cursor

    try:
        if db:
            db.ping(reconnect=True)
            return
    except Exception:
        pass

    db = pymysql.connect(
        host=os.getenv('DB_HOST'),
        user=os.getenv('DB_USERNAME'),
        password=os.getenv('DB_PASSWORD'),
        database=os.getenv('DB_DATABASE'),
        ssl={'ca': os.getenv('MYSQL_ATTR_SSL_CA')},
        charset="utf8mb4",
        cursorclass=pymysql.cursors.DictCursor
    )
    cursor = db.cursor()





# ------------------ ARGUMENT CHECK ------------------
if len(sys.argv) < 2:
    raise Exception("Usage: gemini.py <job_id>")



# ------------------ WRITE STATUS FUNCTION ------------------
def update_job(status, *, output=None, error=None, message=None, progress=None):
    fields = ["status = %s"]
    values = [status]


    if output is not None:
        fields.append("output = %s")
        values.append(json.dumps(output))
    
    if error is not None:
        fields.append("error_message = %s")
        values.append(error)

    if message is not None:
        fields.append("message = %s")
        values.append(message)

    if progress is not None:
        fields.append("progress = %s")
        values.append(progress)

    values.append(job_id)

    sql = f"UPDATE exam_jobs SET {', '.join(fields)} WHERE id = %s"


    cursor.execute(sql, values)
    db.commit()



# ------------ Parse the response -------------
def parse_questions(text):

    if not isinstance(text, str):
        raise TypeError(f"parse_questions expected str but got {type(text)}")

    text = re.sub(r'\*\*', '', text)
    text = re.sub(r'\-{3,}', '\n', text)
    text = text.strip()

    parts = re.split(r'(?i)question\s*\d*[:.-]?|\n?\d+\.', text)
    parsed = []

    for part in parts:
        part = part.strip()
        if not part:
            continue

        # Extract question line and choices
        lines = [line.strip() for line in part.splitlines() if line.strip()]
        question_text = ""
        choices = []
        key = None

        for line in lines:
            if re.match(r'^[A-D]\.', line):
                choices.append(line)
            elif re.match(r'Key\s*[:.-]?', line):
                match = re.search(r'Key:\s*[:.-]?\s*([A-D])', line, re.I)
                if match:
                    key = match.group(1).upper()
            
            else:
                question_text += line + " "

        if question_text and choices:
            parsed.append([question_text.strip(), choices, key])


    return parsed




def main():
    print("Main Python Called", flush=True)

    global job_id

    ensure_db_connection()

    job_id = int(sys.argv[1])

    update_job(
        'processing',
        message="Preparing Environment...",
        progress=0
    )
    time.sleep(sleep_t)

    api_key = os.getenv("GOOGLE_API_KEY")
    if not api_key:
        raise Exception("GOOGLE_API_KEY is missing")
    genai.configure(api_key=api_key)
    generation_config = {
        "temperature": 1,
        "top_p": 0.95,
        "top_k": 40,
        "response_mime_type": "application/json", # <--- THIS IS THE KEY SETTING
    }
    model = genai.GenerativeModel(
        model_name="gemini-3.1-flash-lite-preview",
        generation_config=generation_config,
    )

    update_job(
        'processing',
        message="Environment ready.",
        progress=10
    )    
    time.sleep(sleep_t)

    cursor.execute("SELECT payload FROM exam_jobs WHERE id = %s", (job_id,))
    row = cursor.fetchone()

    update_job(
        'processing',
        message="Reading your inputs.",
        progress=40
    )    
    time.sleep(div3)

    if not row or not row['payload']:
        raise Exception("Payload not found")
    
    update_job(
        'processing',
        message="Reading your inputs.",
        progress=40
    )    
    time.sleep(div3)
    
    payload_ = row['payload']

    if isinstance(payload_, str):
        payload_ = json.loads(payload_)


    # Normalize interest 
    if isinstance(payload_, dict):
        interests = payload_.get("interest")


    elif isinstance(payload_, list):
        # Case 1: list of interests directly
        if all(isinstance(i, str) for i in payload_):
            interests = payload_

        # Case 2: list of objects with "interest"
        else:
            interests = []
            for item in payload_:
                if isinstance(item, dict) and "interest" in item:
                    val = item["interest"]
                    if isinstance(val, list):
                        interests.extend(val)
                    else:
                        interests.append(val)

    else:
        interests = None

    if not interests:
        raise Exception("Interest not found in payload")

    if isinstance(interests, str):
        interests = [interests]

    interest_text = ", ".join(interests)





    update_job(
        'processing',
        message="Generating questions...",
        progress=50
    )
    time.sleep(div3)

    prompt = f"""
    Act as an Exam Content Generator for a Grade 10 Career Aptitude Assessment (similar to the NCAE).
    The goal is to measure interest and basic reasoning for students who have not yet chosen a specialization.

    ### TARGET AUDIENCE
    - 10th Grade students (minimal technical background).
    - Avoid: Advanced programming, complex algorithms, or college-level engineering.

    ### CONTENT SCOPE
    - **Interests to incorporate:** {interest_text}
    - **Categories:** Information Technology, Computer Science, Computer Engineering, Multimedia Arts.
    - **Allowed Competencies (Pick exactly 2 per question):** logical_reasoning, syntax_analysis, algorithmic_thinking, hardware_systems, networking_systems, system_organization, digital_creativity, ui_design, problem_solving, attention_to_detail.

    ### QUESTION QUANTITY & DISTRIBUTION
    - Generate EXACTLY 20 questions.
    - Distribute evenly (5 questions per category).

    ### STRICT TYPE DEFINITIONS
    1. **Objective**: Testing facts or reasoning. MUST have a correct "answer" (A, B, C, or D).
    2. **Situational**: Testing application in a scenario. MUST have a correct "answer".
    3. **Preference**: Testing interest. "answer" must be an empty string "". MUST include "choice_equivalent" mapping each option to one of the 4 Categories.

    ### OUTPUT FORMAT RULES
    - Output ONLY valid JSON.
    - No conversational filler, no markdown code blocks (unless requested), no explanations.
    - Follow this schema exactly:

    {{
    "questions": [
        {{
        "number": 1,
        "type": "Objective",
        "category": "Computer Science",
        "competencies": ["algorithmic_thinking", "problem_solving"],
        "question": "Which of the following is a step-by-step instruction to solve a problem?",
        "choices": {{
            "A": "A variable",
            "B": "An algorithm",
            "C": "A hardware",
            "D": "A screen"
        }},
        "answer": "B"
        }},
        {{
        "number": 2,
        "type": "Preference",
        "category": "",
        "competencies": ["digital_creativity", "ui_design"],
        "question": "Which activity would you enjoy doing the most on a weekend?",
        "choices": {{
            "A": "Setting up a home Wi-Fi network",
            "B": "Writing a simple code to automate a task",
            "C": "Taking apart a broken radio to see how it works",
            "D": "Designing a digital poster for a school event"
        }},
        "answer": "",
        "choice_equivalent": {{
            "A": "Information Technology",
            "B": "Computer Science",
            "C": "Computer Engineering",
            "D": "Multimedia Arts"
        }}
        }}
    ]
    }}

    ### NEGATIVE CONSTRAINTS
    - DO NOT use "Logical reasoning" or "Knowledge" as a 'type'.
    - DO NOT include "choice_equivalent" in Objective or Situational questions.
    - DO NOT mix "Preference" questions with a correct answer.
    - DO NOT use technical jargon like 'asynchronous' or 'polymorphism'.
    """

    response = model.generate_content(prompt)
    print("Sending request to Gemini...", flush=True)



    update_job(
        'processing',
        message="Generating questions...",
        progress=60
    )
    time.sleep(div3)

    update_job(
        'processing',
        message="Generating questions...",
        progress=70
    )
    time.sleep(div3)

    

    raw_text = response.text.strip()

    # Remove ```json and ``` wrappers
    # if raw_text.startswith("```"):
    #     raw_text = raw_text.replace("```json", "").replace("```", "").strip()

    # parsed_questions = parse_questions(response.text)
    try:
        parsed_questions = json.loads(raw_text)
        update_job(
            'processing',
            message="Parsing questions...",
            progress=100
        )
    except json.JSONDecodeError as e:
        raise Exception(f"Failed to parse AI output: {e}")

        

    update_job(
        'done',
        output=parsed_questions
    )



if __name__ == "__main__":
    try:
        if len(sys.argv) < 2:
            raise ValueError("Missing job ID argument")
        main()
    except Exception as e:
        print(f"PYTHON CRASH: {str(e)}", flush=True)
        print(traceback.format_exc(), flush=True)

        try:
            update_job('failed', error=str(e))
        except Exception:
            pass
    finally:
        if db:
            db.close()