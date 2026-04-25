import responses
import requests

class TestHTTPMock:
    """Тест №4: HTTP запрос с mock-ответом (без реального сервера)."""

    @responses.activate
    def test_send_registration_success(self, api_base_url):
        # api_base_url приходит из фикстуры в conftest.py
        url = f"{api_base_url}/register"

        responses.add(
            responses.POST,
            url,
            json={"status": "ok", "message": "Registration for Иван added"},
            status=200
        )

        payload = {
            "name": "Иван",
            "birth_date": "1990-01-01",
            "topic": "Data Science",
            "materials_included": True,
            "format": "online"
        }
        response = requests.post(url, json=payload)

        assert response.status_code == 200
        data = response.json()
        assert data["message"] == "Registration for Иван added"
        assert len(responses.calls) == 1
        assert responses.calls[0].request.url == url