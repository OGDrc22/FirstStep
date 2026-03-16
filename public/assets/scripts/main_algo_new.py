import pandas as pd
import numpy as np
from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import LabelEncoder
import json, sys
from pathlib import Path



DEBUG_FILE = r"C:\xampp\htdocs\first-step\storage\logs\main_algo_debug.txt"


# ------------------------------
# 1. LOAD DATA & TRAIN MODEL
# ------------------------------

SCRIPT_DIR = Path(__file__).parent
data_path = SCRIPT_DIR / "files" / "ai_training_dataset_10000_rows.csv"

df = pd.read_csv(data_path)

FEATURE_COLUMNS = [
    "logic","syntax","algorithm","hardware","networking",
    "system_org","creativity","ui_design","attention_detail","problem_solving",

    "logic_speed","syntax_speed","algorithm_speed","hardware_speed","networking_speed",
    "system_org_speed","creativity_speed","ui_design_speed","attention_detail_speed","problem_solving_speed",

    "logic_confidence","syntax_confidence","algorithm_confidence","hardware_confidence","networking_confidence",
    "system_org_confidence","creativity_confidence","ui_design_confidence","attention_detail_confidence","problem_solving_confidence",

    "IT_accuracy","CS_accuracy","CE_accuracy","MMA_accuracy",

    "IT_time_ratio","CS_time_ratio","CE_time_ratio","MMA_time_ratio"
]

X = df[FEATURE_COLUMNS]
y = df["Track"]

X_train, X_test, y_train, y_test = train_test_split(
    X, y, test_size=0.2, random_state=42
)

model = RandomForestClassifier(
    n_estimators=500,
    max_depth=10,
    min_samples_leaf=13,
    class_weight="balanced",
    random_state=42
)

model.fit(X_train, y_train)

accuracy = model.score(X_test, y_test)


# Label decoder
# le_track = LabelEncoder()
# le_track.fit(df["Track"])


# ------------------------------
# 2. RECEIVE INPUT FROM LARAVEL
# ------------------------------

try:
    payload = json.load(sys.stdin)
except Exception as e:
    print(json.dumps({"error": str(e)}))
    sys.exit(1)

features = payload["features"]

with open(DEBUG_FILE, "a", encoding="utf-8") as f:
        f.write(f"🔄 features: {len(features)}\n")
        f.write(f"🔄 FEATURE_COLLUMNS: {len(FEATURE_COLUMNS)}\n")

# ------------------------------
# 3. BUILD FEATURE VECTOR
# ------------------------------

# competency scores from Laravel
# competency_scores = exam["competency_scores"]

# logic = competency_scores.get("logical_reasoning", 0)
# syntax = competency_scores.get("syntax_analysis", 0)
# algorithm = competency_scores.get("algorithmic_thinking", 0)
# hardware = competency_scores.get("hardware_systems", 0)
# networking = competency_scores.get("networking_systems", 0)
# system_org = competency_scores.get("system_organization", 0)
# creativity = competency_scores.get("digital_creativity", 0)
# ui_design = competency_scores.get("ui_design", 0)
# attention_detail = competency_scores.get("attention_to_detail", 0)
# problem_solving = competency_scores.get("problem_solving", 0)


# # category accuracy
# accuracy_per_cat = exam["accuracy_per_category"]

# IT_accuracy = accuracy_per_cat.get("Information Technology", 0)
# CS_accuracy = accuracy_per_cat.get("Computer Science", 0)
# CE_accuracy = accuracy_per_cat.get("Computer Engineering", 0)
# MMA_accuracy = accuracy_per_cat.get("Multimedia Arts", 0)


# create dataframe for model
try:
    new_student = pd.DataFrame(
        [features],
        columns=FEATURE_COLUMNS
    )
    with open(DEBUG_FILE, "a", encoding="utf-8") as f:
         f.write(f"new_student count: {len(new_student)}\n")
except Exception as e:   
    with open(DEBUG_FILE, "a", encoding="utf-8") as f:
            f.write(f"🎯 New_Student: {e}\n")

# ------------------------------
# 4. PREDICT
# ------------------------------

try:

    probabilities = model.predict_proba(new_student)[0]

    track_labels = model.classes_

    track_percentage = {}
    for track, prob in zip(track_labels, probabilities):
        track_percentage[track] = round(prob * 100, 2)
except Exception as e:
    with open(DEBUG_FILE, "a", encoding="utf-8") as f:
            f.write(f"🎯 sorted_tracks: {e}\n")

# best_index = np.argmax(probabilities)
# predicted_track = track_labels[best_index]

sorted_tracks = sorted(track_percentage.items(), key=lambda x: x[1], reverse=True)

with open(DEBUG_FILE, "a", encoding="utf-8") as f:
        f.write(f"🎯 sorted_tracks: {sorted_tracks}\n")

primary_recommendation = sorted_tracks[0]
secondary_recommendation = sorted_tracks[1]


# ------------------------------
# 5. OUTPUT
# ------------------------------

output = {
    "track_percentage": track_percentage,
    "predicted_track": primary_recommendation,
    "secondary_track": secondary_recommendation,
    "model_accuracy": round(accuracy, 2)
}

print(json.dumps(output))