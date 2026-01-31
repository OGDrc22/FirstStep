import traceback
import google.generativeai as genai
import json, re, os, sys
from dotenv import load_dotenv
import pymysql
import time

DEBUG_FILE = r"C:\xampp\htdocs\first-step\storage\logs\debug.txt"
db = None
cursor = None
job_id = None

sleep_t = 3
div3 = 0.3333

# ------------------ DB CONNECT ------------------
def ensure_db_connection():
    global db, cursor

    try:
        if db:
            db.ping(reconnect=True)
            return
    except Exception:
        pass

    db = pymysql.connect(
        host="127.0.0.1",
        user="root",
        password="",
        database="firststep",
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
    with open(DEBUG_FILE, "a", encoding="utf-8") as f:
        f.write(f"🔄 values: {values}\n")
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

    # print(json.dumps(parse_questions(response.text), indent=2))

    return parsed




def main():
    global job_id

    ensure_db_connection()

    job_id = int(sys.argv[1])
    
    with open(DEBUG_FILE, "a", encoding="utf-8") as f:
        f.write(f"🎯 Received job ID: {sys.argv[1]}\n")

    update_job(
        'started',
        message="Preparing Environment...",
        progress=0
    )
    time.sleep(sleep_t)

    load_dotenv()
    api_key = os.getenv("GOOGLE_API_KEY")
    if not api_key:
        raise Exception("GOOGLE_API_KEY is missing")
    genai.configure(api_key=api_key)
    model = genai.GenerativeModel('gemini-2.5-flash')

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

    update_job(
        'processing',
        message="Reading your inputs.",
        progress=40
    )    
    time.sleep(div3)

    interest = ", ".join(payload_.get("interest", []))

    update_job(
        'processing',
        message="Generating questions...",
        progress=50
    )
    time.sleep(div3)

    prompt = ("Generate 20 multiple-choice questions in NCAE format based on this interest: "  + interest + ", divided into 4 categories:\n"
                "1-5: Information Technology\n"
                "6-10: Computer Science\n"
                "11-15: Computer Engineering\n"
                "16-20: Multimedia Arts\n"
                "Each question must follow this exact format:\n"
                "Question <number>: <question text>\n"
                "A. <option>\n"
                "B. <option>\n"
                "C. <option>\n"
                "D. <option>\n"
                "Key: <A/B/C/D>\n"
                "Rules:\n"
                "Provide exactly four choices per question.\n"
                "The correct answer must be labeled using 'Key: <letter>'.\n"
                "Do NOT include explanations.\n"
                "Follow the arragement of category.\n"
                "Keep the questions appropriate for senior high school / NCAE level."
                )

    response = model.generate_content(prompt)

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

    update_job(
        'processing',
        message="Parsing questions...",
        progress=100
    )


    parsed_questions = parse_questions(response.text)

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
        with open(DEBUG_FILE, "a", encoding="utf-8") as f:
            f.write(f"❌ Error in main: {e}\n")
            f.write(traceback.format_exc() + "\n")
        sys.exit(1)

        try:
            update_job('failed', error=str(e))
        except Exception:
            pass
    finally:
        if db:
            db.close()