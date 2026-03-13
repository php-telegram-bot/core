# ميزات Telegram Bot API 9.3 وكيفية استخدامها

يقدم هذا الملف شرحاً للميزات الجديدة التي تم إضافتها في إصدار **Bot API 9.3** وكيفية الاستفادة منها عبر هذه المكتبة.

## 1. المواضيع في المحادثات الخاصة (Topics in Private Chats)

أصبح بإمكان البوتات الآن تفعيل نظام "المواضيع" (Topics) حتى في المحادثات الخاصة مع المستخدمين، مما يسمح بتنظيم المحادثة بشكل أفضل.

### التحقق من تفعيل الميزة
يمكنك معرفة ما إذا كان المستخدم قد فعل نظام المواضيع عبر حقل `has_topics_enabled` في كائن `User`:

```php
$user = $message->getFrom();
if ($user->getHasTopicsEnabled()) {
    // نظام المواضيع مفعل في هذه المحادثة الخاصة
}
```

### إرسال رسالة إلى موضوع محدد
تدعم جميع طرق الإرسال (مثل `sendMessage`, `sendPhoto`, إلخ) بارامتر `message_thread_id` لإرسال الرسالة إلى موضوع معين داخل المحادثة الخاصة:

```php
Request::sendMessage([
    'chat_id'           => $user_id,
    'text'              => 'رسالة داخل موضوع خاص',
    'message_thread_id' => $thread_id,
]);
```

---

## 2. بث مسودة الرسالة (sendMessageDraft)

تسمح هذه الطريقة الجديدة ببث أجزاء من الرسالة للمستخدم أثناء توليدها (مفيد جداً لبوتات الذكاء الاصطناعي).

**ملاحظة:** هذه الميزة مدعومة حالياً فقط للبوتات التي فعلت نظام المواضيع (Forum Topic Mode).

```php
Request::sendMessageDraft([
    'chat_id' => $user_id,
    'text'    => 'جاري توليد الإجابة...',
]);
```

---

## 3. تحديثات الهدايا (Gifts)

تم إضافة ميزات قوية للتعامل مع الهدايا العادية والفريدة (Unique Gifts).

### الحصول على هدايا المستخدم أو الدردشة
تم إضافة طرق جديدة لجلب قائمة الهدايا:

```php
// هدايا المستخدم
$user_gifts = Request::getUserGifts(['user_id' => $user_id]);

// هدايا الدردشة (القنوات)
$chat_gifts = Request::getChatGifts(['chat_id' => $chat_id]);
```

### تحديثات الهدايا الفريدة (Unique Gift Info)
تم استبدال حقل `last_resale_star_count` بحقول أكثر تفصيلاً:
- `getLastResaleCurrency()`: العملة المستخدمة (مثل XTR أو TON).
- `getLastResaleAmount()`: المبلغ المدفوع.

---

## 4. إعادة نشر القصص (repostStory)

يمكن للبوتات الآن إعادة نشر القصص عبر حسابات الأعمال المختلفة التي تديرها.

```php
Request::repostStory([
    'business_connection_id' => $connection_id,
    'chat_id'                => $target_chat_id,
    'story_id'               => $story_id,
]);
```

---

## 5. ميزات متنوعة

### تأثيرات الرسائل (Message Effects)
أصبح بإمكانك إضافة تأثيرات بصرية عند إعادة توجيه أو نسخ الرسائل باستخدام `message_effect_id`:

```php
Request::copyMessage([
    'chat_id'           => $target_chat_id,
    'from_chat_id'      => $source_chat_id,
    'message_id'        => $message_id,
    'message_effect_id' => '5104841245755180586', // مثال لمعرف تأثير
]);
```

### تقييم المستخدم (User Rating)
يمكن الحصول على تقييم المستخدم بناءً على إنفاقه لنجوم تلجرام عبر `ChatFullInfo`:

```php
$chat_info = Request::getChat(['chat_id' => $user_id])->getResult();
$rating = $chat_info->getRating();
echo "مستوى المستخدم: " . $rating->getLevel();
```

---

للمزيد من التفاصيل حول كافة التغييرات، يرجى مراجعة [سجل تغييرات تلجرام الرسمي](https://core.telegram.org/bots/api-changelog#december-31-2025).
