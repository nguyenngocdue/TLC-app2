export const TableSettings = {
    editableMode: {
        modeName: 'editable-mode',
        cssClass: {
            thead: `sticky z-10 top-0`,
            thead_tr: `bg-gray-100 text-center h-10`,
            thead_tr_th: `?`,

            tbody_tr: `hover:bg-gray-100`,
            tbody_tr_td: `border p-1`,

            tfoot_tr: `bg-gray-100 text-center h-10`,
        },
    },
    printableMode: {
        modeName: 'printable-mode',
        cssClass: {
            thead: `none`,
            thead_tr: `text-center 456`,
            thead_tr_th: `text-center bg-gray-50 border border-gray-400 py-2`,

            tbody_tr: `hover:bg-gray-100`,
            tbody_tr_td: `border p-1`,

            tfoot_tr: `text-center 456`,
        },
    }
}