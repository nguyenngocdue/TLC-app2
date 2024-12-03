import { TableColumn } from './Type/EditableTable3ColumnType'
import { TableParams } from './Type/EditableTable3ParamType'

declare let tableColumns: { [tableName: string]: TableColumn[] }

export const ColumnNoValue: TableColumn = {
    dataIndex: '_no_',
    width: 50,
    title: 'No.',
    renderer: 'no.',
    align: 'center',
    fixed: 'left',
}

export const makeUpDefaultValue = ({ tableName }: TableParams) => {
    let columns = tableColumns[tableName]
    if (columns) {
        columns = columns.map((column) => {
            if (column.renderer == 'checkbox_for_line') {
                column.dataIndex = '_checkbox_for_line_'
                column.title = ''
                column.width = 40
            }
            return { ...column, width: column.width || 100 }
        })
    }
    return columns
}

export const convertArrayToLengthAware = (dataSource: any[]) => {
    return {
        current_page: 1,
        data: dataSource,
        first_page_url: '',
        from: 1,
        last_page: 1,
        last_page_url: '',
        links: [],
        next_page_url: null,
        path: '',
        per_page: 15,
        prev_page_url: null,
        to: 1,
        total: dataSource.length,
    }
}

export const getTooltip = (column: TableColumn) => {
    if (column.tooltip) return column.tooltip
    return [
        column.dataIndex ? `+DataIndex: ${column.dataIndex}` : '',
        column.renderer ? `+Renderer: ${column.renderer}` : '',
        column.width ? `+Width: ${column.width}` : '',
    ]
        .filter((i) => i)
        .join('\n')
}

// export const makeUpPaginator = (tableConfig: TableConfig, dataSource: LengthAware) => {
//     if (tableConfig.showPaginationTop) {
//         if (tableConfig.topLeftControl == 'paginator')
//             tableConfig.topLeftControl = Paginator(dataSource)
//         if (tableConfig.topCenterControl == 'paginator')
//             tableConfig.topCenterControl = Paginator(dataSource)
//         if (tableConfig.topRightControl == 'paginator')
//             tableConfig.topRightControl = Paginator(dataSource)
//     }
//     if (tableConfig.showPaginationBottom) {
//         if (tableConfig.bottomLeftControl == 'paginator')
//             tableConfig.bottomLeftControl = Paginator(dataSource)
//         if (tableConfig.bottomCenterControl == 'paginator')
//             tableConfig.bottomCenterControl = Paginator(dataSource)
//         if (tableConfig.bottomRightControl == 'paginator')
//             tableConfig.bottomRightControl = Paginator(dataSource)
//     }
// }
