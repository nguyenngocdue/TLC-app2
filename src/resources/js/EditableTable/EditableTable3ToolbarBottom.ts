import { ToolbarComponent } from './ToolbarComponents'
import { TableParams } from './Type/EditableTable3ParamType'

export const makeToolBarBottom = (tableParams: TableParams) => {
    const { tableConfig } = tableParams
    const {
        showPaginationBottom: show,
        bottomCenterControl: bc,
        bottomLeftControl: bl,
        bottomRightControl: br,
    } = tableConfig
    if (!show) return ''

    if (bl || bc || br) {
        // console.log('makeToolBarBottom()', { bl, bc, br })
        return `<div component="tableToolbarBottom" class='w-full grid grid-cols-12 border-b border-red-50 rounded-b bg-gray-100 p-1vw px-4 py-3 text-xs-vw font-semibold1 tracking-wide text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300'>
        <span class='lg:col-span-4 lg:justify-start col-span-12 flex gap-1 justify-center'>
            ${bl ? new ToolbarComponent(tableParams).render(bl) : ''}
        </span>
        <span class="lg:col-span-4 lg:justify-center col-span-12 flex gap-1 justify-center">
            ${bc ? new ToolbarComponent(tableParams).render(bc) : ''}
        </span>
        <span class="lg:col-span-4 lg:justify-end col-span-12 flex gap-1 justify-center">
            ${br ? new ToolbarComponent(tableParams).render(br) : ''}
        </span>
    </div>`
    }

    return ''
}
