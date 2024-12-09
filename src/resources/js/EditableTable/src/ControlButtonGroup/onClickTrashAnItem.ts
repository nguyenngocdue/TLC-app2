import { LengthAware, TableCellType } from '../Type/EditableTable3DataLineType'
import { TableParams } from '../Type/EditableTable3ParamType'

declare let tableData: { [tableName: string]: LengthAware }
// declare let tableColumns: { [tableName: string]: TableColumn[] }

export const onClickTrashAnItem = (params: TableParams, rowIndex: number) => {
    const dataSource = tableData[params.tableName]
    const dataLine = dataSource.data[rowIndex]

    let isDeleting = false
    if (!dataLine['DESTROY_THIS_LINE']) {
        dataLine['DESTROY_THIS_LINE'] = true as unknown as TableCellType
        isDeleting = true
    } else {
        delete dataLine['DESTROY_THIS_LINE']
    }

    // get the tr element
    const tr = document.getElementById(`${params.tableName}__${rowIndex}`)

    if (!tr) return
    if (isDeleting) {
        tr.classList.add('bg-red-600')
    } else {
        tr.classList.remove('bg-red-600')
    }
}
