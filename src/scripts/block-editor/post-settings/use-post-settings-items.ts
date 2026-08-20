/**
 * WordPress Dependencies
 */
import { useEffect, useMemo, useState } from '@wordpress/element';
import { addAction, applyFilters, removeAction } from '@wordpress/hooks';

/**
 * Internal Dependencies
 */
import { getStandardItems, getStandardSections } from './providers';
import {
	ITEM_FILTER,
	SECTION_FILTER,
	type PostSettingsContext,
	type PostSettingsItem,
	type PostSettingsSection,
} from './types';

const OBSERVER_NAMESPACE = 'ystandard/post-settings-host';

type Definition = PostSettingsSection | PostSettingsItem;

interface IndexedDefinition<T extends Definition> {
	definition: T;
	index: number;
}

/**
 * 不正な投稿設定定義を開発者へ通知する.
 *
 * @param type 定義種別.
 */
function warnInvalidDefinition(type: 'section' | 'item') {
	// 外部拡張の問題を特定できるよう、無視した定義種別だけを通知する.
	// eslint-disable-next-line no-console
	console.warn(`Invalid post settings ${type} definition was ignored.`);
}

/**
 * フィルターの例外で投稿設定モーダル全体が停止しないようにする.
 *
 * @param hookName フック名.
 * @param defaults 標準定義.
 * @param context  投稿設定コンテキスト.
 * @param type     定義種別.
 */
function applyDefinitionFilters<T extends Definition>(
	hookName: string,
	defaults: T[],
	context: PostSettingsContext,
	type: 'section' | 'item'
): T[] {
	try {
		const definitions = applyFilters(hookName, defaults, context);
		// 配列以外の戻り値は後続処理で扱えないため標準定義へ戻す.
		if (!Array.isArray(definitions)) {
			warnInvalidDefinition(type);
			return defaults;
		}
		return definitions as T[];
	} catch (error) {
		warnInvalidDefinition(type);
		return defaults;
	}
}

/**
 * 定義IDの重複を解決して表示順に並べる.
 *
 * @param definitions  定義一覧.
 * @param protectedIds 外部定義で置き換えないID.
 */
function sortDefinitions<T extends Definition>(
	definitions: T[],
	protectedIds: Set<string> = new Set()
): T[] {
	const definitionsById = new Map<string, IndexedDefinition<T>>();
	definitions.forEach((definition, index) => {
		// yStandard標準セクションは先頭の正本を維持し、外部の同名定義を無視する.
		if (
			protectedIds.has(definition.id) &&
			definitionsById.has(definition.id)
		) {
			return;
		}
		definitionsById.set(definition.id, { definition, index });
	});

	return Array.from(definitionsById.values())
		.sort((a, b) => {
			// 同じorderではフィルター登録順を維持する.
			if (a.definition.order === b.definition.order) {
				return a.index - b.index;
			}
			return a.definition.order - b.definition.order;
		})
		.map(({ definition }) => definition);
}

/**
 * 登録済みの投稿設定セクションを取得する.
 *
 * @param context 投稿設定コンテキスト.
 */
export function getPostSettingsSections(
	context: PostSettingsContext
): PostSettingsSection[] {
	const standard = getStandardSections(context);
	const protectedIds = new Set(standard.map((section) => section.id));
	const definitions = applyDefinitionFilters<PostSettingsSection>(
		SECTION_FILTER,
		standard.map((section) => ({ ...section })),
		context,
		'section'
	);
	const sections = [
		...standard,
		...definitions.filter(
			(definition) => !definition || !protectedIds.has(definition.id)
		),
	].filter((definition): definition is PostSettingsSection => {
		const isValid =
			definition !== null &&
			typeof definition === 'object' &&
			typeof definition.id === 'string' &&
			definition.id !== '' &&
			typeof definition.title === 'string' &&
			definition.title !== '' &&
			typeof definition.order === 'number' &&
			Number.isFinite(definition.order);
		// 不正な外部定義だけを除外し、ほかの項目は表示し続ける.
		if (!isValid) {
			warnInvalidDefinition('section');
		}
		return isValid;
	});

	return sortDefinitions(sections, protectedIds);
}

/**
 * 登録済みの投稿設定項目を取得する.
 *
 * @param context  投稿設定コンテキスト.
 * @param sections 検証済みセクション.
 */
export function getPostSettingsItems(
	context: PostSettingsContext,
	sections: PostSettingsSection[]
): PostSettingsItem[] {
	const definitions = applyDefinitionFilters<PostSettingsItem>(
		ITEM_FILTER,
		getStandardItems(context),
		context,
		'item'
	);
	const sectionIds = new Set(sections.map((section) => section.id));
	const items = definitions.filter(
		(definition): definition is PostSettingsItem => {
			const isValid =
				definition !== null &&
				typeof definition === 'object' &&
				typeof definition.id === 'string' &&
				definition.id !== '' &&
				typeof definition.section === 'string' &&
				sectionIds.has(definition.section) &&
				typeof definition.order === 'number' &&
				Number.isFinite(definition.order) &&
				typeof definition.Component === 'function';
			// 不正な外部定義だけを除外し、ほかの項目は表示し続ける.
			if (!isValid) {
				warnInvalidDefinition('item');
			}
			return isValid;
		}
	);

	return sortDefinitions(items);
}

/**
 * 投稿設定フックを監視して表示用の定義一覧を返す.
 *
 * @param context 投稿設定コンテキスト.
 */
export function usePostSettingsItems(context: PostSettingsContext) {
	const [revision, setRevision] = useState(0);

	useEffect(() => {
		/**
		 * 対象フックの追加・解除時だけ定義一覧を再取得する.
		 *
		 * @param hookName 変更されたフック名.
		 */
		const refreshDefinitions = (hookName: string) => {
			// 投稿設定の定義が変わった場合だけ再描画する.
			if (hookName === SECTION_FILTER || hookName === ITEM_FILTER) {
				setRevision((current) => current + 1);
			}
		};

		addAction(
			'hookAdded',
			`${OBSERVER_NAMESPACE}/added`,
			refreshDefinitions
		);
		addAction(
			'hookRemoved',
			`${OBSERVER_NAMESPACE}/removed`,
			refreshDefinitions
		);

		return () => {
			removeAction('hookAdded', `${OBSERVER_NAMESPACE}/added`);
			removeAction('hookRemoved', `${OBSERVER_NAMESPACE}/removed`);
		};
	}, []);

	return useMemo(() => {
		const sections = getPostSettingsSections(context);
		return {
			sections,
			items: getPostSettingsItems(context, sections),
		};
	}, [context.apiVersion, context.postId, context.postType, revision]);
}
