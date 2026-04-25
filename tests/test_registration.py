import os
import pytest
from unittest.mock import MagicMock, patch
from src.registration import Registration

class TestRegistrationUnit:
    """Unit-тесты без mock (используется реальная SQLite в памяти)."""

    def test_add_registration(self):
        """Тест №1: добавление регистрации и получение данных."""
        reg = Registration()  # база в памяти
        result = reg.register(
            name="Анна",
            birth_date="2000-05-15",
            topic="Python мастер-класс",
            materials_included=True,
            format="online"
        )
        assert result == "Registration for Анна added"

        records = reg.get_all()
        assert len(records) == 1
        assert records[0][1] == "Анна"           # поле name
        assert records[0][4] == 1                 # materials_included (1=True)
        reg.close()

    def test_get_all_empty(self):
        """Тест №2: получение всех записей из пустой таблицы."""
        reg = Registration()
        records = reg.get_all()
        assert records == []
        reg.close()


class TestRegistrationWithMock:
    """Тест №3: использование mock-объекта для соединения с БД."""

    def test_register_uses_db_correctly(self):
        # Создаём mock-соединение
        mock_conn = MagicMock()
        mock_cursor = MagicMock()
        mock_conn.cursor.return_value = mock_cursor

        # Подменяем sqlite3.connect, чтобы он возвращал наш mock
        with patch('src.registration.sqlite3.connect', return_value=mock_conn):
            reg = Registration(db_path="ignored")  # connect вызовет mock
            # Вызываем метод
            result = reg.register(
                name="Пётр",
                birth_date="1995-10-10",
                topic="Веб-разработка",
                materials_included=False,
                format="offline"
            )

        # Проверяем, что connect был вызван
        mock_conn.cursor.assert_called_once()
        # Проверяем, что было выполнено INSERT
        mock_cursor.execute.assert_called()
        # Можно проверить SQL-запрос и параметры
        args, kwargs = mock_cursor.execute.call_args
        assert "INSERT INTO registrations" in args[0]
        assert args[1] == ("Пётр", "1995-10-10", "Веб-разработка", 0, "offline")
        # Возвращаемое значение
        assert result == "Registration for Пётр added"