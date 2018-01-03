# version 1 → version 2 の変更点

## 関数

- post_meta系をまとめる
  - inc / post-meta.php

## css

- ys-button → btn


## 変更された関数
- ys_amp_the_amp_script -> ys_the_amp_script
- ys_extras_apple_touch_icon -> ys_the_apple_touch_icon
- ys_extras_add_canonical -> ys_the_canonical_tag
- ys_extras_adjacent_posts_rel_link_wp_head -> ys_the_rel_link
- ys_extras_add_noindex -> ys_the_noindex
- ys_setup_initialize -> ys_init
- ys_setup_remove_action -> ys_remove_action
- ys_setup_remove_emoji -> ys_remove_emoji
- ys_setup_remove_oembed -> ys_remove_oembed
- ys_extras_load_css_footer_js -> ys_enqueue_css
- ys_utilities_get_theme_version -> ys_util_get_theme_version
- ys_extras_add_async -> ys_add_async_on_js

## 削除予定の関数
- ys_settings
- ys_get_setting

## 削除された関数
- ys_template_the_head_normal
- ys_template_the_head_tag
- ys_template_the_inline_css
- ys_amp_the_head_amp
- ys_amp_the_amp_script
- ys_extras_apple_touch_icon
- ys_extras_add_canonical
- ys_extras_adjacent_posts_rel_link_wp_head
- ys_extras_add_noindex
- ys_extras_add_load_script_list
- ys_setup_initialize
- ys_init_change_logo_size
- ys_setup_remove_action
- ys_setup_remove_emoji
- ys_setup_remove_oembed
- ys_utilities_get_theme_version
- ys_utilities_json_encode
- ys_extras_add_async
- ys_utilities_get_load_script_array
