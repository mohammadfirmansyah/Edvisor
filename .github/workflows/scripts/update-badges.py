#!/usr/bin/env python3

import os
import re
import sys
from datetime import datetime

def get_env_variable(var_name):
    value = os.getenv(var_name)
    if value is None or value.strip() == "":
        print(f"Error: Environment variable {var_name} is not set.")
        sys.exit(1)
    return value

def parse_tag(tag):
    """
    Parses the tag to extract version and status.
    Expected formats:
    - DIGIT1.DIGIT2.DIGIT3 (e.g., 1.2.3)
    - DIGIT1.DIGIT2.DIGIT3-TEXT.ITERATION (e.g., 1.2.3-HOTFIX.4)
    """
    stable_pattern = r'^[vV]?(\d+\.\d+\.\d+)$'
    extended_pattern = r'^[vV]?(\d+\.\d+\.\d+)-([A-Za-z]+)\.\d+$'

    stable_match = re.match(stable_pattern, tag)
    if stable_match:
        version = stable_match.group(1)
        status = "STABLE"
        color = "success"
        return version, status, color

    extended_match = re.match(extended_pattern, tag)
    if extended_match:
        version = extended_match.group(1)
        status = extended_match.group(2).upper()
        if status == "ALPHA":
            color = "critical"   # Merah
        elif status in ["BETA", "RC", "HOTFIX"]:
            color = "important"  # Oranye
        else:
            color = "success"    # Hijau
        return f"{version}-{status}", status, color

    # Default to STABLE if no pattern matches
    print("Warning: Tag does not match expected patterns. Defaulting to STABLE.")
    return tag, "STABLE", "success"

def format_date(published_at):
    """
    Formats the date to Indonesian format: DD MMMM YYYY
    """
    try:
        # Parse the ISO 8601 date
        dt = datetime.strptime(published_at, "%Y-%m-%dT%H:%M:%SZ")
        # Define month names in Indonesian
        bulan = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ]
        day = dt.day
        month = bulan[dt.month - 1]
        year = dt.year
        return f"{day:02d} {month} {year}"
    except ValueError as e:
        print(f"Error: Invalid date format for PUBLISHED_AT: {published_at}")
        sys.exit(1)

def update_readme(latest_release, latest_status, latest_update, color):
    """
    Replaces placeholders in README.md with the corresponding badge URLs.
    """
    readme_path = "README.md"
    if not os.path.isfile(readme_path):
        print("Error: README.md not found.")
        sys.exit(1)

    with open(readme_path, "r", encoding="utf-8") as file:
        content = file.read()

    # Define badge URLs
    latest_release_badge = f"https://img.shields.io/badge/Latest%20Release-{latest_release}-informational?style=for-the-badge"
    latest_status_badge = f"https://img.shields.io/badge/LATEST%20STATUS-{latest_status}-{color}?style=for-the-badge"
    latest_update_badge = f"https://img.shields.io/badge/Latest%20Update-{latest_update}-lightgrey?style=for-the-badge"

    # Replace placeholders
    content = content.replace("___LATEST_RELEASE_BADGE___", latest_release_badge)
    content = content.replace("___LATEST_STATUS_BADGE___", latest_status_badge)
    content = content.replace("___LATEST_UPDATE_BADGE___", latest_update_badge)

    with open(readme_path, "w", encoding="utf-8") as file:
        file.write(content)

def main():
    # Ambil variabel environment dari GitHub Actions
    tag = get_env_variable("TAG_NAME")
    published_at = get_env_variable("PUBLISHED_AT")

    # Parse tag untuk mendapatkan status dan warna
    version, status, color = parse_tag(tag)

    # Format tanggal
    formatted_date = format_date(published_at)

    # Update README.md
    update_readme(version, status, formatted_date, color)

    # Set outputs untuk digunakan dalam workflow
    with open(os.environ['GITHUB_OUTPUT'], 'a') as f:
        f.write(f"STATUS={status}\n")
        f.write(f"COLOR={color}\n")
        f.write(f"FORMATTED_DATE={formatted_date}\n")
        f.write(f"LATEST_RELEASE={version}\n")

    print("README.md berhasil diperbarui.")

if __name__ == "__main__":
    main()