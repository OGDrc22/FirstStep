# import pandas as pd
# from sklearn.model_selection import train_test_split
# from sklearn.ensemble import RandomForestClassifier
# from sklearn.metrics import accuracy_score
import subprocess
import json
import os
import sys

try:

    base_path = os.path.dirname(os.path.dirname(os.path.dirname(__file__)))
    gemeni_path = os.path.join(base_path, 'assets', 'scripts', 'gemeni.py')
    
    # Debug prints
    debug_info = {
        "base_path": base_path,
        "gemeni_path": gemeni_path,
        "file_exists": os.path.exists(gemeni_path)
    }
    print(json.dumps({"debug": debug_info}), file=sys.stderr)

    # if not os.path.exists(gemeni_path):
    #     raise FileNotFoundError(f"Cannot find gemeni.py at: {gemeni_path}")
    
    
    # Run the gemeni.py script and capture output
    result = subprocess.run(
        ["python", gemeni_path],
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE,
        text=True
    )
    
    # Parse the output
    print(result.stdout.strip())  # For debugging purposes
except subprocess.CalledProcessError as e:
    response = {
        "status": "error",
        "data": None,
        "error": str(e)
    }
    print(json.dumps(response))
except Exception as e:
    response = {
        "status": "error",
        "data": None,
        "error": str(e)
    }
    print(json.dumps(response))