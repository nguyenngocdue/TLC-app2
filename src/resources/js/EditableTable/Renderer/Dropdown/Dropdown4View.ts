import { getDataSource, getNotFound } from '../../Functions/CacheKByKey'
import { TableColumnDropdown } from '../../Type/EditableTable3ColumnType'
import { TableRenderedValueObject } from '../../Type/EditableTable3DataLineType'
import { Renderer4View } from '../Renderer4View'

export class Dropdown4View extends Renderer4View {
    render(): TableRenderedValueObject {
        const cellValue = this.cellValue as unknown as string
        const column = this.column as TableColumnDropdown
        const { rendererAttrs = {} } = column
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
        let tdClass = ''
        if (selectedItem) {
            const label = selectedItem[labelField]
            const tooltip = selectedItem[tooltipField] || ''
            result = `<div class="" title="${tooltip}">${label}</div>`
        } else {
            result = getNotFound()
            tdClass = 'bg-gray-50'
        }

        return {
            rendered: result,
            tdClass,
        }
    }
}
