# ブロックエディター投稿設定の拡張API

このAPIは、yStandard 4.59.0-alpha-1以上、v5未満で利用できます。外部プラグインが独立したReactコンポーネントを投稿設定へ登録するための契約です。将来のyStandard v5投稿設定モーダルでも同じフックとデータ構造を使用します。

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

セクションは`ystandard.hooks.postSettings.sections`、設定項目は`ystandard.hooks.postSettings.items`へ登録します。いずれのフィルターにも現在の`PostSettingsContext`が第2引数として渡されます。

### yStandard標準セクション

yStandard標準パネルには、外部設定項目から参照できる予約済みIDがあります。

| セクションID | パネルタイトル |
|---|---|
| `ystandard/post` | `[ys] 投稿設定` |
| `ystandard/seo` | `[ys] SEO設定` |
| `ystandard/sns` | `[ys] SNS設定` |

標準セクションへ設定項目を追加する場合、セクションの登録は不要です。例えば、ToolboxのSEO設定は`ystandard/seo`を参照します。

```tsx
addFilter(
	'ystandard.hooks.postSettings.items',
	'ystandard-toolbox/seo-title',
	( items, context ) => [
		...items,
		{
			id: 'ystdtb/seo-title',
			section: 'ystandard/seo',
			order: 100,
			Component: SeoTitleSetting,
		},
	]
);

addFilter(
	'ystandard.hooks.postSettings.items',
	'ystandard-toolbox/seo-description',
	( items, context ) => [
		...items,
		{
			id: 'ystdtb/seo-description',
			section: 'ystandard/seo',
			order: 110,
			Component: SeoDescriptionSetting,
		},
	]
);
```

標準フィールドを先頭に表示し、その後へ外部設定項目を`order`順で表示します。`ystandard/*`の予約済みセクションを外部から再定義することはできません。

### 外部セクション

```tsx
import { addFilter } from '@wordpress/hooks';

addFilter(
	'ystandard.hooks.postSettings.sections',
	'ystandard-toolbox/design',
	( sections, context ) => [
		...sections,
		{
			id: 'ystdtb/design',
			title: '[Toolbox]デザイン',
			order: 20,
		},
	]
);

addFilter(
	'ystandard.hooks.postSettings.items',
	'ystandard-toolbox/header-overlay',
	( items, context ) => [
		...items,
		{
			id: 'ystdtb/header-overlay',
			section: 'ystdtb/design',
			order: 10,
			Component: HeaderOverlaySetting,
		},
	]
);
```

設定コンポーネントには`postType`と`postId`が渡されます。投稿メタの取得と保存は、設定コンポーネント自身が`useEntityProp()`などを使用して実装してください。

## 表示規則

- 設定項目が1件以上あるセクションだけを表示します。
- yStandard標準フィールドがあるセクションは、外部設定項目がなくても従来どおり表示します。
- `order`の昇順で表示します。同じ`order`ではフィルター適用後の追加順を維持します。
- 同じ`id`が複数ある場合は、後から追加された有効な定義を採用します。
- 不正な定義、Reactコンポーネントではない`Component`、未登録セクションを参照する設定項目は表示しません。
- 予約済みの標準セクションと同じ`id`を持つ外部セクション定義は表示しません。
- 1つの設定コンポーネントで例外が発生しても、ほかの設定項目は表示を継続します。
- 外部フィルターで例外が発生しても、yStandard標準パネルは表示を継続します。
- 初回描画後に追加・削除されたフィルターも表示へ反映します。

既存の`ys_block_editor_post_meta_fields`は、`text`、`textarea`、`toggle`を使用する従来フィールドの登録に引き続き使用します。独自UIはこのJavaScript APIへ登録してください。

yStandard 4.59以降で同じ設定をJavaScript APIへ移行する場合は、`ys_block_editor_post_meta_fields`による従来フィールドの追加を停止し、画面上に設定が重複しないようにしてください。
