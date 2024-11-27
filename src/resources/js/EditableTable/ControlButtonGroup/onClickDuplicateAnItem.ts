import { LengthAware, TableCellType } from '../Type/EditableTable3DataLineType'
import { TableParams } from '../Type/EditableTable3ParamType'

declare let tableData: { [tableName: string]: LengthAware }
// declare let tableColumns: { [tableName: string]: TableColumn[] }

export const onDuplicateAnItem = (params: TableParams, rowIndex: number) => {
    const dataSource = tableData[params.tableName]
    // const columns = tableColumns[params.tableName]

    const dataLine = dataSource.data[rowIndex]
    dataSource.data.push({ ...dataLine, NEW_INSERTED_LINE: true as unknown as TableCellType })
}
