import google.generativeai as genai
import json, re, os, sys
from dotenv import load_dotenv



# ------------ Configure the API -------------
load_dotenv()
my_key = os.getenv("GOOGLE_API_KEY")
genai.configure(api_key=my_key)
model = genai.GenerativeModel('gemini-2.5-flash')


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



# ------------------ ARGUMENT CHECK ------------------
if len(sys.argv) < 3:
    raise Exception("Usage: gemini.py <payload_file> <output_file>")

payload_file = sys.argv[1]
output_file = sys.argv[2]

def write_status(status, message, data=None) :
    tmp = output_file + ".tmp"
    with open(tmp, 'w', encoding='utf-8') as f:
        json.dump({
            "status": status,
            "message": message,
            "data": data
        }, f, indent=2)
    os.replace(tmp, output_file)


# %%
# try:
#     payload = json.load(sys.stdin)
# except Exception as e:
#     print(json.dumps({"error": str(e)}))
#     sys.exit(1)

# interest = payload.get("interest", "general")

# ------------------ MAIN ------------------
try:
    write_status('processing', 'Exam generation in progress...')
    
    with open(payload_file, 'r', encoding='utf-8') as f:
        payload = json.load(f)

    interests = payload.get("interests", [])
    interest = ", ".join(interests) if isinstance(interests, list) else str(interests)

    # write_status('processing', 'Connecting to Gemini API...')
    write_status('processing', 'Connecting to API...')

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
    # "add catergory label before each question."

    response = model.generate_content(prompt)

    write_status('processing', 'Parsing questions from response...')
    
    parsed_questions = parse_questions(response.text)

    write_status('done', 'Exam ready!.', parsed_questions)
    # answer_key = extraxct_key(parsed_questions)
    # user_responses = ['A', 'B', 'C']
    # grading_result = grade_responses(parsed_questions, user_responses)

    # Final array structure
    # output = {
    #     "status": "success",
    #     "data": parsed_questions,
    #     # "answer_key": answer_key,
    #     # "grading_result": grading_result,
    #     "error": None
    # }

except Exception as e:
    # output = {
    #     "status": "error",
    #     "data": None,
    #     "error": str(e)
    # }
    write_status('error', 'An error occurred during exam generation.', str(e))



# print(json.dumps(output))
