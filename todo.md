## Improve Security & Roles (Cashier, Admin, Manager)


Quick Full Clean Command Sequence

On dev environment:

php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload
php artisan migrate:fresh --seed
php artisan serve

1️⃣ وظيفة ملفات bootstrap
bootstrap: يحتوي على ملفات تهيئة التطبيق. أهم ملف هنا هو 
app.php الذي يقوم بتحميل الإطار (Framework) وتجهيزه قبل التشغيل.

config: يحتوي على ملفات الإعدادات الخاصة بالنظام (مثل إعدادات قاعدة البيانات، البريد الإلكتروني، الملفات، إلخ).

2️⃣ وظيفة ملفات config

كل ملف config يعيد array من الإعدادات. مثال:
وظيفة config/: إعدادات التطبيق العامة لكل شيء تقريبًا.

نعدل فيه غالبًا: لتغيير إعدادات البيئة، اللغة، قاعدة البيانات، البريد، الخدمات الخارجية.

لا نعدل فيه بدون سبب: الباقي الافتراضي يعمل جيد.

أفضل ممارسة: التغيير يكون عادة في .env لتجنب تعديل الملفات الافتراضية.
الملف	الوظيفة
app.php	إعدادات عامة مثل: اسم التطبيق، اللغة، Timezone، Environment
database.php	إعدادات قاعدة البيانات (نوع DB، host، username، password)
mail.php	إعدادات البريد (SMTP server)
auth.php	إعدادات المصادقة، guards، providers، roles
cache.php	إعدادات التخزين المؤقت (Cache)
queue.php	إعدادات الطوابير (Jobs)
services.php	إعدادات خدمات خارجية (مثل Twilio, Stripe, Google API)

مجلد database/

هذا المجلد يحتوي كل شيء متعلق بقاعدة البيانات، من إنشاء الجداول، تعبئتها بالبيانات، وإنشاء بيانات وهمية للاختبارات.
## **3️⃣ كيف يعملون معًا**
1. **Migrations** → تُنشئ الجداول والأعمدة في قاعدة البيانات.
2. **Factories** → تُنشئ بيانات وهمية قابلة للتخصيص لكل Model.
3. **Seeders** → تستخدم Factories أو بيانات ثابتة لتعبئة الجداول.


