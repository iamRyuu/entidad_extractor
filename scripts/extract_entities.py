import sys
import json
from google.cloud import language_v1
import requests
from bs4 import BeautifulSoup
import os

def fetch_url_content(url):
    try:
        response = requests.get(url)
        response.raise_for_status()
        return response.text
    except requests.exceptions.RequestException as e:
        print(f"Error fetching URL content: {e}")
        sys.exit(1)

def analyze_entities(text):
    try:
        # Verificar si las credenciales están configuradas correctamente
        credentials_path = os.getenv('GOOGLE_APPLICATION_CREDENTIALS')
        if not credentials_path or not os.path.exists(credentials_path):
            print("Error: Las credenciales no están configuradas correctamente. Asegúrate de que la variable de entorno GOOGLE_APPLICATION_CREDENTIALS apunte al archivo JSON correcto.")
            sys.exit(1)

        client = language_v1.LanguageServiceClient()

        document = language_v1.Document(content=text, type_=language_v1.Document.Type.PLAIN_TEXT)
        response = client.analyze_entities(document=document)

        entities = []
        for entity in response.entities:
            entities.append({
                'name': entity.name,
                'type': language_v1.Entity.Type(entity.type_).name
            })

        return entities
    except Exception as e:
        print(f"Error analyzing entities: {e}")
        sys.exit(1)

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python extract_entities.py <URL>")
        sys.exit(1)

    url = sys.argv[1]
    content = fetch_url_content(url)

    # Parse HTML to get text content
    soup = BeautifulSoup(content, 'html.parser')
    text = soup.get_text()

    entities = analyze_entities(text)
    print(json.dumps(entities, indent=2))

print(json.dumps({"status": "Script executed successfully"}))
sys.exit(0)
