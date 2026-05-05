# Test script for scenario 4: AI detection
# Uncomment for presentation, remove after

# Injects error patterns into build.log to trigger ai_check.py threshold
with open("build.log", "a") as f:
    for i in range(6):
        f.write(f"ERROR: simulated error {i+1} for AI detection test\n")

print("Injected 6 error lines into build.log")
