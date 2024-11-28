import { ToolbarComponent } from './ToolbarComponents'
import { TableParams } from './Type/EditableTable3ParamType'

export const makeToolBarTop = (tableParams: TableParams) => {
    const { tableConfig } = tableParams
    const {
        showPaginationTop: show,
        topCenterControl: tc,
        topLeftControl: tl,
        topRightControl: tr,
    } = tableConfig
    if (!show) return ''

    if (tl || tc || tr) {
        return `<div component="tableToolbarTop" class='w-full grid grid-cols-12 border-b border-red-50 rounded-b bg-gray-100 p-1vw px-4 py-3 text-xs-vw font-semibold1 tracking-wide text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300'>
        <span class='lg:col-span-4 lg:justify-start col-span-12 flex gap-1 justify-center'>
            ${tl ? new ToolbarComponent(tableParams).render(tl) : ''}
        </span>
        <span class="lg:col-span-4 lg:justify-center col-span-12 flex gap-1 justify-center">
            ${tc ? new ToolbarComponent(tableParams).render(tc) : ''}
        </span>
        <span class="lg:col-span-4 lg:justify-end col-span-12 flex gap-1 justify-center">
            ${tr ? new ToolbarComponent(tableParams).render(tr) : ''}
        </span>
    </div>`
    }

    return ''
}
