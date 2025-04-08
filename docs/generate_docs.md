# Інструкція з генерації документації

Ця інструкція пояснює, як згенерувати автоматичну документацію для кастомного плагіна інтернет-магазину квітів.

## 📌 Передумови

- Встановлено [PHP](https://www.php.net/downloads)
- Операційна система: Windows
- Проєкт знаходиться у теці без кириличних символів у шляху
- Завантажено файл `phpdoc.phar` з офіційного сайту:
  [https://phpdoc.org/phpDocumentor.phar](https://phpdoc.org/phpDocumentor.phar)

> ⚠️ **Composer-версія не рекомендується**, через часті конфлікти залежностей.

---

## 🧾 Кроки для генерації документації

### 1. Завантаження phpDocumentor

Завантаж файл [`phpDocumentor.phar`](https://phpdoc.org/phpDocumentor.phar) та помісти його в корінь
проєкту (`flower-shop-bachelor`).

**Перейменуй файл на** `phpdoc.phar` (для зручності).

---

### 2. Налаштування конфігурації

Створи файл `phpdoc.xml` у корені проєкту з таким вмістом:

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<phpdocumentor configVersion="3">
    <title>Flower Shop Plugin Documentation</title>

    <paths>
        <output>docs/phpdocs</output>
    </paths>

    <settings>
        <visibility>
            <element name="public"/>
        </visibility>
    </settings>

    <templates>
        <template name="default"/>
    </templates>
</phpdocumentor>
```

### 3. Генерація документації

У терміналі виконай команду:

```bash
php phpdoc.phar -d flower-custom-functions -c phpdoc.xml
```

* d — директорія з вихідним кодом
* c — файл конфігурації

📂 Після успішного виконання, документація з’явиться в теці `docs/phpdocs`.

### 4. Створення архіву

Створи архів із вмістом папки `docs/phpdocs` (наприклад, .zip) і додай його до звіту.

## ✅ Результат

* Створено HTML-документацію до всіх публічних функцій плагіна.
* Вона зберігається у `docs/phpdocs/index.html`.
* Можна переглядати в браузері — відкрий файл `index.html`.
