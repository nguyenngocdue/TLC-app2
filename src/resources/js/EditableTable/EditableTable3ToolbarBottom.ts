import { TableParams } from './Type/EditableTable3ParamType'

export const makeToolBarBottom = (tableParams: TableParams) => {
    const { tableConfig, tableName } = tableParams
    const { bottomCenterControl: tc, bottomLeftControl: tl, bottomRightControl: tr } = tableConfig
    if (tl || tc || tr) {
        const classList =
            'w-full grid grid-cols-12 border-b border-red-50 rounded-b bg-gray-100 p-1vw px-2 py-1 text-xs-vw font-semibold1 tracking-wide text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300'
        return `<div component="tableToolbarBottom" class='${classList}'>
        <div id="${tableName}__Toolbar_Bottom_Left" class='lg:col-span-4 lg:justify-start col-span-12 flex gap-1 justify-center'>
        </div>
        <div id="${tableName}__Toolbar_Bottom_Center" class="lg:col-span-4 lg:justify-center col-span-12 flex gap-1 justify-center">
        </div>
        <div id="${tableName}__Toolbar_Bottom_Right" class="lg:col-span-4 lg:justify-end col-span-12 flex gap-1 justify-center">
        </div>
    </div>`
    }

    return ''
}
