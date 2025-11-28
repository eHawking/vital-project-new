<?php 

return [
    'accepted' => ':attribute قبول کیا جانا ضروری ہے۔',
    'active_url' => ':attribute ایک درست URL نہیں ہے۔',
    'after' => ':attribute کی تاریخ :date کے بعد کی ہونی چاہیے۔',
    'after_or_equal' => ':attribute کی تاریخ :date کے بعد یا اس کے برابر ہونی چاہیے۔',
    'alpha' => ':attribute صرف حروف (Letters) پر مشتمل ہو سکتی ہے۔',
    'alpha_dash' => ':attribute میں صرف حروف، نمبر، خط (–) اور نچلا خط (_) استعمال ہو سکتا ہے۔',
    'alpha_num' => ':attribute میں صرف حروف اور نمبرز ہی ہو سکتے ہیں۔',
    'array' => ':attribute ایک صف (Array) ہونی چاہیے۔',
    
    'attributes' => [],

    'before' => ':attribute کی تاریخ :date سے پہلے کی ہونی چاہیے۔',
    'before_or_equal' => ':attribute کی تاریخ :date سے پہلے یا اس کے برابر ہونی چاہیے۔',

    'between' => [
        'array' => ':attribute میں کم از کم :min اور زیادہ سے زیادہ :max آئٹمز ہونے چاہیں۔',
        'file' => ':attribute کا سائز :min اور :max کلو بائٹس کے درمیان ہونا چاہیے۔',
        'numeric' => ':attribute کی قیمت :min اور :max کے درمیان ہونی چاہیے۔',
        'string' => ':attribute :min اور :max حروف کے درمیان ہونی چاہیے۔',
    ],

    'boolean' => ':attribute کی قدر صحیح (true) یا غلط (false) ہونی چاہیے۔',
    'confirmed' => ':attribute کی تصدیق درست نہیں ہے۔',
    
    'custom' => [
        'attribute-name' => [
            'rule-name' => 'کسٹم میسج',
        ],
    ],

    'date' => ':attribute ایک درست تاریخ نہیں ہے۔',
    'date_equals' => ':attribute کی تاریخ :date کے برابر ہونی چاہیے۔',
    'date_format' => ':attribute کا فارمیٹ :format سے میچ نہیں کرتا۔',
    'different' => ':attribute اور :other مختلف ہونے چاہیں۔',
    'digits' => ':attribute میں بالکل :digits نمبرز ہونے چاہیں۔',
    'digits_between' => ':attribute میں کم از کم :min اور زیادہ سے زیادہ :max نمبرز ہونے چاہیں۔',
    'dimensions' => ':attribute کی تصویر کے سائز درست نہیں ہیں۔',
    'distinct' => ':attribute کی قدر دہرائی گئی ہے۔',
    'email' => ':attribute ایک درست ای میل ہونی چاہیے۔',
    'ends_with' => ':attribute مندرجہ ذیل الفاظ میں سے کسی ایک پر ختم ہونی چاہیے: :values۔',
    'exists' => 'منتخب کردہ :attribute غلط ہے۔',
    'file' => ':attribute ایک فائل ہونی چاہیے۔',
    'filled' => ':attribute کی قدر درج کرنی ضروری ہے۔',

    'gt' => [
        'array' => ':attribute میں :value سے زیادہ آئٹمز ہونے چاہیں۔',
        'file' => ':attribute کا سائز :value کلو بائٹس سے زیادہ ہونا چاہیے۔',
        'numeric' => ':attribute کی قدر :value سے زیادہ ہونی چاہیے۔',
        'string' => ':attribute میں :value حروف سے زیادہ ہونے چاہیں۔',
    ],

    'gte' => [
        'array' => ':attribute میں کم از کم :value آئٹمز ہونے چاہیں۔',
        'file' => ':attribute کا سائز :value کلو بائٹس یا اس سے زیادہ ہونا چاہیے۔',
        'numeric' => ':attribute کی قدر :value یا اس سے زیادہ ہونی چاہیے۔',
        'string' => ':attribute میں :value حروف یا اس سے زیادہ ہونے چاہیں۔',
    ],

    'image' => ':attribute ایک تصویر ہونی چاہیے۔',
    'in' => 'منتخب کردہ :attribute غلط ہے۔',
    'integer' => ':attribute ایک عدد (Integer) ہونی چاہیے۔',
    'in_array' => ':attribute کی قدر :other میں موجود نہیں ہے۔',
    'ip' => ':attribute ایک درست IP ایڈریس ہونی چاہیے۔',
    'ipv4' => ':attribute ایک درست IPv4 ایڈریس ہونی چاہیے۔',
    'ipv6' => ':attribute ایک درست IPv6 ایڈریس ہونی چاہیے۔',
    'json' => ':attribute ایک درست JSON ہونی چاہیے۔',

    'lt' => [
        'array' => ':attribute میں :value سے کم آئٹمز ہونے چاہیں۔',
        'file' => ':attribute کا سائز :value کلو بائٹس سے کم ہونا چاہیے۔',
        'numeric' => ':attribute کی قدر :value سے کم ہونی چاہیے۔',
        'string' => ':attribute میں :value حروف سے کم ہونے چاہیں۔',
    ],

    'lte' => [
        'array' => ':attribute میں :value سے زیادہ آئٹمز نہیں ہونے چاہیں۔',
        'file' => ':attribute کا سائز :value کلو بائٹس سے زیادہ نہیں ہونا چاہیے۔',
        'numeric' => ':attribute کی قدر :value سے زیادہ نہیں ہونی چاہیے۔',
        'string' => ':attribute میں :value حروف سے زیادہ نہیں ہونے چاہیں۔',
    ],

    'max' => [
        'array' => ':attribute میں :max سے زیادہ آئٹمز نہیں ہونے چاہیں۔',
        'file' => ':attribute کا سائز :max کلو بائٹس سے زیادہ نہیں ہونا چاہیے۔',
        'numeric' => ':attribute کی قدر :max سے زیادہ نہیں ہونی چاہیے۔',
        'string' => ':attribute میں :max حروف سے زیادہ نہیں ہونے چاہیں۔',
    ],

    'mimes' => ':attribute کی قسم :values ہونی چاہیے۔',
    'mimetypes' => ':attribute کی قسم :values ہونی چاہیے۔',

    'min' => [
        'array' => ':attribute میں کم از کم :min آئٹمز ہونے چاہیں۔',
        'file' => ':attribute کا سائز کم از کم :min کلو بائٹس ہونا چاہیے۔',
        'numeric' => ':attribute کی قدر کم از کم :min ہونی چاہیے۔',
        'string' => ':attribute میں کم از کم :min حروف ہونے چاہیں۔',
    ],

    'not_in' => 'منتخب کردہ :attribute غلط ہے۔',
    'not_regex' => ':attribute کا فارمیٹ درست نہیں ہے۔',
    'numeric' => ':attribute ایک عدد (Number) ہونا چاہیے۔',
    'password' => 'غلط پاسورڈ۔',
    'present' => ':attribute موجود ہونا چاہیے۔',
    'regex' => ':attribute کا فارمیٹ درست نہیں ہے۔',
    'required' => ':attribute درج کرنا ضروری ہے۔',
    'required_if' => 'جب :other کی قدر :value ہو تو :attribute درج کرنا ضروری ہے۔',
    'required_unless' => 'جب تک :other میں :values نہ ہو، تب تک :attribute درج کرنا ضروری ہے۔',
    'required_with' => 'جب :values موجود ہو تو :attribute درج کرنا ضروری ہے۔',
    'required_without' => 'جب :values موجود نہ ہو تو :attribute درج کرنا ضروری ہے۔',
    'required_without_all' => 'جب کوئی بھی :values موجود نہ ہو تو :attribute درج کرنا ضروری ہے۔',
    'required_with_all' => 'جب تمام :values موجود ہوں تو :attribute درج کرنا ضروری ہے۔',
    'same' => ':attribute اور :other کی قدر ایک جیسی ہونی چاہیے۔',

    'size' => [
        'array' => ':attribute میں بالکل :size آئٹمز ہونے چاہیں۔',
        'file' => ':attribute کا سائز :size کلو بائٹس ہونا چاہیے۔',
        'numeric' => ':attribute کی قدر :size ہونی چاہیے۔',
        'string' => ':attribute میں بالکل :size حروف ہونے چاہیں۔',
    ],

    'starts_with' => ':attribute مندرجہ ذیل الفاظ میں سے کسی ایک سے شروع ہونی چاہیے: :values۔',
    'string' => ':attribute ایک سٹرنگ ہونی چاہیے۔',
    'timezone' => ':attribute ایک درست علاقہ (Zone) ہونا چاہیے۔',
    'unique' => ':attribute پہلے ہی استعمال ہو چکی ہے۔',
    'uploaded' => ':attribute اپ لوڈ نہیں ہو سکی۔',
    'url' => ':attribute کا فارمیٹ درست نہیں ہے۔',
    'uuid' => ':attribute ایک درست UUID ہونی چاہیے۔',
];