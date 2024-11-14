import { getDataSource, getNotFound } from '../../Function/CacheKByKey'
import { TableColumnDropdown } from '../../Type/EditableTable3ColumnType'
import { TableRendererParams } from '../../Type/EditableTable3DataLineType'

export class Dropdown4Edit {
    private tableDebug = false

    constructor(private params: TableRendererParams) {
        // this.tableDebug = this.params.params.tableConfig.tableDebug || false
    }

    render() {
        const cellValue = this.params.cellValue as unknown as string
        const column = this.params.column as TableColumnDropdown
        const { rendererAttrs = {}, dataIndex } = column
        const {
            // allowClear,
            // allowChooseWhenOneItem,
            // allowOpen,
            valueField = 'id',
            labelField = 'name',
            // descriptionField = 'description',
            tooltipField = valueField,
            // filterColumns,
            // filterOperator,
            // filterValues,
            // dataSource,
            // dataSourceKey,
        } = rendererAttrs

        const cbbDataSource = getDataSource(column)
        // console.log(dataIndex, cellValue, valueField, cbbDataSource)

        const selectedItem = cbbDataSource[cellValue]
        // console.log(cellValue, selectedItem)

        let result = ''
        if (selectedItem) {
            const label = selectedItem[labelField]
            const tooltip = selectedItem[tooltipField] || ''
            result = `<div class="" title="${tooltip}">${label}</div>`
        } else {
            result = getNotFound(valueField, cellValue)
        }

        return {
            rendered: result,
            classStr: this.params.column.classList || '',
        }
    }
}
