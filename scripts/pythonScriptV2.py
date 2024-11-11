import requests
import pandas as pd
from sqlalchemy import create_engine

# Function to pull and parse the ClinVar file line by line and insert into SQL
def pull_and_insert_line_by_line(ftp_url, table_name, engine):
    try:
        # Stream the file from the URL
        with requests.get(ftp_url, stream=True) as response:
            response.raise_for_status()  # Raise an error for bad responses
            # Read the file in chunks - line by line
            for chunk in response.iter_lines(decode_unicode=True):
                if chunk:  # Check if the line is not empty
                    # Split the line into fields based on tab delimiter '\t'
                    row_data = chunk.split('\t')
                    # Convert the chunk to a dataframe to read into SQL
                    df = pd.DataFrame([row_data])
                    # Insert into SQL
                    df.to_sql(table_name, engine, if_exists='append', index=False)
            print(f"All lines from {ftp_url} have been inserted into the table '{table_name}'.")
    except Exception as e:
        print(f"Error processing file from {ftp_url}: {e}")

# Main function
def main():
    ftp_urls = [
        "https://ftp.ncbi.nlm.nih.gov/pub/clinvar/tab_delimited/variant_summary.txt.gz",
        "https://ftp.ncbi.nlm.nih.gov/pub/clinvar/tab_delimited/submission_summary.txt.gz",
        "https://ftp.ncbi.nlm.nih.gov/pub/clinvar/tab_delimited/summary_of_conflicting_interpretations.txt",
        "https://ftp.ncbi.nlm.nih.gov/pub/clinvar/tab_delimited/hgvs4variation.txt.gz"
    ]
    table_names = [
        "vartiant_summary",
        "submission_summary",
        "summary_of_conflicting_interpretations",
        "hgvs4variation"
    ]
    
    # Create an SQLAlchemy engine to connect to the backend db (mySQL) (we can update this later/change if needed)
    # - Database: backend_db is built on local host atm on port 3306
    # - username: BlueTeam
    # - passwd: blue2024
    engine = create_engine('mysql+pymysql://BlueTeam:blue2024@localhost:3306/backend_db')

      # Download, parse, and send the files to the SQL database
    for ftp_url, table_name in zip(ftp_urls, table_names):
        pull_and_insert_line_by_line(ftp_url, table_name, engine)

if __name__ == "__main__":
    main()
