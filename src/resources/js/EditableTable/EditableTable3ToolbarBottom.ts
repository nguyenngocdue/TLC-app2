import { TableColumn } from './EditableTable3ColumnType'
import { TableConfig } from './EditableTable3ConfigType'
import { TableDataLine } from './EditableTable3DataLineType'

export const makeToolBarBottom = ({
    dataSource,
    columns,
    tableConfig,
}: {
    dataSource: TableDataLine[]
    columns: TableColumn[]
    tableConfig: TableConfig
}) => {
    if (!tableConfig.showPaginationBottom) return ''
    return `<div class='w-full grid grid-cols-12 border-b border-red-50 rounded-t bg-gray-100 p-1vw px-4 py-3 text-xs-vw font-semibold1 tracking-wide text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300'>
    <span class='lg:col-span-4 col-span-12 flex gap-1 justify-start'>
        ${tableConfig.bottomLeftControl || ''}
    </span>
    <span class="lg:col-span-4 col-span-12 flex gap-1 justify-center">
        ${tableConfig.bottomCenterControl || ''}
    </span>
    <span class="lg:col-span-4 col-span-12 grid grid-cols-12 sm:flex gap-1 justify-end items-center pt-2 sm:pt-0">
        ${tableConfig.bottomRightControl || ''}
    </span>
</div>`
}
