import os
import pytest
from dotenv import load_dotenv

TEST_DIR = os.path.dirname(os.path.abspath(__file__))
load_dotenv(os.path.join(TEST_DIR, '.env.test'))

@pytest.fixture
def db_name():
    return os.getenv('DB_NAME', 'test.db')

@pytest.fixture
def api_base_url():
    return os.getenv('API_BASE_URL', 'http://localhost')