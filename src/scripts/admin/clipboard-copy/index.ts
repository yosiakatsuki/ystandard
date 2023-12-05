export function copyToClipboard() {
	document.addEventListener('DOMContentLoaded', () => {
		const copyButtons = document.querySelectorAll('.ys-clipboard-copy__button');

		const canWriteText = () => {
			// @ts-ignore
			navigator.permissions.query({name: "clipboard-write"}).then((result) => {
				return result.state === "granted" || result.state === "prompt";
			});
			return false;
		}

		// execCommand でのコピー.
		const execCommandCopy = (target: HTMLInputElement, info: HTMLElement) => {
			target.select();
			document.execCommand('copy');
			showInfo(info);
		}
		// 「コピーしました」の表示.
		const showInfo = (info: HTMLElement) => {
			const CLASS_NAME = 'is-show';
			info.classList.add(CLASS_NAME);
			setTimeout(() => {
				info.classList.remove(CLASS_NAME);
			}, 3000);
		}

		// クリップボードコピーボタンのセット.
		copyButtons.forEach((copyButton) => {
			copyButton.addEventListener('click', (e) => {
				e.preventDefault();
				const parent = copyButton.parentElement as HTMLElement;
				const copyTarget = parent.querySelector('.ys-clipboard-copy__target') as HTMLInputElement;
				const info = parent.querySelector('.ys-clipboard-copy__info') as HTMLElement;
				// クリップボード書き込み.
				if (canWriteText()) {
					navigator.clipboard.writeText(copyTarget.value).then(
						() => {
							showInfo(info);
						},
						() => {
							execCommandCopy(copyTarget, info);
						},
					);
				} else {
					execCommandCopy(copyTarget, info);
				}
			});
		});
	});
}
