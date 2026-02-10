# %%
import pandas as pd
import numpy as np
from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import LabelEncoder
import json, sys

# df = pd.read_csv('C:/xampp/htdocs/first-step/public/assets/scripts/files/processed_data.csv')
df = pd.read_csv('C:/xampp/htdocs/first-step/public/assets/scripts/files/processed_data.csv')

X = df[['score_IT', 'score_CS', 'score_CE', 'score_MMA', 'accuracy_score', 'interest_score', 'performance_encoded', ]]
y = df['track_encoded']

X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

model = RandomForestClassifier(n_estimators=500, max_depth=10, min_samples_split=2, min_samples_leaf=13, random_state=42, class_weight='balanced')
model.fit(X_train, y_train)

accuracy = model.score(X_test, y_test)
accuracy_2f = f"{accuracy:.2f}"

# %%

le_track = LabelEncoder()
le_track.fit(df["Track"])

# %%
try:
    payload = json.load(sys.stdin)
except Exception as e:
    print(json.dumps({"error": str(e)}))
    sys.exit(1)

answers = payload["answers"]
keys = payload["keys"]
data = payload["questionsData"]

total_questions = len(keys)

has_err = False
predicted_track = ["Unknown"]
if isinstance(answers, dict):
    temp = {}
    for k, v in answers.items():
        try:
            temp[int(k)] = v
        except:
            continue

    answers = [temp.get(i, None) for i in range(total_questions)]
    
if answers is None:
    has_err = True
    print(json.dumps({
        "error": "No answers provided",
        "score": 0,
        "total": 0,
        "predicted_track": "Unknown",
        "qData": [],
        "keys": [],
        "category_scores": {},
        "interest_score": 0,
        "performance_group": "Unknown",
        "accuracy": "0.00",
        "accuracy_per_category": {},
        "duration_per_category": {},
    }))
    sys.exit(0)

categories = {
    "IT": range(1, 6),
    "CS": range(6, 11),
    "CE": range(11, 16),
    "MMA": range(16, 21)
}

category_scores = {k: 0 for k in categories}
average_times = []

for i in range(total_questions):

    if i >= len(data):
        # Create placeholder if data is missing
        data.append({"index": i, "duration": None})

    q_num = data[i]["index"] + 1



    st_ans = answers[i] if i < len(answers) else None
    correct_k = keys[i] if i < len(keys) else None
    correct = st_ans == correct_k
    data[i]["correct"] = correct 

    for cat, rng in categories.items():
        if q_num in rng and correct:
            category_scores[cat] += 1

    duration = data[i].get("duration")
    if duration is not None:
        average_times.append(duration)


if len(average_times) == 0:
    average_speed = 30
else:
    average_speed = np.mean(average_times)


# speed_score = (60 - average_speed) / 55
speed_score = max(0, min(1, (60 - average_speed) / 60))
total_correct = sum(category_scores.values())
if total_questions == 0:
    accuracy_score_student = 0
else:
    accuracy_score_student = total_correct / total_questions
interest_score = 0.6 * accuracy_score_student + 0.4 * speed_score

if accuracy_score_student < 0.4:
    performance_group = 'Low'
elif accuracy_score_student < 0.7:
    performance_group = 'Average'
else:
    performance_group = 'High'

le_perf = LabelEncoder()
le_perf.fit(["Low", "Average", "High"])
performance_encoded = le_perf.transform([performance_group])[0]

new_student = pd.DataFrame({
    "score_IT": [category_scores["IT"]],
    "score_CS": [category_scores["CS"]],
    "score_CE": [category_scores["CE"]],
    "score_MMA": [category_scores["MMA"]],
    "accuracy_score": [accuracy_score_student],
    "interest_score": [interest_score],
    "performance_encoded": [performance_encoded]
})



# --- Calculate accuracy per category ---
category_accuracy = {}
for cat, score in category_scores.items():
    total_in_category = len(list(rng))
    correct_in_category = category_scores[cat]
    if total_in_category > 0:
        category_accuracy[cat] = round(correct_in_category / total_in_category, 2)
    else:
        category_accuracy[cat] = 0.0


# Initialize
category_time = {cat: [] for cat in categories}

for i in range(total_questions):
    q_num = data[i]["index"] + 1
    duration = data[i].get("duration")
    if duration is None:
        continue  # skip if no duration

    # Assign duration to category
    for cat, rng in categories.items():
        if q_num in rng:
            category_time[cat].append(duration)
            break  # a question belongs to only 1 category

# Compute average time per category
time_per_category = {}
for cat, durations in category_time.items():
    if len(durations) > 0:
        time_per_category[cat] = sum(durations)
    else:
        time_per_category[cat] = 0.0



# --- Predict track ---
# predicted_track_encoded = model.predict(new_student)
# predicted_track = le_track.inverse_transform(predicted_track_encoded)

# --- Predict probabilities ---
probabilities = model.predict_proba(new_student)[0]

# Encoded class labels used by the model
track_labels_encoded = model.classes_

# Decode to actual track names
track_labels = le_track.inverse_transform(track_labels_encoded)

# Build percentage dictionary
track_percentage = {}

for track, prob in zip(track_labels, probabilities):
    track_percentage[track] = round(prob * 100, 2)

# Best track (highest probability)
best_index = np.argmax(probabilities)
predicted_track = track_labels[best_index]

print(json.dumps({
    "score": total_correct,
    "total": total_questions,
    "category_scores": category_scores,
    "predicted_track": predicted_track,
    "track_percentage": track_percentage,
    "qData": data,
    "keys": keys,
    "accuracy_per_category": category_accuracy,
    "duration_per_category": time_per_category,
    "interest_score": interest_score,
    "performance_group": performance_group,
    "accuracy": accuracy_2f
}))


