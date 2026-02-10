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

assessment = payload["assessment"]

exam = payload["exam_metrics"]

accuracy_per_cat = exam["accuracy_per_category"]

TOTAL_PER_CAT = {
    "Information Technology": 5,
    "Computer Science": 5,
    "Computer Engineering": 5,
    "Multimedia Arts": 5
}

score_IT = accuracy_per_cat.get("Information Technology", 0) * TOTAL_PER_CAT["Information Technology"]
score_CS = accuracy_per_cat.get("Computer Science", 0) * TOTAL_PER_CAT["Computer Science"]
score_CE = accuracy_per_cat.get("Computer Engineering", 0) * TOTAL_PER_CAT["Computer Engineering"]
score_MMA = accuracy_per_cat.get("Multimedia Arts", 0) * TOTAL_PER_CAT["Multimedia Arts"]

accuracy_score_student = exam["accuracy"]

interest_sum = 0
count = 0

for item in assessment:
    if item["type"] != "known":
        continue

    skills = item["skills"]
    mini = item["mini_test"]

    interest_sum += (
        skills["average_score"] *
        skills["weight"] *
        mini["accuracy"]
    )
    count += 1

interest_score = interest_sum / count if count > 0 else 0



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
    "score_IT": [score_IT],
    "score_CS": [score_CS],
    "score_CE": [score_CE],
    "score_MMA": [score_MMA],
    "accuracy_score": [accuracy_score_student],
    "interest_score": [interest_score],
    "performance_encoded": [performance_encoded]
})

category_scores = {
    score_IT,
    score_CS,
    score_CE,
    score_MMA
}


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
def make_json_safe(obj):
    if isinstance(obj, set):
        return list(obj)
    if isinstance(obj, dict):
        return {k: make_json_safe(v) for k, v in obj.items()}
    if isinstance(obj, list):
        return [make_json_safe(v) for v in obj]
    return obj

output = {
    "track_percentage": make_json_safe(track_percentage),
    "category_scores": make_json_safe(category_scores),
    "predicted_track": make_json_safe(predicted_track),
    "interest_score": make_json_safe(interest_score),
    "performance_group": make_json_safe(performance_group),
    "sys_accuracy": accuracy_2f
}

print(json.dumps(output))
