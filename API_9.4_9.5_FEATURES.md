# ميزات تحديث Bot API 9.4 و 9.5

يدعم هذا الإصدار من المكتبة ميزات تحديث Telegram Bot API 9.4 و 9.5. فيما يلي الميزات الجديدة وكيفية استخدامها.

## Bot API 9.5

### 1. الكيان الجديد `date_time` في الرسائل
تم إضافة نوع جديد للكيانات `MessageEntity` يسمى `date_time` لعرض التاريخ والوقت بتنسيق محدد للمستخدم.

```php
// مثال على تنسيق التاريخ والوقت في MarkdownV2
$text = "موعد الاجتماع: ![22:45 tomorrow](tg://time?unix=1740861900&format=wDT)";

Request::sendMessage([
    'chat_id'    => $chat_id,
    'text'       => $text,
    'parse_mode' => 'MarkdownV2',
]);
```

### 2. ميزة بث الرسائل الجاري إنشاؤها (`sendMessageDraft`)
أصبح بإمكان جميع البوتات الآن استخدام الطريقة `sendMessageDraft` لبث أجزاء من الرسالة للمستخدم أثناء إنشائها (مثل تطبيقات الذكاء الاصطناعي).

```php
Request::sendMessageDraft([
    'chat_id'  => $chat_id,
    'draft_id' => 12345, // معرف فريد للمسودة
    'text'     => "جاري التفكير...",
]);

// تحديث النص (سيظهر بحركة أنيميشن)
Request::sendMessageDraft([
    'chat_id'  => $chat_id,
    'draft_id' => 12345,
    'text'     => "جاري التفكير... لقد وجدت الحل!",
]);

// إرسال الرسالة النهائية باستخدام sendMessage العادي
```

### 3. وسوم الأعضاء (Member Tags) وأذونات جديدة
تم إضافة حقل `tag` للأعضاء العاديين، وطريقة `setChatMemberTag` لتعيينها، بالإضافة إلى أذونات التحكم بها.

```php
// تعيين وسم لعضو
Request::setChatMemberTag([
    'chat_id' => $chat_id,
    'user_id' => $user_id,
    'tag'     => "مميز",
]);
```

الأذونات الجديدة:
- `can_edit_tag`: يسمح للمستخدم بتعديل وسمه الخاص.
- `can_manage_tags`: يسمح للمشرفين بتعديل وسوم الأعضاء.

---

## Bot API 9.4

### 1. المواضيع (Topics) في الدردشات الخاصة
أصبح بإمكان البوتات إنشاء مواضيع في الدردشات الخاصة مع المستخدمين، تماماً كما في المجموعات الكبيرة.

```php
// إنشاء موضوع في دردشة خاصة
Request::createForumTopic([
    'chat_id' => $user_id,
    'name'    => "الدعم الفني",
]);
```

### 2. أزرار ملونة ورموز تعبيرية مخصصة
يمكن الآن تغيير لون الأزرار وإضافة رموز تعبيرية مخصصة لها.

```php
$keyboard = new InlineKeyboard([
    ['text' => 'حذف', 'callback_data' => 'delete', 'style' => 'danger'], // زر أحمر
    ['text' => 'تأكيد', 'callback_data' => 'ok', 'style' => 'success'],  // زر أخضر
    ['text' => 'معلومات', 'callback_data' => 'info', 'style' => 'primary'], // زر أزرق
]);

$keyboard_with_emoji = new Keyboard([
    ['text' => 'الإعدادات', 'icon_custom_emoji_id' => '5368324170671202286'],
]);
```

### 3. إدارة صورة الملف الشخصي للبوت
طرق جديدة لتعيين أو حذف صورة البوت الشخصية مباشرة عبر API.

```php
Request::setMyProfilePhoto([
    'photo' => '/path/to/photo.jpg',
]);

Request::removeMyProfilePhoto();
```

### 4. جودة الفيديو ووسائط الملف الشخصي
- `VideoQuality`: الحصول على معلومات حول الجودات المتاحة للفيديو.
- `first_profile_audio`: الحصول على أول ملف صوتي في ملف تعريف المستخدم (للحسابات المميزة).
- `getUserProfileAudios`: جلب قائمة الملفات الصوتية في ملف تعريف المستخدم.

```php
$response = Request::getUserProfileAudios(['user_id' => $user_id]);
if ($response->isOk()) {
    $audios = $response->getResult()->getAudios();
}
```

- `first_profile_audio`: في كائن `ChatFullInfo` (للدردشات الخاصة)، يمثل أول ملف صوتي في ملف تعريف المستخدم.

```php
// جلب أول صوت في الملف الشخصي من معلومات الدردشة الكاملة
$chat_info = Request::getChat(['chat_id' => $user_id])->getResult();
$first_audio = $chat_info->getFirstProfileAudio();
```

### 5. زر الدردشة السفلي (Chat Bottom Button)
تم إضافة دعم لتعيين زر في أسفل الدردشة يفتح تطبيق ويب (Mini App).

```php
Request::setChatBottomButton([
    'chat_id' => $chat_id,
    'bottom_button' => [
        'text' => 'فتح المتجر',
        'web_app' => ['url' => 'https://example.com/shop'],
    ],
]);
```

### 6. ميزات الهدايا (Gifts)
- إضافة حقل `rarity` لنماذج الهدايا الفريدة (`UniqueGiftModel`).
- إضافة حقل `is_burned` للهدايا الفريدة (`UniqueGift`) التي تم استخدامها في نظام الصياغة (Crafting).
