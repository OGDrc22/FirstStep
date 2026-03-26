import pandas as pd
import numpy as np
from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import LabelEncoder
import json, sys
from pathlib import Path
import joblib



# DEBUG_FILE = "/var/www/storage/logs/python_debug.log"


# ------------------------------
# 1. LOAD DATA & TRAIN MODEL
# ------------------------------

SCRIPT_DIR = Path(__file__).resolve().parent
data_path = SCRIPT_DIR / "files" / "ai_training_dataset_10000_rows.csv"

print(f"FR algo: Good", flush=True)

#TRAIN
# df = pd.read_csv(data_path)


#Actual
model_path = SCRIPT_DIR / "model.pkl"
model = joblib.load(model_path)

FEATURE_COLUMNS = [
    "logic","syntax","algorithm","hardware","networking",
    "system_org","creativity","ui_design","attention_detail","problem_solving",

    "logic_speed","syntax_speed","algorithm_speed","hardware_speed","networking_speed",
    "system_org_speed","creativity_speed","ui_design_speed","attention_detail_speed","problem_solving_speed",

    "logic_confidence","syntax_confidence","algorithm_confidence","hardware_confidence","networking_confidence",
    "system_org_confidence","creativity_confidence","ui_design_confidence","attention_detail_confidence","problem_solving_confidence",

    "IT_interest","CS_interest","CE_interest","MMA_interest",

    "IT_accuracy","CS_accuracy","CE_accuracy","MMA_accuracy",

    "IT_time_ratio","CS_time_ratio","CE_time_ratio","MMA_time_ratio"
]


# TRAIN
# X = df[FEATURE_COLUMNS]
# y = df["Track"]

# X_train, X_test, y_train, y_test = train_test_split(
#     X, y, test_size=0.2, random_state=42
# )

# model = RandomForestClassifier(
#     n_estimators=500,
#     max_depth=10,
#     min_samples_leaf=13,
#     class_weight="balanced",
#     random_state=42
# )

# model.fit(X_train, y_train)

# joblib.dump(model, SCRIPT_DIR / "model.pkl")

# print("✅ Model trained and saved!")




# accuracy = model.score(X_test, y_test)




#Actual
try:
    payload = json.load(sys.stdin)
except Exception as e:
    print(f"PYTHON CRASH main algo: {str(e)}", flush=True)

features = payload["features"]

# with open(DEBUG_FILE, "a", encoding="utf-8") as f:
        # f.write(f"🔄 features: {len(features)}\n")
        # f.write(f"🔄 FEATURE_COLLUMNS: {len(FEATURE_COLUMNS)}\n")




try:
    new_student = pd.DataFrame(
        [features],
        columns=FEATURE_COLUMNS
    )
    
    print(f"PYTHON RF algo: good", flush=True)
    # with open(DEBUG_FILE, "a", encoding="utf-8") as f:
        #  f.write(f"new_student count: {len(new_student)}\n")
except Exception as e:   
    # with open(DEBUG_FILE, "a", encoding="utf-8") as f:
            # f.write(f"🎯 New_Student: {e}\n")
    print(f"PYTHON CRASH main algo: {str(e)}", flush=True) # THIS WILL SHOW IN RENDER LOGS
    # print(traceback.format_exc(), flush=True)


try:

    probabilities = model.predict_proba(new_student)[0].tolist()

    track_labels = model.classes_.tolist()

    track_percentage = {}
    for track, prob in zip(track_labels, probabilities):
        track_percentage[track] = round(prob * 100, 2)
        
        print(f"PYTHON RF algo: good", flush=True)
except Exception as e:
    # with open(DEBUG_FILE, "a", encoding="utf-8") as f:
            # f.write(f"🎯 sorted_tracks: {e}\n")
    print(f"PYTHON CRASH RF algo: {str(e)}", flush=True)


sorted_tracks = sorted(track_percentage.items(), key=lambda x: x[1], reverse=True)

# with open(DEBUG_FILE, "a", encoding="utf-8") as f:
        # f.write(f"🎯 sorted_tracks: {sorted_tracks}\n")

primary_recommendation = sorted_tracks[0]
secondary_recommendation = sorted_tracks[1]


# ------------------------------
# 5. OUTPUT
# ------------------------------

output = {
    "track_percentage": track_percentage,
    "probabilities": probabilities,
    "predicted_track": primary_recommendation,
    "secondary_track": secondary_recommendation,
    # "model_accuracy": round(accuracy, 2)
    "model_accuracy": None
}

print(json.dumps(output))