#!/usr/bin/env python3
# update_badges.py
import os
import json
import subprocess
from datetime import datetime
import locale

def main():
    """
    1. Baca info rilis dari GITHUB_EVENT_PATH
    2. Tentukan status (ALPHA, BETA, dsb.) dan warna badge
    3. Ganti placeholder di README.md
    4. Commit & Push
    """
    event_path = os.environ.get("GITHUB_EVENT_PATH", "")
    if not event_path or not os.path.isfile(event_path):
        print("GITHUB_EVENT_PATH tidak ditemukan. Script dibatalkan.")
        return

    # 1. Baca JSON event (release)
    with open(event_path, "r", encoding="utf-8") as f:
        event_data = json.load(f)

    # Pastikan struktur JSON betul
    release_info = event_data.get("release", {})
    latest_release = release_info.get("tag_name", "")
    published_at = release_info.get("published_at", "")

    # 2. Format Tanggal Rilis ke bahasa Indonesia
    # Contoh input: "2025-01-02T07:53:21Z"
    # Output: "02 Januari 2025"
    if published_at:
        # set locale
        locale.setlocale(locale.LC_ALL, "id_ID.UTF-8")
        # parse ISO 8601
        dt = datetime.strptime(published_at, "%Y-%m-%dT%H:%M:%SZ")
        release_date = dt.strftime("%d %B %Y")
    else:
        release_date = ""

    # 3. Tentukan status (STABLE, ALPHA, BETA, HOTFIX, RC, dsb.)
    #    Berdasarkan format versi: X.Y.Z(-LABEL.ITERASI)?
    #    Contoh: 1.2.1-hotfix.1 -> HOTFIX, 1.3.0 -> STABLE
    latest_status = "STABLE"
    import re
    regex = r"^[0-9]+\.[0-9]+\.[0-9]+(?:-([^.]+)\.[0-9]+)?$"
    match = re.match(regex, latest_release)
    if match:
        label = match.group(1) or ""
        if label:
            latest_status = label.upper()

    # 4. Mapping warna status
    #    - ALPHA => red (Shields.io => critical)
    #    - BETA => orange (Shields.io => important)
    #    - Lainnya => green (success)
    color = "success"
    if latest_status == "ALPHA":
        color = "critical"
    elif latest_status == "BETA":
        color = "important"

    # 5. Baca README.md dan ganti placeholder
    readme_path = "README.md"
    if not os.path.isfile(readme_path):
        print("README.md tidak ditemukan di root repository. Script dibatalkan.")
        return

    with open(readme_path, "r", encoding="utf-8") as f:
        content = f.read()

    # 5a. Ganti placeholder "Latest Release"
    old_release_url = "https://img.shields.io/github/v/release/mohammadfirmansyah/Edvisor?include_prereleases&label=Latest%20Release&style=for-the-badge&color=informational"
    new_release_url = f"https://img.shields.io/github/v/release/mohammadfirmansyah/Edvisor/{latest_release}?include_prereleases&label=Latest%20Release&style=for-the-badge&color=informational"
    content = content.replace(old_release_url, new_release_url)

    # 5b. Ganti placeholder "Latest Status"
    old_status_url = "https://img.shields.io/badge/LATEST%20STATUS--blue?style=for-the-badge"
    new_status_url = f"https://img.shields.io/badge/LATEST%20STATUS-{latest_status}-{color}?style=for-the-badge"
    content = content.replace(old_status_url, new_status_url)

    # 5c. Ganti placeholder "Latest Update"
    old_update_url = "https://img.shields.io/badge/Latest%20Update--blue?style=for-the-badge&color=lightgrey"
    new_update_url = f"https://img.shields.io/badge/Latest%20Update-{release_date}-blue?style=for-the-badge&color=lightgrey"
    content = content.replace(old_update_url, new_update_url)

    # Tulis kembali README.md
    with open(readme_path, "w", encoding="utf-8") as f:
        f.write(content)

    print(f"Placeholder berhasil diganti. Tag={latest_release}, Tanggal={release_date}, Status={latest_status}, Warna={color}")

    # 6. Commit & Push
    # Gunakan subprocess agar GitHub Actions mau melakukan commit & push
    subprocess.run(["git", "config", "--local", "user.name", "github-actions[bot]"], check=False)
    subprocess.run(["git", "config", "--local", "user.email", "github-actions[bot]@users.noreply.github.com"], check=False)
    subprocess.run(["git", "add", "README.md"], check=False)
    commit_msg = f"Update badges: Release={latest_release}, Date={release_date}, Status={latest_status}"
    subprocess.run(["git", "commit", "-m", commit_msg], check=False)
    subprocess.run(["git", "push"], check=False)


if __name__ == "__main__":
    main()