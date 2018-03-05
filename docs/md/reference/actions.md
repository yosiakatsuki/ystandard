# アクションリファレンス

## ys_amp_head

- file: inc/amp/amp-head.php
- description: AMPフォーマットのhead終了直前で実行するアクション。wp_headのAMPフォーマット版のようなもの

## ys_amp_footer

- file: inc/amp/amp-footer.php
- description: AMPフォーマットのbody終了直前で実行するアクション。wp_footerのAMPフォーマット版のようなもの

## ys_body_prepend

- file: header.php
- description: body開始直下で実行するアクション

## ys_body_append

- file: header.php
- description: body終了直前で実行するアクション

## ys_site_header_prepend

- file: header.php
- description: サイトheader開始直後で実行するアクション

## ys_site_header_append

- file: header.php
- description: サイトheader終了直前で実行するアクション

## ys_after_site_header

- file: header.php
- description: サイトheader終了直後で実行するアクション

## ys_site_main_prepend

- file: index.php,page.php,single.php,archive.php,404.php,page-template/**
- description: サイトmain開始直後で実行するアクション

## ys_site_main_append

- file: index.php,page.php,single.php,archive.php,404.php
- description: サイトmain終了直前で実行するアクション
