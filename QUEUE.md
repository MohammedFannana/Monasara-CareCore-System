تشغيل ومعالجة مهام الـ Queue (تقارير، صيغ كبيرة، إلخ)

لماذا؟
- تم نقل مهام توليد تقارير كبيرة (PDF/Excel) إلى وظائف (Jobs) مُرسَلة إلى الـ queue لتقليل زمن الاستجابة والذاكرة المستخدمة في طلبات HTTP.
- لكي تُنجَز هذه الوظائف فعليًا، يجب تشغيل عامل/عاملات الـ queue في بيئة التنفيذ (مثل php artisan queue:work أو عن طريق Laravel Horizon أو Supervisor).

متطلبات أساسية
1) اتصال قاعدة البيانات مُعدّ (لـ driver = database) — الملف .env أو .env.example يحتوي على:
   QUEUE_CONNECTION=database
   (إذا تفضّل Redis/Beanstalk/SQS فغيّر قيمة QUEUE_CONNECTION وأعد تكوين الاتصالات في config/queue.php)

2) جدول jobs و failed_jobs وجدولا job_batches (إذا كنت تستخدم batching). المشروع يحتوي على migration افتراضية:
   database/migrations/0001_01_01_000002_create_jobs_table.php

لإنشاء الجداول (في بيئة التطوير أو بعد النشر):
   php artisan migrate

تشغيل عامل Queue (التشغيل اليدوي)
- تشغيل عامل واحد بسيط (مناسب للتجارب المحلية):
   php artisan queue:work --sleep=3 --tries=3 --timeout=90

- تشغيل عامل مع تحديد الطابور الافتراضي:
   php artisan queue:work --queue=default --sleep=3 --tries=3 --timeout=90

تشغيل عامل مؤقت في الخلفية (مقترح للإنتاج)
- على Linux مع systemd أو Supervisor (أنفذ أمثلة الإعداد التالية وتأكد من أن الخدمة تعمل دائماً وتُعاد عند إعادة التشغيل).

مثال Supervisor (ملف /etc/supervisor/conf.d/amal-orphans-queue.conf):

[program:amal-orphans-queue]
command=php /path/to/project/artisan queue:work --sleep=3 --tries=3 --timeout=90 --queue=default
process_name=%(program_name)s_%(process_num)02d
numprocs=2
directory=/path/to/project
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/log/amal-orphans/queue.log
stopwaitsecs=3600

systemd مثال للخدمة (مثال بسيط):

[Unit]
Description=Laravel Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /path/to/project/artisan queue:work --sleep=3 --tries=3 --timeout=90 --queue=default

[Install]
WantedBy=multi-user.target

ملاحظة: بالنسبة لبيئات Windows، يمكن استخدام NSSM أو تشغيل سكريبت PowerShell الذي يبدأ العملية في نافذة منفصلة أو كخدمة.

PowerShell helper (مثال في start-queue.ps1)
- هذا سكربت صغير لتشغيل عامل الـ queue على Windows في نافذة منفصلة:

Start-Process -FilePath "php" -ArgumentList "artisan queue:work --sleep=3 --tries=3 --timeout=90" -NoNewWindow -WindowStyle Hidden

(يمكن تشغيله بواسطة PowerShell: .\start-queue.ps1)

ملاحظات عملية حول الأداء والضبط
- retry_after / timeout: اضبط --timeout في queue:work و retry_after في config/queue.php لتتوافق مع وقت تنفيذ الـ Job الفعلي.
- عدد العمال: لعمليات إخراج تقارير كبيرة يفضّل تشغيل عامل متعدد العمليات أو تحديد أكثر من process في Supervisor.
- مراقبة: اجعل ملف failed_jobs مفعلًا (config/queue.php -> failed.driver يجب أن يكون مناسبًا) وتابع السجلات (failed_jobs, logs).
- تسليم الموارد: لتقارير كبيرة استخدم الأسلوب chunking عند قراءة البيانات من DB داخل الـ Job لتقليل الذاكرة.

تشغيل الاختبارات/التحقق بعد الإعداد
1) شغّل الهجرة: php artisan migrate
2) شغّل العامل محلياً: php artisan queue:work --sleep=3 --tries=3 --timeout=90
3) ابدأ عملية توليد تقرير من واجهة الإدارة (مثلاً: زر تشغيل التقرير) وتأكد من إنشاء ملف التقرير داخل storage وأنّ المهمة لم تفشل.

إذا رغبت، أقدر الآن:
- أ) أضيف ملف السكربت PowerShell في repo: start-queue.ps1 (مفيد لو الخادم Windows) — آمن وسريع.
- ب) أضيف مثال Supervisor/systemd مفصّل حسب المسارات التي تحددها لي.
- ج) أجهز مراقبة إضافية (failed job notifications) أو مثال Horizon.

ما التالي تريده الآن؟
- أضف سكربت PowerShell للمشروع (Recommended for Windows) — سأنشئه الآن.
- جهّز مثال Supervisor/systemd مفصّل حسب المسارات التي تحددها لي.
- لا شيء الآن، فقط وثائق كافية (تمت إضافتها).
