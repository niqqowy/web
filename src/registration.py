import sqlite3
from typing import Optional, List, Tuple

class Registration:
    def __init__(self, db_path: str = ":memory:"):
        """
        :param db_path: путь к файлу БД SQLite.
                        По умолчанию в памяти (для тестов).
        """
        self.connection = sqlite3.connect(db_path)
        self._create_table()

    def _create_table(self):
        query = """
        CREATE TABLE IF NOT EXISTS registrations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            birth_date TEXT NOT NULL,
            topic TEXT NOT NULL,
            materials_included INTEGER NOT NULL DEFAULT 0,
            format TEXT NOT NULL
        )
        """
        self.connection.execute(query)
        self.connection.commit()

    def register(self, name: str, birth_date: str, topic: str,
                 materials_included: bool, format: str) -> str:
        """
        Добавляет новую регистрацию.
        Возвращает строку подтверждения.
        """
        cursor = self.connection.cursor()
        cursor.execute(
            "INSERT INTO registrations (name, birth_date, topic, materials_included, format) "
            "VALUES (?, ?, ?, ?, ?)",
            (name, birth_date, topic, int(materials_included), format)
        )
        self.connection.commit()
        return f"Registration for {name} added"

    def get_all(self) -> List[Tuple]:
        """Возвращает все записи регистрации."""
        cursor = self.connection.cursor()
        cursor.execute("SELECT * FROM registrations")
        return cursor.fetchall()

    def close(self):
        self.connection.close()