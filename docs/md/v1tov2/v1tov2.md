# version 1 → version 2 の変更点

## 関数

- post_meta系をまとめる
  - inc / post-meta.php

## テンプレート
- template-one-column.php -> page-template/template-one-column.phpに変わることに気をつける

## フッターのSNSリンクの背景色
- 設定が変わったので実装変える
- hover時はopacity +20 する

## css
- ys-button → btn

## 設定の再設定が必要な箇所
- OGPデフォルト画像の再設定
- ヘッダーメニュー
- Google Analyticsのトラッキングコードタイプ
- サイドバーのモバイル表示（表示するがデフォルトになる）
- 絵文字関連スタイルシート・スクリプトを出力しない（設定項目が変わるので「出力する」にしていた場合再設定が必要）
- oembed関連スタイルシート・スクリプトを出力しない（設定項目が変わるので「出力する」にしていた場合再設定が必要）

## 削除された設定
- AMP Facebookシェアボタン用 app id (ys_amp_share_fb_app_id)
- AMP 通常ビューへのリンク表示 (ys_amp_normal_link_share_btn,ys_amp_normal_link)
- AMP scriptタグを削除してAMPページを作成する  (ys_amp_del_script)
- AMP style属性を削除してAMPページを作成する (ys_amp_del_style)
- AMP AMPページでも記事下のジェットを表示する (ys_show_entry_footer_widget_amp)

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
- ys_extras_add_amphtml -> ys_the_amphtml
- ys_extras_add_twitter_card -> ys_get_the_twitter_card
- ys_customizer -> ys_theme_customizer
- ys_customizer_color_setting -> ys_customizer_color
- ys_template_get_the_custom_excerpt -> ys_util_get_the_custom_excerpt
- ys_extras_get_the_archive_title -> ys_get_the_archive_title
- ys_extras_add_facebook_ogp -> ys_get_the_ogp
- ys_extras_add_googleanarytics -> ys_the_google_anarytics
- ys_template_get_front_page_template_part -> ys_get_front_page_template

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
- ys_extras_add_async
- ys_extras_add_amphtml
- ys_extras_add_twitter_card
- ys_setup_initialize
- ys_init_change_logo_size
- ys_setup_remove_action
- ys_setup_remove_emoji
- ys_setup_remove_oembed
- ys_utilities_get_theme_version
- ys_utilities_json_encode
- ys_utilities_get_load_script_array
- ys_customizer
- ys_customizer_color_setting
- ys_template_the_follow_sns_list
- ys_template_get_the_custom_excerpt
- ys_extras_get_the_archive_title
- ys_extras_add_facebook_ogp
- ys_extras_add_googleanarytics
- ys_template_the_header_attr
- ys_template_the_content_attr
- ys_template_the_site_hero
- ys_utilities_get_custom_logo_image_src
- ys_template_the_header_site_title_logo
- ys_template_the_header_global_menu
- ys_template_the_sidebar_attr


## 変更されたフィルタ
- ys_ga_tracking_id -> ys_get_google_anarytics_tracking_id
- ys_header_description -> ys_the_blog_description
- ys_get_front_page_template_part -> ys_get_front_page_template

## 削除されたフィルタ
- ys_ga_function
- ys_amp_ga_json
- ys_the_header_attr
- ys_the_content_attr
- ys_the_sidebar_attr
- ys_site_hero
