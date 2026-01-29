# %% DEBUG VERSION – FULLY RUNNABLE
import pandas as pd
import numpy as np
from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import LabelEncoder
import json

# -------------------------------
# MOCK TRAINING DATA
# -------------------------------
df = pd.DataFrame({
    "score_IT":  [3, 4, 1, 5, 2, 4, 1, 5],
    "score_CS":  [2, 5, 4, 1, 3, 4, 5, 1],
    "score_CE":  [1, 2, 5, 1, 4, 2, 4, 1],
    "score_MMA": [4, 1, 2, 3, 1, 5, 1, 4],
    "accuracy_score": [0.6, 0.8, 0.7, 0.9, 0.5, 0.85, 0.65, 0.95],
    "interest_score": [0.55, 0.78, 0.72, 0.88, 0.50, 0.82, 0.68, 0.92],
    "performance_encoded": [1, 2, 1, 2, 0, 2, 1, 2],
    "Track": ["IT", "CS", "CE", "IT", "MMA", "CS", "CE", "IT"]
})

# -------------------------------
# ENCODE TARGET
# -------------------------------
le_track = LabelEncoder()
df["track_encoded"] = le_track.fit_transform(df["Track"])

X = df[[
    "score_IT", "score_CS", "score_CE", "score_MMA",
    "accuracy_score", "interest_score", "performance_encoded"
]]
y = df["track_encoded"]

# -------------------------------
# TRAIN MODEL
# -------------------------------
X_train, X_test, y_train, y_test = train_test_split(
    X, y, test_size=0.25, random_state=42
)

model = RandomForestClassifier(
    n_estimators=200,
    max_depth=8,
    random_state=42
)
model.fit(X_train, y_train)

print("Model accuracy:", round(model.score(X_test, y_test), 2))

# -------------------------------
# MOCK STUDENT INPUT
# -------------------------------
new_student = pd.DataFrame({
    "score_IT": [4],
    "score_CS": [3],
    "score_CE": [1],
    "score_MMA": [2],
    "accuracy_score": [0.75],
    "interest_score": [0.70],
    "performance_encoded": [2]  # High
})

# -------------------------------
# PREDICTION + PROBABILITY
# -------------------------------
probabilities = model.predict_proba(new_student)[0]
class_labels = model.classes_
track_labels = le_track.inverse_transform(class_labels)

track_percentages = {
    track: round(prob * 100, 2)
    for track, prob in zip(track_labels, probabilities)
}

best_index = np.argmax(probabilities)
predicted_track = track_labels[best_index]

# -------------------------------
# OUTPUT
# -------------------------------
result = {
    "predicted_track": predicted_track,
    "track_percentages": track_percentages
}

print("\nPrediction Result:")
print(json.dumps(result, indent=4))
