import pymysql, sys

try:
    db = pymysql.connect(
        host="127.0.0.1",
        user="root",
        password="",
        database="firststep",
        cursorclass=pymysql.cursors.DictCursor  # Use DictCursor to access columns by name
    )
except Exception as e:
    print(f"Error connecting to database: {e}")
    sys.exit(1)

cursor = db.cursor()
cursor.execute("SELECT payload FROM exam_jobs WHERE id = %s", (35,))
row = cursor.fetchone()

if not row or not row['payload']:
    raise Exception("Payload not found")

payload_ = row['payload']
print(payload_)