import { TableParams } from './Type/EditableTable3ParamType'

export const makeToolBarTop = ({ tableConfig }: TableParams) => {
    if (!tableConfig.showPaginationTop) return ''
    return `<div class='w-full grid grid-cols-12 border-b border-red-50 rounded-t bg-gray-100 p-1vw px-4 py-3 text-xs-vw font-semibold1 tracking-wide text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300'>
    <span class='lg:col-span-4 lg:justify-start col-span-12 flex gap-1 justify-center'>
        ${tableConfig.topLeftControl || ''}
    </span>
    <span class="lg:col-span-4 lg:justify-center col-span-12 flex gap-1 justify-center">
        ${tableConfig.topCenterControl || ''}
    </span>
    <span class="lg:col-span-4 lg:justify-end col-span-12 flex gap-1 justify-center">
        ${tableConfig.topRightControl || ''}
    </span>
</div>`
}
