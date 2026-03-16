# %%
import pandas as pd
import numpy as np
from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split
import json, sys
from pathlib import Path

# ------------------------------
# 1. LOAD DATA & TRAIN MODEL
# ------------------------------

SCRIPT_DIR = Path(__file__).parent
data_path = SCRIPT_DIR / "files" / "ai_training_dataset_1000_rows.csv"

df = pd.read_csv(data_path)

FEATURE_COLUMNS = [
    "logic", "syntax", "algorithm", "hardware", "networking",
    "system_org", "creativity", "ui_design", "attention_detail",
    "problem_solving", "IT_accuracy", "CS_accuracy", "CE_accuracy", "MMA_accuracy",
]

X = df[FEATURE_COLUMNS]
y = df["Track"]

X_train, X_test, y_train, y_test = train_test_split(
    X, y, test_size=0.2, random_state=42
)

# --- DEBUG BREAKPOINT 1: Verify data split ---
# breakpoint() 

model = RandomForestClassifier(
    n_estimators=500,
    max_depth=10,
    min_samples_leaf=13,
    class_weight="balanced",
    random_state=42
)

model.fit(X_train, y_train)
accuracy = model.score(X_test, y_test)

# ------------------------------
# 3. BUILD FEATURE VECTOR
# ------------------------------

# Static values for testing
logic, syntax, algorithm = 0.45, 0.52, 0.85
hardware, networking, system_org = 0.38, 0.24, 0.47
creativity, ui_design, attention_detail, problem_solving = 0.69, 0.55, 0.56, 0.76
IT_accuracy, CS_accuracy, CE_accuracy, MMA_accuracy = 0.53, 0.84, 0.46, 0.25

new_student = pd.DataFrame(
    [[logic, syntax, algorithm, hardware, networking, system_org, 
      creativity, ui_design, attention_detail, problem_solving, 
      IT_accuracy, CS_accuracy, CE_accuracy, MMA_accuracy]],
    columns=FEATURE_COLUMNS
)

# ------------------------------
# 4. PREDICT
# ------------------------------

probabilities = model.predict_proba(new_student)[0]
track_labels = model.classes_

# --- BUG FIX: Changed ':' to '=' for dictionary assignment ---
track_percentage = {}
for track, prob in zip(track_labels, probabilities):
    track_percentage[track] = round(float(prob) * 100, 2)

# --- DEBUG BREAKPOINT 2: Check probability mapping ---
# breakpoint() 

sorted_tracks = sorted(track_percentage.items(), key=lambda x: x[1], reverse=True)
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
# %%
