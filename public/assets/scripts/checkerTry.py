import sys, json
try:
    payload = json.load(sys.stdin)
except Exception as e:
    print(json.dumps({"error": str(e)}))
    sys.exit(1)


answers = payload["answers"]
keys = payload["keys"]

score = 0
for i in range(len(answers)):
    if answers[i] == keys[i]:
        score += 1

print(json.dumps({
    "score": score,
    "total": len(keys)
}))
