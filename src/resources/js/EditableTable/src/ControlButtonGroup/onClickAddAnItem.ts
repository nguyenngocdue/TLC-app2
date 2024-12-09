import moment from 'moment'
import { applyRenderedTRow } from '../EditableTable3ApplyRenderedTRow'
import { TableColumn, TableColumnPickerDateTime } from '../Type/EditableTable3ColumnType'
import { LengthAware, TableCellType, TableDataLine } from '../Type/EditableTable3DataLineType'
import { Caller, TableParams } from '../Type/EditableTable3ParamType'
import { renderOneEmptyRow } from '../VirtualScrolling/updateVirtualTableVisibleRows'
import { addClassToTr, addTrBeforeBtmSpacer, scrollToBottom } from '../Functions/TableManipulations'

declare let tableData: { [tableName: string]: LengthAware }
declare let tableColumns: { [tableName: string]: TableColumn[] }

const defaultValueForPk = (column: TableColumnPickerDateTime): string => {
    const pickerType = column.rendererAttrs?.pickerType || 'datetime'
    // console.log('pickerType', pickerType)
    switch (pickerType) {
        case 'time':
            // return moment().format('HH:mm:ss')
            return '01:00:00'
        case 'datetime':
            return moment.utc().format('YYYY-MM-DD HH:mm:ss')
        case 'date':
        case 'month':
        case 'year':
        default:
            return moment().format('YYYY-MM-DD')
    }
}

export const onClickAddAnItem = (params: TableParams) => {
    const { tableName } = params
    const dataSource = tableData[tableName]
    const columns = tableColumns[tableName]

    const newIndex = dataSource.data.length

    const newItem: TableDataLine = {}
    columns.forEach((column) => {
        if (column.renderer == 'no.') return
        let value: TableCellType
        switch (true) {
            case column.renderer == 'picker_datetime':
                const tmp1 = defaultValueForPk(column) as unknown as TableCellType
                // console.log('tmp1', tmp1)
                value = tmp1
                break
            case column.renderer == 'number':
            case column.renderer == 'number4':
                value = 0 as unknown as TableCellType
                break
            case column.renderer == 'toggle':
            case column.renderer == 'toggle4':
            case column.renderer == 'checkbox':
            case column.renderer == 'checkbox4':
                value = false as unknown as TableCellType
                break

            //==================
            case column.dataIndex == 'order_no':
                value = (newIndex + 1) as unknown as TableCellType
                break
            case column.dataIndex == 'name':
                value = `Line ${newIndex + 1}` as unknown as TableCellType
                break
            default:
                value = null as unknown as TableCellType
                break
        }
        newItem[column.dataIndex] = value
        // console.log('adding column', column)
    })
    // console.log('newItem', newItem)
    dataSource.data.push({ ...newItem, NEW_INSERTED_LINE: true as unknown as TableCellType })

    const index = dataSource.data.length - 1

    const emptyRow = renderOneEmptyRow(params, index, Caller.ON_CLICK_ADD_AN_ITEM)
    if (!emptyRow) return

    addTrBeforeBtmSpacer(tableName, emptyRow)
    applyRenderedTRow(params, newItem, index)
    scrollToBottom(tableName)
    addClassToTr(tableName, index, 'bg-green-100')
}
