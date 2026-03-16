import pandas as pd
import numpy as np

np.random.seed(42)

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


rows = 10000

tracks = [
    "Information Technology",
    "Computer Science",
    "Computer Engineering",
    "Multimedia Arts"
]

data = []

for i in range(rows):

    # -------- competency accuracy --------
    accuracy = np.random.uniform(0.3,1.0,10)

    # -------- speed scores --------
    speed = np.random.uniform(0.5,2.0,10)

    # -------- confidence --------
    confidence = accuracy * speed

    # -------- category accuracy --------
    cat_acc = np.random.uniform(0.4,1.0,4)

    # -------- time ratios --------
    time = np.random.dirichlet(np.ones(4))

    row = list(accuracy) + list(speed) + list(confidence) + list(cat_acc) + list(time)

    # ----- simple rule to assign track -----
    cs_score = accuracy[0] + accuracy[2] + accuracy[9]
    ce_score = accuracy[3] + accuracy[4] + accuracy[5]
    mma_score = accuracy[6] + accuracy[7] + accuracy[8]
    it_score = accuracy[1] + accuracy[4] + accuracy[9]

    scores = {
        "Computer Science": cs_score,
        "Computer Engineering": ce_score,
        "Multimedia Arts": mma_score,
        "Information Technology": it_score
    }

    track = max(scores, key=scores.get)

    row.append(track)

    data.append(row)

columns = FEATURE_COLUMNS + ["Track"]

df = pd.DataFrame(data, columns=columns)

df.to_csv("ai_training_dataset_10000_rows.csv", index=False)

print("Dataset generated:", df.shape)
