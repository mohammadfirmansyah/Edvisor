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
    print(f"{var_name}: {value}")
    return value

def parse_tag(tag):
    """
    Parses the tag to extract version and status.
    Expected formats:
    - DIGIT1.DIGIT2.DIGIT3 (e.g., 1.2.3)
    - DIGIT1.DIGIT2.DIGIT3-TEXT.ITERATION (e.g., 1.2.3-HOTFIX.4)
    """
    print(f"Parsing tag: {tag}")
    stable_pattern = r'^[vV]?(\d+\.\d+\.\d+)$'
    extended_pattern = r'^[vV]?(\d+\.\d+\.\d+)-([A-Za-z]+)\.\d+$'

    stable_match = re.match(stable_pattern, tag)
    if stable_match:
        version = stable_match.group(1)
        status = "STABLE"
        color = "success"
        print(f"Matched STABLE tag format: {version}, {status}, {color}")
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
        print(f"Matched extended tag format: {version}, {status}, {color}")
        return f"{version}-{status}", status, color

    # Default to STABLE if no pattern matches
    print("Warning: Tag does not match expected patterns. Defaulting to STABLE.")
    return tag, "STABLE", "success"

def format_date(published_at):
    """
    Formats the date to Indonesian format: DD MMMM YYYY
    """
    print(f"Formatting date: {published_at}")
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
        formatted_date = f"{day:02d} {month} {year}"
        print(f"Formatted date: {formatted_date}")
        return formatted_date
    except ValueError as e:
        print(f"Error: Invalid date format for PUBLISHED_AT: {published_at}")
        sys.exit(1)

def main():
    # Ambil variabel environment dari GitHub Actions
    tag = get_env_variable("TAG_NAME")
    published_at = get_env_variable("PUBLISHED_AT")

    # Parse tag untuk mendapatkan status dan warna
    version, status, color = parse_tag(tag)

    # Format tanggal
    formatted_date = format_date(published_at)

    # Set outputs untuk digunakan dalam workflow
    with open(os.environ['GITHUB_OUTPUT'], 'a') as f:
        f.write(f"STATUS={status}\n")
        f.write(f"COLOR={color}\n")
        f.write(f"FORMATTED_DATE={formatted_date}\n")
        f.write(f"LATEST_RELEASE={version}\n")

    print("Script berhasil dijalankan dan outputs diatur.")

if __name__ == "__main__":
    main()