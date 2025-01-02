#!/usr/bin/env python3
# update_badges.py

import os
import json
import subprocess
from datetime import datetime
import locale
import re

def main():
    """
    1. Baca data rilis dari GITHUB_EVENT_PATH
    2. Format tanggal ke bahasa Indonesia
    3. Tentukan status rilis dan warna badge
    4. Baca README.md dan ganti placeholder
    5. Commit dan push perubahan
    """
    # 1. Baca data rilis dari GITHUB_EVENT_PATH
    event_path = os.environ.get("GITHUB_EVENT_PATH", "")
    if not event_path or not os.path.isfile(event_path):
        print("GITHUB_EVENT_PATH tidak ditemukan. Script dibatalkan.")
        return

    with open(event_path, "r", encoding="utf-8") as f:
        event_data = json.load(f)

    release_info = event_data.get("release", {})
    latest_release = release_info.get("tag_name", "")
    published_at = release_info.get("published_at", "")

    if not latest_release or not published_at:
        print("Informasi rilis tidak lengkap. Script dibatalkan.")
        return

    # 2. Format tanggal ke bahasa Indonesia
    try:
        locale.setlocale(locale.LC_TIME, 'id_ID.UTF-8')
    except locale.Error:
        # Jika locale id_ID.UTF-8 belum di-gen, lakukan gen terlebih dahulu
        subprocess.run(["sudo", "locale-gen", "id_ID.UTF-8"], check=True)
        locale.setlocale(locale.LC_TIME, 'id_ID.UTF-8')

    try:
        dt = datetime.strptime(published_at, "%Y-%m-%dT%H:%M:%SZ")
        release_date = dt.strftime("%d %B %Y")
    except ValueError:
        print("Format tanggal tidak dikenali. Script dibatalkan.")
        return

    # 3. Tentukan status rilis dan warna badge
    status = "STABLE"
    color = "success"  # Hijau default

    # Regex untuk mengekstrak label dari tag rilis
    regex = r"^[0-9]+\.[0-9]+\.[0-9]+(?:-([^.]+)\.[0-9]+)?$"
    match = re.match(regex, latest_release)
    if match:
        label = match.group(1)
        if label:
            status = label.upper()
            if status == "ALPHA":
                color = "critical"  # Merah
            elif status == "BETA":
                color = "important"  # Oranye
    else:
        status = "STABLE"
        color = "success"

    # 4. Baca README.md dan ganti placeholder
    readme_path = "README.md"
    if not os.path.isfile(readme_path):
        print("README.md tidak ditemukan di root repository. Script dibatalkan.")
        return

    with open(readme_path, "r", encoding="utf-8") as f:
        content = f.read()

    # Ganti placeholder LATEST_STATUS_BADGE
    latest_status_badge_url = f"https://img.shields.io/badge/LATEST%20STATUS-{status}-{color}?style=for-the-badge"
    content = content.replace("{{LATEST_STATUS_BADGE}}", latest_status_badge_url)

    # Ganti placeholder LATEST_UPDATE_BADGE
    latest_update_badge_url = f"https://img.shields.io/badge/Latest%20Update-{release_date}-blue?style=for-the-badge&color=lightgrey"
    content = content.replace("{{LATEST_UPDATE_BADGE}}", latest_update_badge_url)

    # Tulis kembali README.md
    with open(readme_path, "w", encoding="utf-8") as f:
        f.write(content)

    print("Placeholder berhasil diganti.")

    # 5. Commit dan push perubahan
    try:
        subprocess.run(["git", "config", "--local", "user.name", "github-actions[bot]"], check=True)
        subprocess.run(["git", "config", "--local", "user.email", "github-actions[bot]@users.noreply.github.com"], check=True)
        subprocess.run(["git", "add", "README.md"], check=True)
        commit_message = f"Update badges: Release={latest_release}, Date={release_date}, Status={status}"
        # Cek apakah ada perubahan untuk di-commit
        result = subprocess.run(["git", "diff", "--cached", "--exit-code"], check=False)
        if result.returncode != 0:
            subprocess.run(["git", "commit", "-m", commit_message], check=True)
            subprocess.run(["git", "push"], check=True)
            print("Perubahan berhasil di-commit dan di-push.")
        else:
            print("Tidak ada perubahan untuk di-commit.")
    except subprocess.CalledProcessError as e:
        print(f"Terjadi kesalahan saat melakukan commit dan push: {e}")
        return

if __name__ == "__main__":
    main()