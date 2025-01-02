#!/usr/bin/env python3
# .github/scripts/update_badges.py

import os
import json
import subprocess
from datetime import datetime
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
    release_date = format_date_indonesian(published_at)
    if not release_date:
        print("Format tanggal tidak dikenali. Script dibatalkan.")
        return

    # 3. Tentukan status rilis dan warna badge
    status, color = determine_status_and_color(latest_release)

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
    commit_and_push_changes(latest_release, release_date, status)

def format_date_indonesian(published_at):
    """
    Mengonversi tanggal dari format ISO 8601 ke "DD MMMM YYYY" dalam bahasa Indonesia.
    Contoh: "2025-01-02T07:53:21Z" -> "02 Januari 2025"
    """
    # Mapping bulan ke nama bahasa Indonesia
    bulan = {
        1: "Januari",
        2: "Februari",
        3: "Maret",
        4: "April",
        5: "Mei",
        6: "Juni",
        7: "Juli",
        8: "Agustus",
        9: "September",
        10: "Oktober",
        11: "November",
        12: "Desember"
    }

    try:
        dt = datetime.strptime(published_at, "%Y-%m-%dT%H:%M:%SZ")
        hari = dt.day
        bulan_nama = bulan[dt.month]
        tahun = dt.year
        return f"{hari:02d} {bulan_nama} {tahun}"
    except Exception as e:
        print(f"Error saat memformat tanggal: {e}")
        return ""

def determine_status_and_color(latest_release):
    """
    Menentukan status rilis dan warna badge berdasarkan tag rilis.
    """
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

    return status, color

def commit_and_push_changes(latest_release, release_date, status):
    """
    Melakukan commit dan push perubahan README.md ke repository.
    """
    try:
        # Konfigurasi Git
        subprocess.run(["git", "config", "--local", "user.name", "github-actions[bot]"], check=True)
        subprocess.run(["git", "config", "--local", "user.email", "github-actions[bot]@users.noreply.github.com"], check=True)
        
        # Tambahkan README.md
        subprocess.run(["git", "add", "README.md"], check=True)
        
        # Buat pesan commit
        commit_message = f"Update badges: Release={latest_release}, Date={release_date}, Status={status}"
        
        # Cek apakah ada perubahan untuk di-commit
        result = subprocess.run(["git", "diff", "--cached", "--exit-code"], stdout=subprocess.PIPE)
        if result.returncode != 0:
            # Commit dan push
            subprocess.run(["git", "commit", "-m", commit_message], check=True)
            subprocess.run(["git", "push"], check=True)
            print("Perubahan berhasil di-commit dan di-push.")
        else:
            print("Tidak ada perubahan untuk di-commit.")
    except subprocess.CalledProcessError as e:
        print(f"Terjadi kesalahan saat melakukan commit dan push: {e}")

if __name__ == "__main__":
    main()