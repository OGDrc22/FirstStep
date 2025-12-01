# %%
from pathlib import Path
import pandas as pd
from sklearn.preprocessing import StandardScaler
from sklearn.model_selection import train_test_split, cross_val_score
from sklearn.ensemble import RandomForestClassifier
from sklearn.cluster import KMeans
from sklearn.decomposition import PCA
from sklearn.metrics import classification_report, confusion_matrix, silhouette_score

script_path = Path(__file__)
base_path = script_path.parent
path_to_data = base_path / "files"
csv_file = path_to_data / "ResearchInformation3.csv"

df = pd.read_csv(csv_file)

# 1) Simple mapping -> Track (supervised setup)
dept_to_track = {
    "Business Administration": "ABM",
    "Computer Science and Engineering": "STEM",
    "Economics": "ABM",
    "Electrical and Electronic Engineering": "STEM",
    "English": "HUMSS",
    "Journalism, Communication and Media Studies": "HUMSS",
    "Law and Human Rights": "HUMSS",
    "Political Science": "HUMSS",
    "Public Health": "STEM",
    "Sociology": "HUMSS"
}
df['Track'] = df['Department'].map(dept_to_track)

print(df['Track'])



# %%
# 2) Feature engineering
for c in ['HSC','SSC','Overall','Last','English','Computer']:
    df[c] = pd.to_numeric(df[c], errors='coerce')

# Flags
df['Extra_flag'] = df['Extra'].astype(str).str.lower().map({'yes':1, 'no':0}).fillna(0)
# Attendance flag example:
df['Attendance_flag'] = df['Attendance'].str.contains('80%-100%', na=False).astype(int)

# fill numeric missing values with median
num_cols = ['HSC','SSC','Overall','Last','English','Computer']
df[num_cols] = df[num_cols].fillna(df[num_cols].median())

# scaled features
scaler = StandardScaler()
X = scaler.fit_transform(df[num_cols + ['Extra_flag','Attendance_flag']])

# 3a) Unsupervised: clustering
pca = PCA(n_components=5)
X_pca = pca.fit_transform(X)
k = 4  # try different k and evaluate silhouette
kmeans = KMeans(n_clusters=k, random_state=42)
labels = kmeans.fit_predict(X_pca)
df['cluster'] = labels
print("Silhouette:", silhouette_score(X_pca, labels))

# Inspect cluster composition by Department:
print(df.groupby('cluster')['Department'].value_counts().head(20))

# 3b) Supervised: predict Track if Track exists
train_df = df.dropna(subset=['Track']).copy()
y = train_df['Track']
X_super = scaler.fit_transform(train_df[num_cols + ['Extra_flag','Attendance_flag']])
X_train, X_test, y_train, y_test = train_test_split(X_super, y, test_size=0.2, random_state=42, stratify=y)
clf = RandomForestClassifier(n_estimators=200, random_state=42)
clf.fit(X_train, y_train)
y_pred = clf.predict(X_test)
print(classification_report(y_test, y_pred))
print(confusion_matrix(y_test, y_pred))

# %%
stem_df = df[df['Track'] == 'STEM']
print(stem_df['Track'])
print("STEM count:", stem_df.shape[0])

# %%
print(df.head())

# %%

it_list = ["Computer Science", "Information Technology", "Computer Science and Engineering"]
df['Interest_in_IT'] = df['Department'].fillna('').str.strip().isin(it_list)
ex_cls = ['Attendance', 'Income', 'Hometown', 'Computer', 'English', 'Overall', 'Last', 'SSC', 'HSC', 'Extra', 'Gaming', 'Preparation', 'Gender', 'Job', 'Semester']
print(df.drop(columns=ex_cls))
print(df['Interest_in_IT'].count())

# %%
print(df['Department'].unique())

# %%
print(df['Track'].unique())

# %%
output_path = base_path / 'files'
to_csv = output_path / 'processed_interest_data.csv'
df.to_csv(to_csv, index=False)
print("Processed data saved to ", to_csv)



