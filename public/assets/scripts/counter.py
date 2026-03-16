import pandas as pd
from pathlib import Path

SCRIPT_DIR = Path(__file__).parent
data_path = SCRIPT_DIR / "files" / "ai_training_dataset_10000_rows.csv"

df = pd.read_csv(data_path)

print(df.columns.tolist())
print(len(df.columns))