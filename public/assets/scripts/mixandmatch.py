# %%
from pathlib import Path
from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score, confusion_matrix, ConfusionMatrixDisplay
import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
from sklearn.preprocessing import LabelEncoder


# %%
script_path = Path(__file__)
base_path = script_path.parent
path_to_data = base_path / "files"
csv_file = path_to_data / "processed_interest_data.csv"
df_interest = pd.read_csv(csv_file)
df_interest

# %%
csv_file_perf = path_to_data / "processed_clusters.csv"
df_perf = pd.read_csv(csv_file_perf)
# df_perf

# %%
df_interest['performance_group'] = np.random.choice(['High', 'Average', 'Low'], len(df_interest))

print(df_interest.head())

# %%
le_perf = LabelEncoder()
le_track = LabelEncoder()

df_interest['performance_encoded'] = le_perf.fit_transform(df_interest['performance_group'])
df_interest['track_encoded'] = le_track.fit_transform(df_interest['Track'])
df_interest['cluster'].unique()

# %%
X = df_interest[['Interest_in_IT', 'performance_encoded']]
y = df_interest['track_encoded']

X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

model = RandomForestClassifier(n_estimators=100, random_state=42, class_weight='balanced')
model.fit(X_train, y_train)

accuracy = model.score(X_test, y_test)
print(f"Model Accuracy: {accuracy:.2f}")

# %%
new_student = pd.DataFrame({
    'Interest_in_IT': [0.8],
    'performance_encoded': [le_perf.transform(['Low'])[0]]
})

predicted_track = le_track.inverse_transform(model.predict(new_student))
print("Predicted Track:", predicted_track[0])


# %%
print(df_interest['Track'].value_counts())
print(df_interest['Track'].unique())


# %%
y_pred = model.predict(X_test)
accuracy = accuracy_score(y_test, y_pred)
print(f"Accuracy: {accuracy:.2f}")

# %%
cm = confusion_matrix(y_test, y_pred)


# %%
disp = ConfusionMatrixDisplay(confusion_matrix=cm, display_labels=model.classes_)
disp.plot(cmap=plt.cm.Blues)
plt.title('Confusion Matrix for Random Forest Classifier')
plt.show()
