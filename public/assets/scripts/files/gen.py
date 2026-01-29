import pandas as pd
import numpy as np
from sklearn.preprocessing import LabelEncoder

# Number of synthetic students
N = 5000

# ---- STEP 1: Generate Interest scores (0 to 1) ----
interest_scores = np.round(np.random.uniform(0, 1, N), 3)


# ---- STEP 2: Derive performance group based on interest score ----
def derive_perf(interest):
    if interest < 0.4:
        return 'Low'
    elif interest < 0.7:
        return 'Average'
    else:
        return 'High'

performance_groups = [derive_perf(x) for x in interest_scores]


# ---- STEP 3: Assign Track Based on Interest Level ----
tracks = []

for interest in interest_scores:
    
    if interest >= 0.7:
        # High interest → Mostly CS, IT, CE
        choices = ["Computer Science", "Information Technology", "Computer Engineering", "Multimedia Arts"]
        probs   = [0.40,                 0.40,                    0.15,                    0.05]
    
    elif interest >= 0.4:
        # Average interest → balanced
        choices = ["Computer Science", "Information Technology", "Computer Engineering", "Multimedia Arts"]
        probs   = [0.25,                 0.25,                    0.25,                    0.25]
    
    else:
        # Low interest → Mostly Multimedia Arts
        choices = ["Computer Science", "Information Technology", "Computer Engineering", "Multimedia Arts"]
        probs   = [0.10,                 0.10,                    0.10,                    0.70]

    tracks.append(np.random.choice(choices, p=probs))


# ---- STEP 4: Encode Labels ----
le_perf = LabelEncoder()
le_track = LabelEncoder()

performance_encoded = le_perf.fit_transform(performance_groups)
track_encoded = le_track.fit_transform(tracks)


# ---- STEP 5: Create DataFrame ----
df = pd.DataFrame({
    "Interest_in_IT": interest_scores,
    "performance_group": performance_groups,
    "performance_encoded": performance_encoded,
    "Track": tracks,
    "track_encoded": track_encoded
})

# ---- STEP 6: Save ----
# df.to_csv("processed_interest_data.csv", index=False)

# print("Dataset generated: processed_interest_data.csv")
print(df.head())
#%%
print(df['Track'].unique())
# %%
