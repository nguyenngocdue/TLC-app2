const trCssClass = `bg-gray-100 text-center text-xs font-semibold tracking-wide text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300`
export const TableSettings = {
    editableMode: {
        modeName: 'editable-mode',
        cssClass: {
            table_css: `whitespace-no-wrap w-full text-sm border-separate border-spacing-0`,
            thead: `sticky z-10 top-0`,
            thead_tr: trCssClass,
            thead_tr_th: `?`,

            tr_td: `?`,

            tfoot_tr: trCssClass,
        },
    },
    printableMode: {
        modeName: 'printable-mode',
        cssClass: {
            table_css: `w-full min-w-full max-w-full`,
            thead: `none`,
            thead_tr: `text-center 456`,
            thead_tr_th: `text-center bg-gray-50 border border-gray-400 py-2`,

            tr_td: `border border-gray-400 h-10`,

            tfoot_tr: trCssClass,
        },
    }
}