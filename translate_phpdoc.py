import re
from deep_translator import GoogleTranslator

# Вхідний файл (оригінал з українськими коментарями)
input_file = "flower-custom-functions/flower-custom-functions.php"
# Вихідний файл (з англійськими коментарями)
output_file = "flower-custom-functions/flower-custom-functions-en.php"

with open(input_file, "r", encoding="utf-8") as f:
    code = f.read()

# Знаходимо всі PHPDoc-коментарі /** ... */
def translate_phpdoc(match):
    original_comment = match.group(0)
    try:
        translated = GoogleTranslator(source='uk', target='en').translate(original_comment)
        return translated
    except Exception as e:
        print("Translation error:", e)
        return original_comment

# Заміна всіх PHPDoc-коментарів
translated_code = re.sub(r"/\*\*.*?\*/", translate_phpdoc, code, flags=re.DOTALL)

# Запис у новий файл
with open(output_file, "w", encoding="utf-8") as f:
    f.write(translated_code)

print("✅ Translation complete! Saved to", output_file)
