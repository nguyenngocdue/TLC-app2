import { getDataSourceFromKBy } from '../../Function/CacheKByKey'
import { TableColumnDropdown } from '../../Type/EditableTable3ColumnType'
import { TableRendererParams } from '../../Type/EditableTable3DataLineType'

export class Dropdown4View {
    constructor(private params: TableRendererParams) {}

    render() {
        const cellValue = this.params.cellValue as unknown as string
        const column = this.params.column as TableColumnDropdown
        const { rendererAttrs = {}, dataIndex } = column
        const {
            allowClear,
            allowChooseWhenOneItem,
            allowOpen,
            valueField = 'id',
            labelField = 'name',
            descriptionField = 'description',
            tooltipField,
            filterColumns,
            filterOperator,
            filterValues,
            dataSource,
            dataSourceKey,
        } = rendererAttrs

        let cbbDataSource: { [key: string]: { [key: string]: string | number } } = {}
        if (typeof dataSourceKey == 'string') {
            cbbDataSource = getDataSourceFromKBy(column)
        } else {
            cbbDataSource = dataSource || {}
        }
        // console.log(dataIndex, cellValue, valueField, cbbDataSource)

        const selectedItem = cbbDataSource[cellValue]
        // console.log(cellValue, selectedItem)

        const result = selectedItem
            ? selectedItem[labelField] + ''
            : `<div class="text-orange-300 text-center font-bold bg-orange-700 rounded">NOT FOUND ${valueField} ${cellValue}</div>`

        return {
            rendered: result,
            classStr: this.params.column.classList || '',
        }
    }
}
