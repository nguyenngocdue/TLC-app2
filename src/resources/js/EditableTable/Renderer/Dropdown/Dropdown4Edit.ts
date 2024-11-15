import { Str } from '../../Function/Functions'
import { getDataSource } from '../../Function/CacheKByKey'
import { TableColumnDropdown } from '../../Type/EditableTable3ColumnType'
import { Renderer4Edit } from '../Renderer4Edit'

export class Dropdown4Edit extends Renderer4Edit {
    protected tableDebug = false

    getOptionExpensive = (column: TableColumnDropdown) => {
        const { rendererAttrs = {} } = column
        const cbbDataSource = getDataSource(column)
        const {
            valueField = 'id',
            labelField = 'name',
            // descriptionField = 'description',
            tooltipField = '',
        } = rendererAttrs
        const options = Object.keys(cbbDataSource).map((key) => {
            const item = cbbDataSource[key]
            const tooltip = item[tooltipField] || Str.makeId(item[valueField])
            return `<option value="${item[valueField]}" title="${tooltip}">
                ${item[labelField]}
            </option>`
        })
        return options.join('')
    }

    control() {
        // return this.cellValue as unknown as string
        const column = this.column as TableColumnDropdown
        // console.log(dataIndex, cellValue, valueField, cbbDataSource)

        // const options = this.getOptions(column)
        const options = this.getOptionExpensive(column)

        return `<select id="${this.controlId}" name="${this.controlName}">
            ${options}
        </select>`
    }

    applyPostScript = () => {
        // console.log('Dropdown4Edit.applyPostScript()', this)
        const column = this.column as TableColumnDropdown
        const { rendererAttrs = {}, dataIndex } = column
        const {
            allowClear,
            allowChooseWhenOneItem,
            allowOpen,
            valueField = 'id',
            labelField = 'name',
            // descriptionField = 'description',
            tooltipField = '',
        } = rendererAttrs
        $(`#${this.controlId}`).select2({
            placeholder: 'Select an option',
            allowClear,
        })
    }

    render() {
        let result = this.control()

        return {
            rendered: result,
            classStr: this.column.classList || '',
            applyPostScript: this.applyPostScript,
        }
    }
}
