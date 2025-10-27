<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attributeを承認してください。',
    'accepted_if'          => ':otherが:valueの場合、:attributeを承認してください。',
    'active_url'           => ':attributeは有効なURLではありません。',
    'after'                => ':attributeは:dateより後の日付にしてください。',
    'after_or_equal'       => ':attributeは:date以降の日付にしてください。',
    'alpha'                => ':attributeは英字のみにしてください。',
    'alpha_dash'           => ':attributeは英数字とハイフン、アンダースコアのみにしてください。',
    'alpha_num'            => ':attributeは英数字のみにしてください。',
    'array'                => ':attributeは配列にしてください。',
    'ascii'                => ':attributeはASCII文字のみにしてください。',
    'before'                => ':attributeは:dateより前の日付にしてください。',
    'before_or_equal'      => ':attributeは:date以前の日付にしてください。',
    'between'              => [
        'array'   => ':attributeは:min〜:max個にしてください。',
        'file'    => ':attributeは:min〜:max KBにしてください。',
        'numeric' => ':attributeは:min〜:maxにしてください。',
        'string'  => ':attributeは:min〜:max文字にしてください。',
    ],
    'boolean'              => ':attributeはtrueまたはfalseにしてください。',
    'can'                  => ':attributeには権限がありません。',
    'confirmed'            => ':attribute確認が一致しません。',
    'current_password'     => 'パスワードが正しくありません。',
    'date'                 => ':attributeは正しい日付にしてください。',
    'date_equals'          => ':attributeは:dateと同じ日付にしてください。',
    'date_format'          => ':attributeは:formatの形式にしてください。',
    'decimal'              => ':attributeは小数点以下:decimal桁にしてください。',
    'declined'             => ':attributeを拒否してください。',
    'declined_if'          => ':otherが:valueの場合、:attributeを拒否してください。',
    'different'            => ':attributeと:otherは異なるものにしてください。',
    'digits'               => ':attributeは:digits桁にしてください。',
    'digits_between'       => ':attributeは:min〜:max桁にしてください。',
    'dimensions'           => ':attributeの画像サイズが正しくありません。',
    'distinct'             => ':attributeは重複しています。',
    'doesnt_end_with'      => ':attributeは:valuesで終わらないようにしてください。',
    'doesnt_start_with'    => ':attributeは:valuesで始まらないようにしてください。',
    'email'                => ':attributeは正しいメールアドレスにしてください。',
    'ends_with'            => ':attributeは:valuesで終わるようにしてください。',
    'enum'                 => '選択された:attributeは無効です。',
    'exists'               => '選択された:attributeは無効です。',
    'file'                 => ':attributeはファイルにしてください。',
    'filled'               => ':attributeは必須です。',
    'gt'                   => [
        'array'   => ':attributeは:value個より多くしてください。',
        'file'    => ':attributeは:value KBより大きくしてください。',
        'numeric' => ':attributeは:valueより大きくしてください。',
        'string'  => ':attributeは:value文字より多くしてください。',
    ],
    'gte'                  => [
        'array'   => ':attributeは:value個以上にしてください。',
        'file'    => ':attributeは:value KB以上にしてください。',
        'numeric' => ':attributeは:value以上にしてください。',
        'string'  => ':attributeは:value文字以上にしてください。',
    ],
    'hex_color'            => ':attributeは有効な16進カラーコードにしてください。',
    'image'                => ':attributeは画像にしてください。',
    'in'                   => '選択された:attributeは無効です。',
    'in_array'             => ':attributeは:otherに含まれていません。',
    'integer'              => ':attributeは整数にしてください。',
    'ip'                   => ':attributeは正しいIPアドレスにしてください。',
    'ipv4'                 => ':attributeは正しいIPv4アドレスにしてください。',
    'ipv6'                 => ':attributeは正しいIPv6アドレスにしてください。',
    'json'                 => ':attributeは正しいJSON文字列にしてください。',
    'lowercase'            => ':attributeは小文字にしてください。',
    'lt'                   => [
        'array'   => ':attributeは:value個より少なくしてください。',
        'file'    => ':attributeは:value KBより小さくしてください。',
        'numeric' => ':attributeは:valueより小さくしてください。',
        'string'  => ':attributeは:value文字より少なくしてください。',
    ],
    'lte'                  => [
        'array'   => ':attributeは:value個以下にしてください。',
        'file'    => ':attributeは:value KB以下にしてください。',
        'numeric' => ':attributeは:value以下にしてください。',
        'string'  => ':attributeは:value文字以下にしてください。',
    ],
    'mac_address'          => ':attributeは正しいMACアドレスにしてください。',
    'max'                  => [
        'array'   => ':attributeは:max個以下にしてください。',
        'file'    => ':attributeは:max KB以下にしてください。',
        'numeric' => ':attributeは:max以下にしてください。',
        'string'  => ':attributeは:max文字以下にしてください。',
    ],
    'max_digits'           => ':attributeは:max桁以下にしてください。',
    'mimes'                => ':attributeは:valuesのファイル形式にしてください。',
    'mimetypes'            => ':attributeは:valuesのファイル形式にしてください。',
    'min'                  => [
        'array'   => ':attributeは:min個以上にしてください。',
        'file'    => ':attributeは:min KB以上にしてください。',
        'numeric' => ':attributeは:min以上にしてください。',
        'string'  => ':attributeは:min文字以上にしてください。',
    ],
    'min_digits'           => ':attributeは:min桁以上にしてください。',
    'missing'              => ':attributeは存在しないようにしてください。',
    'missing_if'           => ':otherが:valueの場合、:attributeは存在しないようにしてください。',
    'missing_unless'       => ':otherが:valueでない場合、:attributeは存在しないようにしてください。',
    'missing_with'         => ':valuesが存在する場合、:attributeは存在しないようにしてください。',
    'missing_with_all'     => ':valuesがすべて存在する場合、:attributeは存在しないようにしてください。',
    'multiple_of'          => ':attributeは:valueの倍数にしてください。',
    'not_in'               => '選択された:attributeは無効です。',
    'not_regex'            => ':attributeの形式が正しくありません。',
    'numeric'              => ':attributeは数値にしてください。',
    'password'             => 'パスワードが正しくありません。',
    'present'              => ':attributeは存在する必要があります。',
    'prohibited'           => ':attributeは禁止されています。',
    'prohibited_if'        => ':otherが:valueの場合、:attributeは禁止されています。',
    'prohibited_unless'    => ':otherが:valuesでない場合、:attributeは禁止されています。',
    'prohibits'            => ':attributeが存在する場合、:otherは禁止されています。',
    'regex'                => ':attributeの形式が正しくありません。',
    'required'             => ':attributeは必須です。',
    'required_array_keys'  => ':attributeには:valuesのキーが必要です。',
    'required_if'          => ':otherが:valueの場合、:attributeは必須です。',
    'required_if_accepted' => ':otherが承認された場合、:attributeは必須です。',
    'required_unless'      => ':otherが:valuesでない場合、:attributeは必須です。',
    'required_with'        => ':valuesが存在する場合、:attributeは必須です。',
    'required_with_all'    => ':valuesがすべて存在する場合、:attributeは必須です。',
    'required_without'     => ':valuesが存在しない場合、:attributeは必須です。',
    'required_without_all' => ':valuesがすべて存在しない場合、:attributeは必須です。',
    'same'                 => ':attributeと:otherは一致する必要があります。',
    'size'                 => [
        'array'   => ':attributeは:size個にしてください。',
        'file'    => ':attributeは:size KBにしてください。',
        'numeric' => ':attributeは:sizeにしてください。',
        'string'  => ':attributeは:size文字にしてください。',
    ],
    'starts_with'          => ':attributeは:valuesで始まるようにしてください。',
    'string'               => ':attributeは文字列にしてください。',
    'timezone'             => ':attributeは正しいタイムゾーンにしてください。',
    'unique'               => ':attributeは既に使用されています。',
    'uploaded'             => ':attributeのアップロードに失敗しました。',
    'uppercase'            => ':attributeは大文字にしてください。',
    'url'                  => ':attributeは正しいURLにしてください。',
    'ulid'                 => ':attributeは正しいULIDにしてください。',
    'uuid'                 => ':attributeは正しいUUIDにしてください。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "rule.attribute" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => '名前',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'password_confirmation' => 'パスワード確認',
        'role' => '役割',
        'target_date' => '対象日',
        'health_score' => '体調スコア',
        'mental_score' => 'メンタルスコア',
        'body' => '内容',
        'classroom_id' => 'クラス',
        'grade_id' => '学年',
        'student_id' => '生徒',
        'teacher_id' => '教師',
    ],

];
