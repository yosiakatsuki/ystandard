# ブロックエディター投稿設定の拡張API

このAPIは、yStandard v5の投稿設定モーダルへ外部プラグインの設定コンポーネントを追加するための契約です。投稿設定モーダルはブロックエディター専用です。

## 登録契約

```ts
interface PostSettingsContext {
	apiVersion: 1;
	postType: string;
	postId: number;
}

interface PostSettingsSection {
	id: string;
	title: string;
	order: number;
}

interface PostSettingsItemProps {
	postType: string;
	postId: number;
}

interface PostSettingsItem {
	id: string;
	section: string;
	order: number;
	Component: ComponentType<PostSettingsItemProps>;
}
```

セクションは`ystandard.hooks.postSettings.sections`、設定項目は`ystandard.hooks.postSettings.items`へ登録します。どちらのフィルターにも現在の`PostSettingsContext`が第2引数として渡されます。

## yStandard標準セクション

通常の投稿タイプでは、外部設定項目から参照できる次の予約済みIDがあります。

| セクションID | モーダル内の見出し |
|---|---|
| `ystandard/post` | `要素の表示設定` |
| `ystandard/seo` | `SEO設定` |
| `ystandard/sns` | `SNS設定` |

標準セクションへ項目を追加する場合、セクション自体の登録は不要です。

```tsx
import { addFilter } from '@wordpress/hooks';

addFilter(
	'ystandard.hooks.postSettings.items',
	'example-plugin/seo-title',
	( items, context ) => [
		...items,
		{
			id: 'example-plugin/seo-title',
			section: 'ystandard/seo',
			order: 100,
			Component: SeoTitleSetting,
		},
	]
);
```

`ystandard/*`の予約済みセクションは外部から再定義できません。

## 外部セクション

独自セクションを追加する場合は、セクションと項目をそれぞれ登録します。

```tsx
import { addFilter } from '@wordpress/hooks';

addFilter(
	'ystandard.hooks.postSettings.sections',
	'example-plugin/design-section',
	( sections, context ) => [
		...sections,
		{
			id: 'example-plugin/design',
			title: '[Example]デザイン',
			order: 40,
		},
	]
);

addFilter(
	'ystandard.hooks.postSettings.items',
	'example-plugin/header-overlay',
	( items, context ) => [
		...items,
		{
			id: 'example-plugin/header-overlay',
			section: 'example-plugin/design',
			order: 10,
			Component: HeaderOverlaySetting,
		},
	]
);
```

設定コンポーネントには`postType`と`postId`が渡されます。外部設定の投稿メタ登録、取得、保存は、設定を提供するプラグイン側で`register_post_meta()`と`useEntityProp()`などを使って実装してください。テーマ内部の`ys_post_settings`オブジェクトへ外部設定を追加することは想定していません。

## 表示規則

- 設定項目が1件以上あるセクションだけを表示します。
- セクションと項目は`order`の昇順で表示します。同じ`order`ではフィルター適用後の登録順を維持します。
- 同じ`id`が複数ある場合は、後から追加された有効な定義を採用します。
- 不正な定義、Reactコンポーネントではない`Component`、未登録セクションを参照する項目は表示しません。
- 予約済みの標準セクションと同じ`id`を持つ外部セクション定義は表示しません。
- 1つの設定コンポーネントで例外が発生しても、ほかの設定項目は表示を継続します。
- 外部フィルターで例外が発生しても、yStandard標準設定は表示を継続します。
- 初回描画後に追加・削除されたフィルターも表示へ反映します。

## v4からの変更点

yStandard v5では従来のPHPメタボックスと、次の拡張経路を廃止します。

- `ys_meta_box_post`
- `ys_meta_box_seo`
- `ys_meta_box_sns`
- `ys_get_meta_box_post_types`
- `ys_block_editor_post_meta_fields`

外部プラグインの独自UIは、このJavaScript APIへ移行してください。REST保存後の連携用として`ys_save_post_meta_post`、`ys_save_post_meta_seo`、`ys_save_post_meta_sns`は引き続き実行されます。
