import { Str } from '../../EditableTable3Str'
import { getDataSource } from '../../Function/CacheKByKey'
import { TableColumnDropdown } from '../../Type/EditableTable3ColumnType'
import { Renderer4Edit } from '../Renderer4Edit'

export class Dropdown4Edit extends Renderer4Edit {
    protected tableDebug = false

    control() {
        const cellValue = this.cellValue as unknown as string
        const column = this.column as TableColumnDropdown
        const { rendererAttrs = {}, dataIndex } = column
        const {
            // allowClear,
            // allowChooseWhenOneItem,
            // allowOpen,
            valueField = 'id',
            labelField = 'name',
            // descriptionField = 'description',
            tooltipField = '',
            // filterColumns,
            // filterOperator,
            // filterValues,
            // dataSource,
            // dataSourceKey,
        } = rendererAttrs

        const cbbDataSource = getDataSource(column)
        // console.log(dataIndex, cellValue, valueField, cbbDataSource)

        const options = Object.keys(cbbDataSource).map((key) => {
            const item = cbbDataSource[key]
            const tooltip = item[tooltipField] || Str.makeId(item[valueField])
            return `<option value="${item[valueField]}" title="${tooltip}">
                ${item[labelField]}
            </option>`
        })

        return `<select>${options.join()}</select>`
    }

    applyScript(): void {}

    render() {
        let result = this.control()

        return {
            rendered: result,
            classStr: this.column.classList || '',
        }
    }
}
