const trCssClass = `bg-gray-100 text-center h-10`
export const TableSettings = {
    editableMode: {
        modeName: 'editable-mode',
        cssClass: {
            table_css: `w-full border-separate border-spacing-0 whitespace-no-wrap `,
            thead: `sticky z-10 top-0`,
            thead_tr: trCssClass,
            thead_tr_th: `?`,

            tbody_tr: `hover:bg-gray-100`,
            // tr_td: `?`,

            tfoot_tr: trCssClass,
        },
    },
    printableMode: {
        modeName: 'printable-mode',
        cssClass: {
            table_css: `w-full border-separate border-spacing-0 min-w-full max-w-full `,
            thead: `none`,
            thead_tr: `text-center 456`,
            thead_tr_th: `text-center bg-gray-50 border border-gray-400 py-2`,

            tbody_tr: `hover:bg-gray-100`,
            // tr_td: `border border-gray-400 h-10`,

            tfoot_tr: `text-center 456`,
        },
    }
}