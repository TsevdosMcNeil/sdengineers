import os
import zipfile

# Detect plugin folder name automatically
PLUGIN_DIR = os.path.abspath(os.getcwd())
PLUGIN_SLUG = os.path.basename(PLUGIN_DIR)

DIST_DIR = os.path.join(PLUGIN_DIR, "dist")
ZIP_PATH = os.path.join(DIST_DIR, f"{PLUGIN_SLUG}.zip")

# Directories and files to exclude
EXCLUDE_DIRS = {".vscode", ".git", "node_modules", "__pycache__", "dist"}
EXCLUDE_FILES = {".DS_Store", "build-plugin.py", ".hubpdf-gate.js", "README.md", "DEVELOPMENT.md", "OPT-IN-INTEGRATION.md"}

os.makedirs(DIST_DIR, exist_ok=True)

with zipfile.ZipFile(ZIP_PATH, "w", zipfile.ZIP_DEFLATED) as zipf:
    for root, dirs, files in os.walk(PLUGIN_DIR):

        # Skip excluded directories
        dirs[:] = [d for d in dirs if d not in EXCLUDE_DIRS]

        for file in files:
            if file in EXCLUDE_FILES:
                continue

            file_path = os.path.join(root, file)

            # Important: make zip start at plugin folder level
            arcname = os.path.relpath(file_path, os.path.dirname(PLUGIN_DIR))

            zipf.write(file_path, arcname)

print(f"\n✅ Production ZIP created at:\n{ZIP_PATH}")