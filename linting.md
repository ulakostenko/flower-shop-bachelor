# Linting

## Обраний лінтер — *PHP_CodeSniffer + WordPress Coding Standards*

У цьому проєкті для перевірки якості PHP-коду
використовується [**PHP_CodeSniffer**](https://github.com/squizlabs/PHP_CodeSniffer)
у поєднанні з [**WordPress Coding Standards (WPCS)**](https://github.com/WordPress/WordPress-Coding-Standards).

---

## Чому саме цей лінтер?

- Стандарти WPCS — офіційні рекомендації від WordPress для плагінів, тем і ядра.
- PHPCS інтегрується через Composer, не вимагає глобального встановлення.
- Автоматичне виправлення більшості помилок за допомогою `phpcbf`.
- Звіт у зручному форматі прямо в терміналі.
- Підтримується у PhpStorm, VS Code та CI/CD.

---

## 📄 Основні правила WordPress Coding Standards (WPCS)

`WPCS` — це набір правил, які регламентують стиль написання PHP-коду
відповідно до офіційних стандартів WordPress. Основні з них:

### 1. Відступи

- Використовуються **табуляції** (tabs) для відступів, **а не пробіли**.
- Один рівень відступу — це один tab.

### 2. Дужки

- Відкриваюча фігурна дужка `{` ставиться **на новому рядку** після оголошення функції, умови чи циклу.
- Закриваюча дужка `}` завжди розміщується на новому рядку після блоку коду.

### 3. Розмітка PHPDoc

- Коментарі до функцій повинні використовувати стиль `/** ... */`.
- Кожна функція повинна мати опис параметрів (`@param`) та повертаного значення (`@return`).

### 4. Форматування

- Один пробіл після ключових слів (`if`, `foreach`, `function` тощо).
- Пробіли навколо операторів (`=`, `==`, `.` тощо).
- Всі рядки повинні закінчуватись символом переносу рядка у форматі **LF** (`\n`).

### 5. Безпека

- Весь вивід у HTML повинен проходити через функції екранування (`esc_html()`, `esc_attr()` тощо).
- Заборонено використовувати необроблені змінні без перевірки чи очищення.

---

## ⚙️ Інструкція для роботи з лінтером

### 1. Встановлення залежностей

```bash
composer install
```

Ця команда встановить PHPCS, WPCS та пов’язані залежності з composer.json.

### 2. Налаштування WPCS

```bash
./vendor/bin/phpcs --config-set installed_paths vendor/wp-coding-standards/wpcs
```

Це вказує PHPCS, де шукати WordPress Coding Standards.

### 3. Запуск лінтера

```bash
./vendor/bin/phpcs --standard=WordPress flower-custom-functions/
```

Ця команда перевірить код у папці flower-custom-functions/ на відповідність стандарту WordPress.

### 4. Автоматичне виправлення

```bash
./vendor/bin/phpcbf --standard=WordPress flower-custom-functions/
```

Ця команда автоматично виправить більшість проблем зі стилем коду.


## Git Hooks

У проєкті налаштовано `pre-commit` хук, який запускає лінтер перед кожним комітом.
Якщо у коді знайдено помилки стилю — коміт буде скасовано.

```bash
#!/bin/sh
echo "Running phpcs before commit..."
./vendor/bin/phpcs --standard=WordPress flower-custom-functions/

if [ $? -ne 0 ]; then
  echo "Linter failed. Commit aborted."
  exit 1
fi

echo "Linter passed. Commit allowed."
```

Цей файл знаходиться у `.git/hooks/pre-commit` і зроблений виконуваним за допомогою:

```bash
chmod +x .git/hooks/pre-commit
```

## Інтеграція в процес збірки

У `composer.json` знаходиться скрипт для запуску лінтера вручну:

```bash
"scripts": {
  "lint": "phpcs --standard=WordPress flower-custom-functions/",
  "lint:fix": "phpcbf --standard=WordPress flower-custom-functions/",
  "check": [
    "@lint"
  ]
}
```

Перевірити код можна так:

```bash
composer run check    # перевірка
composer run lint     # перевірка
composer run lint:fix # автовиправлення
```

## Статична типізація (PHPDoc)

PHP не має вбудованої жорсткої типізації, тому використовується PHPDoc:

- кожна функція описана за допомогою `@param`, `@return`
- дотримуються правил **WordPress PHPDoc**
- перевіряється наявність коментарів та типів через PHPCS