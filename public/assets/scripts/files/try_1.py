import pandas as pd
import numpy as np
from sklearn.preprocessing import LabelEncoder

# ----------------------------
# Parameters
# ----------------------------
N = 5000
tracks = ["Computer Science", "Computer Engineering", "Multimedia Arts", "Information Technology"]
questions_per_category = 5

# ----------------------------
# 1. Assign tracks
# ----------------------------
assigned_tracks = np.random.choice(tracks, N)

# ----------------------------
# 2. Generate category scores based on track
# ----------------------------
def generate_scores(track):
    """Higher score for the student's track category, medium for others"""
    scores = {}
    for t in tracks:
        if t == track:
            scores[t] = np.random.randint(3, 6)  # High score for own track
        else:
            scores[t] = np.random.randint(0, 4)  # Medium-low for other tracks
    return scores

score_IT, score_CS, score_CE, score_MMA = [], [], [], []
for t in assigned_tracks:
    s = generate_scores(t)
    score_IT.append(s["Information Technology"])
    score_CS.append(s["Computer Science"])
    score_CE.append(s["Computer Engineering"])
    score_MMA.append(s["Multimedia Arts"])

# ----------------------------
# 3. Compute totals, accuracy, interest
# ----------------------------
total_correct = [score_IT[i] + score_CS[i] + score_CE[i] + score_MMA[i] for i in range(N)]
total_questions = questions_per_category * 4
accuracy_score = [total_correct[i]/total_questions for i in range(N)]

# Simulate average speed (inverse to accuracy for variety)
average_speed = [np.random.uniform(0.3, 1.0) for _ in range(N)]
interest_score = [0.6*accuracy_score[i] + 0.4*(1-average_speed[i]) for i in range(N)]

# ----------------------------
# 4. Performance group
# ----------------------------
def derive_performance(score):
    if score < 0.4:
        return "Low"
    elif score < 0.7:
        return "Average"
    else:
        return "High"

performance_group = [derive_performance(acc) for acc in accuracy_score]

# ----------------------------
# 5. Encode labels
# ----------------------------
le_perf = LabelEncoder()
performance_encoded = le_perf.fit_transform(performance_group)

le_track = LabelEncoder()
track_encoded = le_track.fit_transform(assigned_tracks)

# ----------------------------
# 6. Create DataFrame
# ----------------------------
df = pd.DataFrame({
    "score_IT": score_IT,
    "score_CS": score_CS,
    "score_CE": score_CE,
    "score_MMA": score_MMA,
    "total_correct": total_correct,
    "accuracy_score": accuracy_score,
    "average_speed": average_speed,
    "interest_score": interest_score,
    "performance_group": performance_group,
    "performance_encoded": performance_encoded,
    "Track": assigned_tracks,
    "track_encoded": track_encoded
})

# ----------------------------
# 7. Save CSV
# ----------------------------
df.to_csv("processed_interest_data_correlated.csv", index=False)
print("Correlated dataset generated: processed_interest_data_correlated.csv")
print(df.head())
