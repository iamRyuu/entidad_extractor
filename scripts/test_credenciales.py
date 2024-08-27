from google.cloud import language_v1

def test_credentials():
    try:
        client = language_v1.LanguageServiceClient()
        print("Credenciales configuradas correctamente.")
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    test_credentials()
