import { LengthAware, TableCellType } from '../Type/EditableTable3DataLineType'
import { TableParams } from '../Type/EditableTable3ParamType'

declare let tableData: { [tableName: string]: LengthAware }

export const reValueOrderNoColumn = (params: TableParams) => {
    const { tableName } = params

    const dataSource = tableData[tableName]
    if (dataSource && dataSource.data) {
        dataSource.data.forEach((dataLine, index) => {
            dataLine.order_no = (index + 1) as unknown as TableCellType
            // table11__order_no__text__14
            $(`#${tableName}__order_no__text__${index}`).val(index + 1)
            // const element = document.getElementById(
            //     `${tableName}__order_no__text__${index}`,
            // ) as HTMLInputElement
            // element && (element.value = (index + 1).toString())
        })
    }
}
