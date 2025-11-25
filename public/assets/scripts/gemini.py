import google.generativeai as genai
import json
import re
from dotenv import load_dotenv
import os

# Configure the API
load_dotenv()
my_key = os.getenv("GOOGLE_API_KEY")
genai.configure(api_key=my_key)
model = genai.GenerativeModel('gemini-2.5-flash')

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





try:
    prompt = ("Generate 3 multiple-choice IT-related questions for NCAE format.\n"
                  "Each question must be formatted like:\n"
                  "Question 1: <question text>\n"
                  "A. <option>\nB. <option>\nC. <option>\nD. <option>\nKey: <A/B/C/D>\n\n"
                  "Make sure answers are labeled with A-D and include 'Key: <letter>'.")
    response = model.generate_content(prompt)

    # print(response.text)

    parsed_questions = parse_questions(response.text)

    # answer_key = extraxct_key(parsed_questions)
    # user_responses = ['A', 'B', 'C']
    # grading_result = grade_responses(parsed_questions, user_responses)

    # Final array structure
    output = {
        "status": "success",
        "data": parsed_questions,
        # "answer_key": answer_key,
        # "grading_result": grading_result,
        "error": None
    }

except Exception as e:
    output = {
        "status": "error",
        "data": None,
        "error": str(e)
    }

print(json.dumps(output))
