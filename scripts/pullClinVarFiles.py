import requests
import pandas as pd

# Download the tab-delimited file from the ClinVar FTP site
def download_clinvar_file(ftp_url, local_filename):
    response = requests.get(ftp_url)
    
    if response.status_code == 200:
        with open(local_filename, 'wb') as file:
            file.write(response.content)
        print(f"File {local_filename} downloaded successfully.")
    else:
        print(f"Failed to download file from {ftp_url}. Status code: {response.status_code}")

# Parse the tab-delimited file using pandas
def parse_tab_file(local_filename):
    try:
        # Load the tab-delimited file into a pandas DataFrame
        df = pd.read_csv(local_filename, delimiter='\t', low_memory=False)
        print(f"File {local_filename} loaded successfully.")
        return df
    except Exception as e:
        print(f"Error reading the file {local_filename}: {e}")
        return None

# Save DataFrame to CSV
def save_to_csv(df, csv_filename):
    try:
        df.to_csv(csv_filename, index=False)
        print(f"Data successfully saved to {csv_filename}.")
    except Exception as e:
        print(f"Error saving data to CSV file: {e}")

# Main function
def main():
    # Define the ClinVar FTP file URLs and local file names
    ftp_urls = [
        "https://ftp.ncbi.nlm.nih.gov/pub/clinvar/tab_delimited/variant_summary.txt.gz",
        "https://ftp.ncbi.nlm.nih.gov/pub/clinvar/tab_delimited/submission_summary.txt.gz",
        "https://ftp.ncbi.nlm.nih.gov/pub/clinvar/tab_delimited/summary_of_conflicting_interpretations.txt",
        "https://ftp.ncbi.nlm.nih.gov/pub/clinvar/tab_delimited/hgvs4variation.txt.gz"
    ]
    
    local_filenames = [
        "variant_summary.txt.gz",
        "submission_summary.txt.gz",
        "summary_of_conflicting_interpretations.txt",
        "hgvs4variation.txt.gz"
    ]
    
    csv_filenames = [
        "variant_summary.csv",
        "submission_summary.csv",
        "summary_of_conflicting_interpretations.csv",
        "hgvs4variation.csv"
    ]
    
    # Download the files
    for ftp_url, local_filename in zip(ftp_urls, local_filenames):
        download_clinvar_file(ftp_url, local_filename)

    # Parse the downloaded files and save them to CSV
    for local_filename, csv_filename in zip(local_filenames, csv_filenames):
        try:
            df = pd.read_csv(local_filename, compression='gzip', delimiter='\t', low_memory=False)
            save_to_csv(df, csv_filename)
        except Exception as e:
            print(f"Error parsing the file {local_filename}: {e}")

if __name__ == "__main__":
    main()
xs