import json
import subprocess
import sys


def extraxct_key(parsed_questions):
    answer_key = {}
    for indx, question in enumerate(parsed_questions):
        answer_key[indx] = question[2]
    return answer_key


def grade_responses(parsed_questions, student_answers):
    answer_key = extraxct_key(parsed_questions)
    correct_count = 0
    results = []

    for indx, student_answers in enumerate(student_answers):
        correct_answer = answer_key.get(indx)
        is_correct = student_answers.upper() == correct_answer
        if is_correct:
            correct_count += 1
        results.append({
            "question_index": indx,
            "user_response": student_answers.upper(),
            "correct_answer": correct_answer,
            "is_correct": is_correct
        })

    sc_percentage = (correct_count / len(student_answers)) * 100 if student_answers else 0
    return {
        "score": correct_count,
        "total": len(student_answers),
        "percentage": round(sc_percentage, 2),
        "results": results
    }


proc = subprocess.run([sys.executable, "public/assets/scripts/gemini.py"],
                      capture_output=True, text=True, check=True)
data = json.loads(proc.stdout)   # parse JSON printed by gemini.py

parsed_questions = data["data"]

try:
    print(json.dumps(parsed_questions, indent=2))
    student_answers = ['A', 'B', 'C']  # Example student answers
    grading_result = grade_responses(parsed_questions, student_answers)
    print(json.dumps(grading_result, indent=2))
except Exception as e:
    print(f"Error during grading: {e}")