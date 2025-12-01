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
csv_file = path_to_data / "StudentsPerformance.csv"
df_scores = pd.read_csv(csv_file)
df_scores

# %%
X_scores = df_scores[['math score', 'reading score', 'writing score']]

scaler = StandardScaler()
X_scaled = scaler.fit_transform(X_scores)

kmeans = KMeans(n_clusters=3, random_state=42)
df_scores['cluster'] = kmeans.fit_predict(X_scaled)

# %%
cluster_summary = df_scores.groupby('cluster')[['math score', 'reading score', 'writing score']].mean()
print(cluster_summary)


# %%
output_path = base_path / 'files'
to_csv = output_path / 'processed_clusters.csv'
df_scores.to_csv(to_csv, index=False)
print("Processed data saved!")




# %%
